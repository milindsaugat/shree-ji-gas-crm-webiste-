<?php
final class Section
{
    public static function all(bool $active = false): array {
        $rows = Database::get()->query('SELECT * FROM sections' . ($active ? ' WHERE is_active = 1' : '') . ' ORDER BY sort_order, id')->fetchAll();
        $defaults = require ROOT.'/config/section-content.php';
        foreach ($rows as &$row) {
            $extra = $defaults[$row['section_key']] ?? [];
            $row['fields'] = array_replace($extra['fields'] ?? [], json_decode($row['data'], true) ?: []);
            foreach ($row['fields']['items'] ?? [] as $index => $item) {
                $row['fields']['items'][$index] = array_replace($extra['items'][$item['title'] ?? ''] ?? [], $item);
            }
        }
        unset($row);
        return $rows;
    }
    public static function find(string $key): ?array { foreach (self::all() as $row) if ($row['section_key'] === $key) return $row; return null; }
    public static function save(string $key, string $title, array $data, bool $active, int $order): void { Database::get()->prepare('UPDATE sections SET title = ?, data = ?, is_active = ?, sort_order = ? WHERE section_key = ?')->execute([$title, json_encode($data, JSON_UNESCAPED_UNICODE), (int)$active, $order, $key]); }
}
