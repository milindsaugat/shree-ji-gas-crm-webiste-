<?php
final class Auth
{
    public static function user(): ?array {
        if (isset($_SESSION['admin']) && time() - ($_SESSION['last_activity'] ?? 0) > 1800) unset($_SESSION['admin']);
        if (isset($_SESSION['admin'])) $_SESSION['last_activity'] = time();
        return $_SESSION['admin'] ?? null;
    }
    public static function guard(): void { if (!self::user()) redirect('admin/login'); }
    public static function localSetup(): bool { return in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true); }
    public static function hasAdmin(): bool { return (bool)Database::get()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn(); }
    public static function findByIdentifier(string $identifier): ?array {
        $identifier = trim($identifier);
        $stmt = Database::get()->prepare('SELECT * FROM admin_users WHERE LOWER(email) = LOWER(?) OR LOWER(username) = LOWER(?) LIMIT 1');
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch() ?: null;
    }
    public static function login(string $identifier, string $password): bool {
        $user = self::findByIdentifier($identifier);
        if (!$user || !password_verify($password, $user['password_hash'])) return false;
        session_regenerate_id(true);
        $_SESSION['admin'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'username' => $user['username'] ?? ''];
        $_SESSION['last_activity'] = time();
        $stmt = Database::get()->prepare('UPDATE admin_users SET last_login = ? WHERE id = ?'); $stmt->execute([date('Y-m-d H:i:s'), $user['id']]);
        return true;
    }
}
