<?php
final class RateLimit
{
    public static function allow(string $action, int $max, int $seconds): bool {
        $pdo = Database::get(); $key = hash('sha256', $action . ':' . ($_SERVER['REMOTE_ADDR'] ?? 'cli'));
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM rate_limits WHERE action_key = ?' . (DB_DRIVER === 'mysql' ? ' FOR UPDATE' : '')); $stmt->execute([$key]); $row = $stmt->fetch();
            if (!$row) $pdo->prepare('INSERT INTO rate_limits (action_key, attempts, window_start) VALUES (?, 1, ?)')->execute([$key,time()]);
            elseif (time() - (int)$row['window_start'] >= $seconds) $pdo->prepare('UPDATE rate_limits SET attempts = 1, window_start = ? WHERE id = ?')->execute([time(),$row['id']]);
            elseif ((int)$row['attempts'] >= $max) { $pdo->commit(); return false; }
            else $pdo->prepare('UPDATE rate_limits SET attempts = attempts + 1 WHERE id = ?')->execute([$row['id']]);
            $pdo->commit(); return true;
        } catch (Throwable $e) { if ($pdo->inTransaction()) $pdo->rollBack(); throw $e; }
    }
}
