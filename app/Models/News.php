<?php
require_once APPROOT . '/Models/Model.php';

class News extends Model {
    protected string $table = 'news';
    private array $columnExistsCache = [];

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

    public function getPublishedByCategory(string $category): array {
        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.status = 'published' AND n.category = ?
                ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
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
        $columns = ['title', 'slug', 'content', 'image', 'category', 'author_id', 'status'];
        $values = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['category'],
            $data['author_id'],
            $data['status'] ?? 'published',
        ];

        if ($this->hasColumn('featured')) {
            $columns[] = 'featured';
            $values[] = !empty($data['featured']) ? 1 : 0;
        }

        if ($this->hasColumn('highlight_title')) {
            $columns[] = 'highlight_title';
            $values[] = (string)($data['highlight_title'] ?? $data['title']);
        }

        if ($this->hasColumn('detail_content')) {
            $columns[] = 'detail_content';
            $values[] = (string)($data['detail_content'] ?? $data['content']);
        }

        $columns[] = 'published_at';
        $values[] = $data['published_at'] ?? date('Y-m-d H:i:s');

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . $placeholders . ")";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function deleteNews(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteMultipleNews(array $ids): bool {
        if (empty($ids)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM {$this->table} WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
    }

    public function updateNews(int $id, array $data): bool {
        $setParts = [
            'title = ?',
            'slug = ?',
            'content = ?',
            'image = ?',
            'category = ?',
        ];
        $values = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['category'],
        ];

        if ($this->hasColumn('featured')) {
            $setParts[] = 'featured = ?';
            $values[] = !empty($data['featured']) ? 1 : 0;
        }

        if ($this->hasColumn('highlight_title')) {
            $setParts[] = 'highlight_title = ?';
            $values[] = (string)($data['highlight_title'] ?? $data['title']);
        }

        if ($this->hasColumn('detail_content')) {
            $setParts[] = 'detail_content = ?';
            $values[] = (string)($data['detail_content'] ?? $data['content']);
        }

        $setParts[] = 'updated_at = CURRENT_TIMESTAMP';
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE id = ?";
        $values[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function searchAdminNews(?string $category, ?string $keyword, string $sort = 'newest'): array {
        $baseSql = "SELECT n.*,
                           u.full_name AS author_name
                    FROM {$this->table} n
                    LEFT JOIN users u ON u.id = n.author_id
                    WHERE 1=1";
        $params = [];

        if ($category !== null && $category !== '') {
            $baseSql .= " AND n.category = ?";
            $params[] = $category;
        }

        if ($keyword !== null && $keyword !== '') {
            $baseSql .= " AND (n.title LIKE ? OR n.content LIKE ?)";
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
        }

        // Sort by published_at (or created_at fallback) for consistency with displayed date
        if ($sort === 'oldest') {
            $baseSql .= " ORDER BY COALESCE(n.published_at, n.created_at) ASC, n.id ASC";
        } else {
            $baseSql .= " ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC";
        }

        $stmt = $this->db->prepare($baseSql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFeaturedNews(int $limit = 5): array {
        if (!$this->hasColumn('featured')) {
            return $this->getLatestPublished($limit);
        }

        $sql = "SELECT n.*,
                       u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.status = 'published' AND n.featured = 1
                ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function setFeatured(int $id, bool $featured): bool {
        if (!$this->hasColumn('featured')) {
            return true;
        }

        $sql = "UPDATE {$this->table} SET featured = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$featured ? 1 : 0, $id]);
    }

    private function hasColumn(string $columnName): bool {
        if (array_key_exists($columnName, $this->columnExistsCache)) {
            return $this->columnExistsCache[$columnName];
        }

        $sql = "SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = ?
                  AND COLUMN_NAME = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->table, $columnName]);

        $this->columnExistsCache[$columnName] = ((int)$stmt->fetchColumn()) > 0;
        return $this->columnExistsCache[$columnName];
    }
}
