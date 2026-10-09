<?php
class ContentModel
{
    public const TYPES = ['products','categories','blog_posts','blog_categories','case_studies','use_cases','testimonials','client_logos','pages','seo_meta','media','enquiries'];
    public function __construct(public string $table) { if (!in_array($table, self::TYPES, true)) throw new InvalidArgumentException('Unknown content type'); }
    public function all(bool $published = false): array {
        $sql = 'SELECT * FROM ' . $this->table;
        if ($published && in_array($this->table, ['products','blog_posts','case_studies','use_cases','pages'])) $sql .= " WHERE status = 'published'";
        $sql .= in_array($this->table, ['categories','blog_categories','products','testimonials','client_logos']) ? ' ORDER BY sort_order, id DESC' : ' ORDER BY id DESC';
        return Database::get()->query($sql)->fetchAll();
    }
    public function find(int $id): ?array { $stmt = Database::get()->prepare('SELECT * FROM ' . $this->table . ' WHERE id = ?'); $stmt->execute([$id]); return $stmt->fetch() ?: null; }
    public function bySlug(string $slug): ?array { $stmt = Database::get()->prepare('SELECT * FROM ' . $this->table . " WHERE slug = ?" . (in_array($this->table, ['categories','blog_categories']) ? '' : " AND status = 'published'")); $stmt->execute([$slug]); return $stmt->fetch() ?: null; }
    public function save(array $data, ?int $id = null): int {
        foreach (array_keys($data) as $key) if (!preg_match('/^[a-z_]+$/', $key)) throw new InvalidArgumentException('Invalid field');
        $columns = array_keys($data);
        if ($id) { $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', array_map(fn($key) => '`' . $key . '` = ?', $columns)) . ' WHERE id = ?'; $values = [...array_values($data), $id]; }
        else { $sql = 'INSERT INTO ' . $this->table . ' (`' . implode('`,`', $columns) . '`) VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')'; $values = array_values($data); }
        Database::get()->prepare($sql)->execute($values);
        return $id ?: (int)Database::get()->lastInsertId();
    }
    public function delete(int $id): void { Database::get()->prepare('DELETE FROM ' . $this->table . ' WHERE id = ?')->execute([$id]); }
}
