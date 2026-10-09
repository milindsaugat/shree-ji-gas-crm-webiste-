<?php
final class Setting
{
    private static ?array $cache = null;
    public static function get(string $key, string $default = ''): string {
        if (self::$cache === null) self::$cache = Database::get()->query('SELECT `key`, `value` FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
        return (string)(self::$cache[$key] ?? $default);
    }
    public static function save(string $key, string $value): void {
        $stmt = Database::get()->prepare('SELECT id FROM settings WHERE `key` = ?'); $stmt->execute([$key]); $id = $stmt->fetchColumn();
        if ($id) Database::get()->prepare('UPDATE settings SET `value` = ? WHERE id = ?')->execute([$value, $id]);
        else Database::get()->prepare('INSERT INTO settings (`key`, `value`, `group`) VALUES (?, ?, ?)')->execute([$key, $value, 'site']);
        self::$cache = null;
    }
}
