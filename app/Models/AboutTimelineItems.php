<?php
require_once 'app/Models/Model.php';

class AboutTimelineItems extends Model {
    protected string $table = 'about_timeline_items';

    /**
     * Get all active timeline items ordered by sort_order
     * @return array
     */
    public function getAllItems(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get all items including inactive (for admin)
     * @return array
     */
    public function getAllItemsAdmin(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY sort_order ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get single item by ID
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Create new timeline item
     * @param array $data
     * @return int|false
     */
    public function createItem(array $data): int|false {
        $sql = "INSERT INTO {$this->table} (year_label, content, sort_order, is_active) 
                VALUES (:year_label, :content, :sort_order, :is_active)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'year_label' => $data['year_label'],
            'content' => $data['content'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update timeline item
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateItem(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET
            year_label = :year_label,
            content = :content,
            sort_order = :sort_order,
            is_active = :is_active
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete timeline item
     * @param int $id
     * @return bool
     */
    public function deleteItem(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Toggle active status
     * @param int $id
     * @return bool
     */
    public function toggleActive(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET is_active = NOT is_active 
            WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
