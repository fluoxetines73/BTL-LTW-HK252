<?php
require_once 'app/Models/Model.php';

class AboutStatistics extends Model {
    protected string $table = 'about_statistics';

    /**
     * Get all active statistics ordered by sort_order
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
     * Create new statistic item
     * @param array $data
     * @return int|false
     */
    public function createItem(array $data): int|false {
        $sql = "INSERT INTO {$this->table} (number_display, label, sort_order, is_active) 
                VALUES (:number_display, :label, :sort_order, :is_active)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'number_display' => $data['number_display'],
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update statistic item
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateItem(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET
            number_display = :number_display,
            label = :label,
            sort_order = :sort_order,
            is_active = :is_active
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete statistic item
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
