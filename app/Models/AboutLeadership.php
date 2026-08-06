<?php
require_once ROOT . '/app/Models/Model.php';

class AboutLeadership extends Model {
    protected string $table = 'about_leadership';

    public function getAllItems(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllItemsAdmin(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY sort_order ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function createItem(array $data): int|false {
        $sql = "INSERT INTO {$this->table} 
                (name, role, avatar_type, avatar_value, avatar_color_class, status, sort_order, is_active) 
                VALUES 
                (:name, :role, :avatar_type, :avatar_value, :avatar_color_class, :status, :sort_order, :is_active)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'role' => $data['role'],
            'avatar_type' => $data['avatar_type'] ?? 'icon',
            'avatar_value' => $data['avatar_value'] ?? 'fa-solid fa-user-tie',
            'avatar_color_class' => $data['avatar_color_class'] ?? 'team-avatar-1',
            'status' => $data['status'] ?? 'active',
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    public function updateItem(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET
            name = :name,
            role = :role,
            avatar_type = :avatar_type,
            avatar_value = :avatar_value,
            avatar_color_class = :avatar_color_class,
            status = :status,
            sort_order = :sort_order,
            is_active = :is_active
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deleteItem(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleActive(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET is_active = NOT is_active 
            WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateAvatarImage(int $id, string $imagePath): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET avatar_value = :avatar_value, avatar_type = 'image' 
            WHERE id = :id");
        return $stmt->execute(['avatar_value' => $imagePath, 'id' => $id]);
    }
}
