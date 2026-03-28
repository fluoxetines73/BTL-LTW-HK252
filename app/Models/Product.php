<?php
require_once APPROOT . '/Models/Model.php';

class Product extends Model {
    protected string $table = 'products';

    public function search(string $keyword): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE ? ORDER BY id DESC");
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }
}
