<?php
require_once APPROOT . '/Models/Model.php';

class News extends Model {
    protected string $table = 'news';

    public function search(string $keyword): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE title LIKE ? ORDER BY id DESC");
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    public function getPublished(): array {
        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.status = 'published'
                ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getLatestPublished(int $limit = 5): array {
        $limit = max(1, $limit);
        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.status = 'published'
                ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findPublishedById(int $id): array|false {
        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.id = ? AND n.status = 'published'
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAdminList(?string $category = null): array {
        $baseSql = "SELECT n.*,
                           u.full_name AS author_name
                    FROM {$this->table} n
                    LEFT JOIN users u ON u.id = n.author_id";

        if ($category !== null && $category !== '') {
            $sql = $baseSql . " WHERE n.category = ? ORDER BY n.created_at DESC, n.id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category]);
            return $stmt->fetchAll();
        }

        $sql = $baseSql . " ORDER BY n.created_at DESC, n.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findByIdWithAuthor(int $id): array|false {
        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.id = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createNews(array $data): bool {
        $sql = "INSERT INTO {$this->table}
                (title, slug, content, image, category, author_id, status, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['category'],
            $data['author_id'],
            $data['status'] ?? 'published',
            $data['published_at'] ?? date('Y-m-d H:i:s'),
        ]);
    }

    public function deleteNews(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateNews(int $id, array $data): bool {
        $sql = "UPDATE {$this->table}
                SET title = ?, slug = ?, content = ?, image = ?, category = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['category'],
            $id,
        ]);
    }
}
