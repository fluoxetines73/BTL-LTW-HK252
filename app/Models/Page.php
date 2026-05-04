<?php
require_once ROOT . '/app/Models/Model.php';

class Page extends Model {
    protected string $table = 'pages';

    public function getAllPages() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBySlug(string $slug): array|false {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPageById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPage($data) {
        $sql = "INSERT INTO {$this->table} (title, slug, content, status) 
                VALUES (:title, :slug, :content, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':status' => $data['status'] ?? 'draft'
        ]);
    }

    public function updatePage($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = :title, slug = :slug, content = :content, status = :status 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function deletePage($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
