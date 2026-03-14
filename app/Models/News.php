<?php
require_once APPROOT . '/Models/Model.php';

class News extends Model {
    protected string $table = 'news';

    public function search(string $keyword): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE title LIKE ? ORDER BY id DESC");
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }
}
