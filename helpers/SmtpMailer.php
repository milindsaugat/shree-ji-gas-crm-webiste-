<?php
final class SmtpMailer
{
    private static function expect($socket, array $codes): void {
        $line = '';
        while (($part = fgets($socket, 515)) !== false) { $line = $part; if (isset($part[3]) && $part[3] === ' ') break; }
        $code = (int)substr($line, 0, 3);
        if (!in_array($code, $codes, true)) throw new RuntimeException('SMTP server rejected request (Code '.$code.'): '.$line);
    }
    private static function command($socket, string $command, array $codes): void { fwrite($socket, $command."\r\n"); self::expect($socket, $codes); }

    public static function isConfigured(): bool {
        $enabled = setting('smtp_enabled', (string)(defined('SMTP_ENABLED') && SMTP_ENABLED ? '1' : '0')) === '1';
        $user = setting('smtp_user', defined('SMTP_USER') ? SMTP_USER : '');
        $pass = setting('smtp_pass', defined('SMTP_PASS') ? SMTP_PASS : '');
        return $enabled && !empty($user) && !empty($pass);
    }

    public static function send(string $to, string $subject, string $body, ?string $html = null): void {
        $enabled = setting('smtp_enabled', (string)(defined('SMTP_ENABLED') && SMTP_ENABLED ? '1' : '0')) === '1';
        $host = setting('smtp_host', defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com') ?: 'smtp.gmail.com';
        $port = (int)(setting('smtp_port', defined('SMTP_PORT') ? (string)SMTP_PORT : '587') ?: 587);
        $user = setting('smtp_user', defined('SMTP_USER') ? SMTP_USER : '');
        $pass = setting('smtp_pass', defined('SMTP_PASS') ? SMTP_PASS : '');
        if (str_contains($host, 'gmail.com') || (strlen($pass) === 19 && substr_count($pass, ' ') === 3)) {
            $pass = str_replace(' ', '', $pass);
        }
        $fromEmail = setting('smtp_from_email', setting('smtp_user', defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : '')) ?: $user;
        if (str_contains($host, 'gmail.com') && $user && filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = $user;
        }
        $fromName = setting('smtp_from_name', setting('tagline', defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : SITE_NAME)) ?: SITE_NAME;
        $encryption = setting('smtp_encryption', 'tls');

        if (!$html && class_exists('EmailTemplate')) {
            $html = EmailTemplate::wrap($subject, mb_strimwidth($body, 0, 120, '...'), '<div style="font-size:14px; line-height:1.7; color:#1a2d42;">' . nl2br(e($body)) . '</div>');
        }

        if (!$enabled || !$user || !$pass || !$fromEmail) {
            $logDir = ROOT . '/storage/logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
            file_put_contents($logDir . '/mail.log', '['.date('Y-m-d H:i:s')."] Simulated mail to <$to> | Subject: $subject\n$body\n---\n", FILE_APPEND);
            if (getenv('ALLOW_LOCAL_DB_TESTS') === '1' || !self::isConfigured()) {
                return;
            }
            throw new RuntimeException('SMTP is not enabled. Configure SMTP Host, Username and App Password in Admin Settings.');
        }

        if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('The recipient or sender email address is invalid.');
        }

        $remoteTarget = ($encryption === 'ssl' || $port === 465) ? 'ssl://' . $host . ':' . $port : 'tcp://' . $host . ':' . $port;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT,
            ]
        ]);
        $socket = @stream_socket_client($remoteTarget, $errno, $errstr, 8, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) throw new RuntimeException('Unable to connect to SMTP server '.$host.':'.$port.' ('.$errstr.')');
        stream_set_timeout($socket, 8);
        try {
            self::expect($socket, [220]);
            self::command($socket, 'EHLO localhost', [250]);
            if ($encryption === 'tls' && $port !== 465) {
                self::command($socket, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                    throw new RuntimeException('Unable to establish TLS encryption with SMTP server.');
                }
                self::command($socket, 'EHLO localhost', [250]);
            }
            self::command($socket, 'AUTH LOGIN', [334]);
            self::command($socket, base64_encode($user), [334]);
            self::command($socket, base64_encode($pass), [235]);
            self::command($socket, 'MAIL FROM:<'.$fromEmail.'>', [250]);
            self::command($socket, 'RCPT TO:<'.$to.'>', [250, 251]);
            fwrite($socket, "DATA\r\n");
            self::expect($socket, [354]);

            $headers = 'From: ' . mb_encode_mimeheader($fromName) . ' <' . $fromEmail . ">\r\n"
                     . 'To: <' . $to . ">\r\n"
                     . 'Subject: ' . mb_encode_mimeheader($subject) . "\r\n"
                     . 'Date: ' . date('r') . "\r\n"
                     . 'MIME-Version: 1.0' . "\r\n";

            $logoPath = ROOT . '/assets/images/logo.png';
            $hasLogo = is_file($logoPath);
            $boundaryRelated = '=_rel_' . bin2hex(random_bytes(10));
            $boundaryAlt = '=_alt_' . bin2hex(random_bytes(10));

            if ($html) {
                if ($hasLogo) {
                    $headers .= 'Content-Type: multipart/related; boundary="' . $boundaryRelated . '"' . "\r\n";
                    $logoData = chunk_split(base64_encode(file_get_contents($logoPath)));

                    $payload = "--{$boundaryRelated}\r\n"
                             . "Content-Type: multipart/alternative; boundary=\"{$boundaryAlt}\"\r\n\r\n"
                             . "--{$boundaryAlt}\r\n"
                             . "Content-Type: text/plain; charset=UTF-8\r\n"
                             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                             . $body . "\r\n\r\n"
                             . "--{$boundaryAlt}\r\n"
                             . "Content-Type: text/html; charset=UTF-8\r\n"
                             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                             . $html . "\r\n\r\n"
                             . "--{$boundaryAlt}--\r\n\r\n"
                             . "--{$boundaryRelated}\r\n"
                             . "Content-Type: image/png; name=\"logo.png\"\r\n"
                             . "Content-Transfer-Encoding: base64\r\n"
                             . "Content-ID: <logo@shreejigas>\r\n"
                             . "Content-Disposition: inline; filename=\"logo.png\"\r\n\r\n"
                             . $logoData . "\r\n"
                             . "--{$boundaryRelated}--\r\n";
                } else {
                    $headers .= 'Content-Type: multipart/alternative; boundary="' . $boundaryAlt . '"' . "\r\n";
                    $payload = "--{$boundaryAlt}\r\n"
                             . "Content-Type: text/plain; charset=UTF-8\r\n"
                             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                             . $body . "\r\n\r\n"
                             . "--{$boundaryAlt}\r\n"
                             . "Content-Type: text/html; charset=UTF-8\r\n"
                             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                             . $html . "\r\n\r\n"
                             . "--{$boundaryAlt}--\r\n";
                }
            } else {
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $payload = $body;
            }

            $payload = preg_replace('/^\./m', '..', $headers . "\r\n" . $payload) . "\r\n.\r\n";
            fwrite($socket, $payload);
            self::expect($socket, [250]);
            self::command($socket, 'QUIT', [221, 250]);
        } finally {
            if (is_resource($socket)) fclose($socket);
        }
    }
}
