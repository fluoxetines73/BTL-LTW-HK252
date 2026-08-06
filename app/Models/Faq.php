<?php
require_once ROOT . '/app/Models/Model.php';

class Faq extends Model {
    protected string $table = 'faqs';

    public function getAllFaqs($sortBy = null, $sortOrder = 'asc') {
        $allowedSortColumns = ['id', 'question', 'category', 'sort_order', 'status'];
        
        if ($sortBy && in_array($sortBy, $allowedSortColumns)) {
            $orderDirection = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
            $sql = "SELECT * FROM {$this->table} ORDER BY {$sortBy} {$orderDirection}, id ASC";
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY category, sort_order, id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchFaqs($keyword = null, $category = null, $status = null, $sortBy = 'id', $sortOrder = 'asc') {
        $allowedSortColumns = ['id', 'question', 'category', 'sort_order', 'status'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }
        $orderDirection = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $conditions = [];
        $params = [];

        if ($keyword !== null && $keyword !== '') {
            $conditions[] = 'question LIKE ?';
            $params[] = '%' . $keyword . '%';
        }
        if ($category !== null && $category !== '') {
            $conditions[] = 'category = ?';
            $params[] = $category;
        }
        if ($status !== null && $status !== '') {
            $conditions[] = 'status = ?';
            $params[] = $status;
        }

        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY {$sortBy} {$orderDirection}, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllGroupedByCategory(): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY category, sort_order, id");
        $stmt->execute();
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grouped = [];
        foreach ($faqs as $faq) {
            $grouped[$faq['category']][] = $faq;
        }
        return $grouped;
    }

    public function getFaqById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAllCategories(): array {
        $stmt = $this->db->prepare("SELECT DISTINCT category FROM {$this->table} ORDER BY category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createFaq($data) {
        $sql = "INSERT INTO {$this->table} (question, answer, category, sort_order, status) 
                VALUES (:question, :answer, :category, :sort_order, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':question' => $data['question'],
            ':answer' => $data['answer'],
            ':category' => $data['category'] ?? 'Chung',
            ':sort_order' => $data['sort_order'] ?? 0,
            ':status' => $data['status'] ?? 'active'
        ]);
    }

    public function updateFaq($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                question = :question, answer = :answer, category = :category, 
                sort_order = :sort_order, status = :status 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':question' => $data['question'],
            ':answer' => $data['answer'],
            ':category' => $data['category'],
            ':sort_order' => $data['sort_order'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function deleteFaq($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteMultiple(array $ids): bool {
        if (empty($ids)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM {$this->table} WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
    }

    public function updateStatusMultiple(array $ids, string $status): bool {
        if (empty($ids)) {
            return false;
        }
        $allowedStatuses = ['active', 'inactive'];
        if (!in_array($status, $allowedStatuses, true)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE {$this->table} SET status = ? WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_merge([$status], $ids));
    }
}
