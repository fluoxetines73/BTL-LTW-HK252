<?php
require_once ROOT . '/app/Models/Model.php';

class Combo extends Model {
    protected string $table = 'combos';

    public function getAllCombos() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY price ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComboById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCombo($data) {
        // Đã sửa lại thành cột `image` cho khớp với CSDL
        $sql = "INSERT INTO {$this->table} (name, description, price, image, is_active) 
                VALUES (:name, :description, :price, :image, :is_active)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':is_active' => $data['is_active'] ?? 1
        ]);
    }

    public function updateCombo($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                name = :name, description = :description, 
                price = :price, image = :image, is_active = :is_active 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id; // Gắn thêm ID vào mảng data để bind
        return $stmt->execute($data);
    }

    public function deleteCombo($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}