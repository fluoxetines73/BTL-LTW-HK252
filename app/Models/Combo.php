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
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deleteCombo($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function countAdminCombos($keyword = '', $status = 'all') {
        $sql = "SELECT COUNT(*) FROM combos WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE :kw1 OR description LIKE :kw2)";
            $params[':kw1'] = $params[':kw2'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND is_active = :status";
            $params[':status'] = ($status === 'active' ? 1 : 0);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function searchAdminCombos($keyword = '', $status = 'all', $sort = 'newest', $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM combos WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE :kw1 OR description LIKE :kw2)";
            $params[':kw1'] = $params[':kw2'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND is_active = :status";
            $params[':status'] = ($status === 'active' ? 1 : 0);
        }

        switch ($sort) {
            case 'oldest': $sql .= " ORDER BY created_at ASC, id ASC"; break;
            case 'price_asc': $sql .= " ORDER BY price ASC, id ASC"; break;
            case 'price_desc': $sql .= " ORDER BY price DESC, id DESC"; break;
            default: $sql .= " ORDER BY created_at DESC, id DESC"; break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function deleteMultipleCombos(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $db->prepare("DELETE FROM combos WHERE id IN ($placeholders)");
        return $stmt->execute($ids);
    }

    public function isNameExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM combos WHERE name = :name";
        $params = [':name' => $name];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}