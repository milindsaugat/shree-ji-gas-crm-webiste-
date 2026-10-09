<?php
declare(strict_types=1);

final class EmailTemplate
{
    /**
     * Renders base responsive HTML email wrapper with light & dark mode support.
     */
    public static function wrap(string $title, string $preheader, string $bodyContent): string
    {
        $siteName = e(setting('tagline') ? 'Shree Ji Gas Industries' : SITE_NAME);
        $phone = e(setting('phone', '+91 7060069159'));
        $email = e(setting('email', 'shreejigasteam@gmail.com'));
        $address = e(setting('address', '33/103, Pratappura, Agra, Uttar Pradesh — 282001'));
        $year = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{$title}</title>
    <style>
        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: #f0f6f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1a2d42;
            line-height: 1.6;
        }
        /* Mobile Responsive */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; padding: 10px 8px !important; }
            .email-card { padding: 22px 18px !important; border-radius: 6px !important; }
            .header-cell { padding: 22px 18px !important; }
            .field-label { width: 100% !important; display: block !important; margin-bottom: 4px !important; }
            .field-value { width: 100% !important; display: block !important; }
            .field-link { width: 100% !important; display: block !important; }
            .equipment-wrap { width: 100% !important; display: block !important; margin-top: 2px !important; }
            .equipment-badge { display: inline-block !important; width: auto !important; max-width: 100% !important; }
            .action-btn-wa, .action-btn-call { display: block !important; width: 100% !important; box-sizing: border-box !important; text-align: center !important; }
        }
        /* Dark Mode Preferences */
        @media (prefers-color-scheme: dark) {
            body, .email-body-table { background-color: #0b1522 !important; }
            .email-card { background-color: #142234 !important; border-color: #23374d !important; box-shadow: none !important; }
            .brand-title { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .brand-tagline { color: #95d2de !important; -webkit-text-fill-color: #95d2de !important; }
            .email-title { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .email-text { color: #d6e5f0 !important; }
            .email-muted { color: #8fa7be !important; }
            .field-box { background-color: #1a2c42 !important; border-color: #263e5b !important; }
            .field-row { border-bottom-color: #263e5b !important; }
            .field-label { color: #9bb3c7 !important; -webkit-text-fill-color: #9bb3c7 !important; }
            .field-value { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .field-link { color: #38bdf8 !important; -webkit-text-fill-color: #38bdf8 !important; }
            .field-time { color: #8fa7be !important; }
            .quote-box { background-color: #17273a !important; border-color: #2e8fa8 !important; color: #eaf3f8 !important; -webkit-text-fill-color: #eaf3f8 !important; }
            .footer-text { color: #7892a7 !important; }
            .badge-pill { background-color: #0c2d48 !important; color: #38bdf8 !important; border-color: #0ea5e9 !important; -webkit-text-fill-color: #38bdf8 !important; }
            .section-subheading { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .equipment-badge { background-color: #0c2d48 !important; color: #38bdf8 !important; border: 1px solid #0ea5e9 !important; -webkit-text-fill-color: #38bdf8 !important; }
            .action-btn-wa { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .action-btn-call { color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; }
            .otp-box { background-color: #0f1c2d !important; border-color: #2e8fa8 !important; }
        }
        /* Gmail App / Samsung Mail / Outlook mobile anti-inversion hooks */
        [data-ogsc] .brand-title, [data-ogsb] .brand-title, u + .body .brand-title {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        [data-ogsc] .brand-tagline, [data-ogsb] .brand-tagline, u + .body .brand-tagline {
            color: #95d2de !important;
            -webkit-text-fill-color: #95d2de !important;
        }
        [data-ogsc] .email-title, [data-ogsb] .email-title, u + .body .email-title {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        [data-ogsc] .section-subheading, [data-ogsb] .section-subheading, u + .body .section-subheading {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        [data-ogsc] .equipment-badge, [data-ogsb] .equipment-badge, u + .body .equipment-badge {
            background-color: #0c2d48 !important;
            color: #38bdf8 !important;
            -webkit-text-fill-color: #38bdf8 !important;
            border: 1px solid #0ea5e9 !important;
        }
        [data-ogsc] .action-btn-wa, [data-ogsb] .action-btn-wa, u + .body .action-btn-wa {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        [data-ogsc] .action-btn-call, [data-ogsb] .action-btn-call, u + .body .action-btn-call {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
    </style>
</head>
<body class="body" style="margin:0; padding:0; background-color:#f0f6f8;">
    <!-- Preheader preview text (hidden) -->
    <div style="display:none; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; mso-hide:all;">
        {$preheader}
    </div>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-body-table" style="background-color:#f0f6f8; padding:24px 0 36px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="max-width:600px; width:100%; margin:0 auto; padding:0 16px;">
                    
                    <!-- BRAND HEADER -->
                    <tr>
                        <td align="center" style="padding-bottom:18px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#1b3d6f; background:linear-gradient(135deg, #0f2747 0%, #1b3d6f 100%); border-radius:8px 8px 0 0; border-top:4px solid #2e8fa8;">
                                <tr>
                                    <td class="header-cell" style="padding:26px 30px; text-align:left;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="58" valign="middle" style="padding-right:16px;">
                                                    <img src="cid:logo@shreejigas" alt="Shree Ji Gas" width="52" height="52" style="display:block; border-radius:50%; background:#ffffff; padding:2px; border:1px solid #7ec8d4;" />
                                                </td>
                                                <td valign="middle">
                                                    <span class="brand-title" style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif; font-size:18px; font-weight:800; color:#ffffff !important; -webkit-text-fill-color:#ffffff !important; letter-spacing:0.04em; display:block; line-height:1.2; text-shadow:0 0 1px #ffffff;">
                                                        SHREE JI GAS INDUSTRIES
                                                    </span>
                                                    <span class="brand-tagline" style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif; font-size:9px; font-weight:700; color:#95d2de !important; -webkit-text-fill-color:#95d2de !important; letter-spacing:0.18em; text-transform:uppercase; display:block; margin-top:3px; text-shadow:0 0 1px #95d2de;">
                                                        Medical &amp; Industrial Gas Infrastructure · Agra
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- MAIN CARD CONTENT -->
                    <tr>
                        <td>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-card" style="background-color:#ffffff; border:1px solid #d8e8ec; border-top:none; border-radius:0 0 8px 8px; padding:32px 34px; box-shadow:0 4px 14px rgba(27,61,111,0.06);">
                                <tr>
                                    <td>
                                        {$bodyContent}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BRAND FOOTER -->
                    <tr>
                        <td style="padding-top:22px; text-align:center;">
                            <p class="footer-text" style="font-size:11px; color:#688298; line-height:1.7; margin:0 0 10px;">
                                <strong>Shree Ji Gas Industries</strong><br>
                                {$address}<br>
                                Hotline: <a href="tel:{$phone}" style="color:#2e8fa8; text-decoration:none; font-weight:600;">{$phone}</a> · 
                                Email: <a href="mailto:{$email}" style="color:#2e8fa8; text-decoration:none;">{$email}</a>
                            </p>
                            <p class="footer-text" style="font-size:10px; color:#88a0b3; line-height:1.6; margin:0; border-top:1px solid #dce8ec; padding-top:12px;">
                                Licensed PESO Refill Depot · Processed in accordance with DPDP Act (2023)<br>
                                &copy; {$year} Shree Ji Gas Industries. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    /**
     * Creates HTML & Plain text email for new website enquiries.
     */
    public static function enquiry(array $data, int $id): array
    {
        $name = e($data['name'] ?? 'Website Visitor');
        $phone = e($data['phone'] ?? 'N/A');
        $cleanPhone = preg_replace('/\D/', '', $data['phone'] ?? '');
        $email = e($data['email'] ?? 'Not provided');
        $product = e($data['product'] ?? 'General enquiry');
        $message = nl2br(e($data['message'] ?? ''));
        $time = date('d M Y, h:i A') . ' IST';
        $adminUrl = url('admin/enquiries');
        $waUrl = 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode("Hello {$data['name']}, thank you for contacting Shree Ji Gas Industries regarding {$product}.");

        $subject = "New Enquiry #{$id} from {$data['name']} — Shree Ji Gas Industries";
        $preheader = "New equipment enquiry #{$id} received from {$name} for {$product}.";

        $bodyContent = <<<HTML
        <!-- Eyebrow Badge -->
        <div style="margin-bottom:16px;">
            <span class="badge-pill" style="display:inline-block; background-color:#eaf7fa; color:#1b3d6f; border:1px solid #c8e8ef; padding:4px 12px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border-radius:20px;">
                Official Website Enquiry #{$id}
            </span>
        </div>

        <!-- Heading -->
        <h1 class="email-title" style="margin:0 0 12px; font-size:22px; font-weight:700; color:#1b3d6f; line-height:1.3;">
            Equipment Consultation Request
        </h1>
        <p class="email-text" style="margin:0 0 22px; font-size:14px; color:#4a6279; line-height:1.6;">
            A new enquiry has been submitted through the official procurement desk on <strong>shreejigas.com</strong>.
        </p>

        <!-- Customer Data Table / Fields -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="field-box" style="background-color:#f6fbfc; border:1px solid #e1eef2; border-radius:6px; margin-bottom:24px; padding:4px 0; border-collapse:collapse;">
            <tr>
                <td class="field-row" style="padding:11px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; letter-spacing:0.06em; display:inline-block; width:130px;">Customer Name</span>
                    <strong class="field-value" style="font-size:14px; color:#1b3d6f;">{$name}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-row" style="padding:11px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; letter-spacing:0.06em; display:inline-block; width:130px;">Phone Number</span>
                    <a href="tel:{$phone}" class="field-link" style="font-size:14px; color:#2e8fa8; font-weight:700; text-decoration:none;">{$phone}</a>
                </td>
            </tr>
            <tr>
                <td class="field-row" style="padding:11px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; letter-spacing:0.06em; display:inline-block; width:130px;">Email Address</span>
                    <a href="mailto:{$email}" class="field-link" style="font-size:14px; color:#2e8fa8; text-decoration:none;">{$email}</a>
                </td>
            </tr>
            <tr>
                <td class="field-row" style="padding:11px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; letter-spacing:0.06em; display:inline-block; width:130px; vertical-align:middle;">Equipment Interest</span>
                    <div class="equipment-wrap" style="display:inline-block; vertical-align:middle;">
                        <span class="equipment-badge" style="font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif; font-size:13px; font-weight:700; background-color:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; padding:4px 12px; border-radius:18px; display:inline-block; letter-spacing:0.02em;">
                            {$product}
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="field-row" style="padding:11px 18px;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; letter-spacing:0.06em; display:inline-block; width:130px;">Submission Time</span>
                    <span class="field-value field-time" style="font-size:13px; color:#5c7488;">{$time}</span>
                </td>
            </tr>
        </table>

        <!-- Enquiry Message Block -->
        <div style="margin-bottom:26px;">
            <div class="section-subheading" style="font-size:12px; font-weight:700; text-transform:uppercase; color:#1b3d6f; letter-spacing:0.06em; margin-bottom:8px;">
                Customer Requirements &amp; Message:
            </div>
            <div class="quote-box" style="background:#f4f9fb; border-left:4px solid #2e8fa8; padding:16px 20px; font-size:13px; color:#27435b; line-height:1.7; border-radius:0 4px 4px 0;">
                {$message}
            </div>
        </div>

        <!-- Quick Action Buttons: Bulletproof Vertical Stacking for Mobile & Desktop -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:24px; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
            <tr>
                <td style="padding-bottom:12px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                        <tr>
                            <td align="center" style="background-color:#16a34a; background:linear-gradient(135deg, #15803d 0%, #22c55e 100%); border-radius:6px;">
                                <a href="{$waUrl}" target="_blank" class="action-btn-wa" style="display:block; width:100%; box-sizing:border-box; padding:13px 20px; font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif; font-size:13px; font-weight:700; color:#ffffff !important; -webkit-text-fill-color:#ffffff !important; text-decoration:none; text-align:center; letter-spacing:0.02em;">
                                    Reply on WhatsApp ↗
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                        <tr>
                            <td align="center" style="background-color:#1b3d6f; background:linear-gradient(135deg, #0f2747 0%, #1b3d6f 100%); border-radius:6px; border:1px solid #2e8fa8;">
                                <a href="tel:{$phone}" class="action-btn-call" style="display:block; width:100%; box-sizing:border-box; padding:13px 20px; font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif; font-size:13px; font-weight:700; color:#ffffff !important; -webkit-text-fill-color:#ffffff !important; text-decoration:none; text-align:center; letter-spacing:0.02em;">
                                    Call Customer ↗
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
HTML;

        $plain = "New Website Enquiry #{$id} — Shree Ji Gas Industries\r\n"
               . "====================================================\r\n\r\n"
               . "Customer Name: {$data['name']}\r\n"
               . "Phone Number:  {$data['phone']}\r\n"
               . "Email Address: {$data['email']}\r\n"
               . "Equipment:     {$data['product']}\r\n"
               . "Submitted At:  {$time}\r\n\r\n"
               . "Message:\r\n"
               . "{$data['message']}\r\n\r\n"
               . "---\r\n"
               . "Shree Ji Gas Industries · Agra\r\n"
               . "Phone: " . setting('phone') . "\r\n";

        return [
            'subject' => $subject,
            'html' => self::wrap($subject, $preheader, $bodyContent),
            'plain' => $plain
        ];
    }

    /**
     * Creates HTML & Plain text email for Admin OTP / Security Verification.
     */
    public static function otp(string $name, string $otp, string $desc, ?string $link = null): array
    {
        $subject = 'Your Shree Ji Security Verification Code: ' . $otp;
        $preheader = "Your 6-digit verification code is {$otp}. Valid for 10 minutes.";

        $linkHtml = $link ? <<<HTML
        <p style="margin:20px 0 0; font-size:12px; color:#55728a;">
            Or open this direct authorization link:<br>
            <a href="{$link}" style="color:#2e8fa8; word-break:break-all;">{$link}</a>
        </p>
HTML : '';

        $bodyContent = <<<HTML
        <div style="margin-bottom:16px;">
            <span class="badge-pill" style="display:inline-block; background-color:#fef3e2; color:#b45309; border:1px solid #fde68a; padding:4px 12px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border-radius:20px;">
                Security Verification
            </span>
        </div>

        <h1 class="email-title" style="margin:0 0 10px; font-size:22px; font-weight:700; color:#1b3d6f;">
            Admin Authorization Code
        </h1>
        <p class="email-text" style="margin:0 0 24px; font-size:14px; color:#4a6279; line-height:1.6;">
            Hello <strong>{$name}</strong>,<br>
            {$desc}
        </p>

        <!-- OTP Display Box -->
        <div style="text-align:center; margin:26px 0;">
            <div style="display:inline-block; background:#1b3d6f; color:#ffffff; font-family:monospace; font-size:32px; font-weight:700; letter-spacing:8px; padding:16px 36px; border-radius:8px; border:2px solid #2e8fa8;">
                {$otp}
            </div>
            <p class="email-muted" style="margin:12px 0 0; font-size:12px; color:#68869b;">
                This code expires in <strong>10 minutes</strong>. Do not share it with anyone.
            </p>
        </div>

        {$linkHtml}
HTML;

        $plain = "Shree Ji Gas Industries — Security Verification Code\r\n"
               . "====================================================\r\n\r\n"
               . "Hello {$name},\r\n\r\n"
               . "{$desc}\r\n\r\n"
               . "VERIFICATION CODE: {$otp}\r\n\r\n"
               . "This code expires in 10 minutes.\r\n"
               . ($link ? "Reset link: {$link}\r\n\r\n" : "")
               . "If you did not request this code, please check your admin account security.\r\n";

        return [
            'subject' => $subject,
            'html' => self::wrap($subject, $preheader, $bodyContent),
            'plain' => $plain
        ];
    }

    /**
     * Creates HTML & Plain text email for SMTP Gateway Test.
     */
    public static function test(string $recipient, string $timestamp): array
    {
        $subject = 'Shree Ji Gas SMTP Gateway Test Dispatch';
        $preheader = 'SMTP Gateway connection and transactional socket delivery verified.';
        $host = e(setting('smtp_host', 'smtp.gmail.com'));
        $port = e(setting('smtp_port', '587'));
        $enc = strtoupper(e(setting('smtp_encryption', 'tls')));

        $bodyContent = <<<HTML
        <div style="margin-bottom:16px;">
            <span class="badge-pill" style="display:inline-block; background-color:#e6f9f0; color:#18734e; border:1px solid #b7ecd4; padding:4px 12px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; border-radius:20px;">
                Gateway Verification · Live Connected
            </span>
        </div>

        <h1 class="email-title" style="margin:0 0 10px; font-size:22px; font-weight:700; color:#1b3d6f;">
            SMTP Gateway Is Operational
        </h1>
        <p class="email-text" style="margin:0 0 22px; font-size:14px; color:#4a6279; line-height:1.6;">
            This automated test message confirms that your transactional SMTP email gateway for <strong>Shree Ji Gas Industries</strong> is configured correctly and successfully delivering live emails over socket encryption.
        </p>

        <!-- Gateway Diagnostics Box -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="field-box" style="background-color:#f6fbfc; border:1px solid #e1eef2; border-radius:6px; margin-bottom:20px;">
            <tr>
                <td style="padding:10px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; width:140px; display:inline-block;">SMTP Server</span>
                    <strong class="field-value" style="font-size:13px; color:#1b3d6f;">{$host}:{$port} ({$enc})</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 18px; border-bottom:1px solid #e8f2f5;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; width:140px; display:inline-block;">Recipient</span>
                    <strong class="field-value" style="font-size:13px; color:#2e8fa8;">{$recipient}</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 18px;">
                    <span class="field-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#68869b; width:140px; display:inline-block;">Verification Time</span>
                    <span class="field-value" style="font-size:13px; color:#5c7488;">{$timestamp}</span>
                </td>
            </tr>
        </table>

        <p class="email-muted" style="margin:0; font-size:12px; color:#68869b; line-height:1.6;">
            All administrative OTPs, password reset links, and customer enquiry alerts will now be dispatched in real-time through this gateway.
        </p>
HTML;

        $plain = "Shree Ji Gas SMTP Gateway Test Dispatch\r\n"
               . "========================================\r\n\r\n"
               . "Status: Operational\r\n"
               . "SMTP Host: {$host}:{$port} ({$enc})\r\n"
               . "Recipient: {$recipient}\r\n"
               . "Timestamp: {$timestamp}\r\n\r\n"
               . "This confirms that transactional SMTP socket sending is operational.\r\n";

        return [
            'subject' => $subject,
            'html' => self::wrap($subject, $preheader, $bodyContent),
            'plain' => $plain
        ];
    }
}
