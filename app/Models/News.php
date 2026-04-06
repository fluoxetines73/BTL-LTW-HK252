<?php
require_once APPROOT . '/Models/Model.php';

class News extends Model {
    protected string $table = 'news';

    public function search(string $keyword): array {
        return $this->getPublishedList($keyword);
    }

    public function getPublishedList(string $keyword = ''): array {
        $sql = "SELECT n.*, u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE n.status = 'published'";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (n.title LIKE ? OR n.content LIKE ? OR n.slug LIKE ?)";
            $search = '%' . $keyword . '%';
            $params = [$search, $search, $search];
        }

        $sql .= " ORDER BY COALESCE(n.published_at, n.created_at) DESC, n.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findPublishedById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT n.*, u.full_name AS author_name
             FROM {$this->table} n
             LEFT JOIN users u ON u.id = n.author_id
             WHERE n.id = ? AND n.status = 'published'
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAdminList(string $keyword = ''): array {
        $sql = "SELECT n.*, u.full_name AS author_name
                FROM {$this->table} n
                LEFT JOIN users u ON u.id = n.author_id
                WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (n.title LIKE ? OR n.content LIKE ? OR n.slug LIKE ?)";
            $search = '%' . $keyword . '%';
            $params = [$search, $search, $search];
        }

        $sql .= " ORDER BY n.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findByIdForAdmin(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createNews(array $data): bool {
        $slug = $this->generateUniqueSlug((string)$data['title']);
        $status = in_array((string)$data['status'], ['draft', 'published'], true) ? (string)$data['status'] : 'draft';
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (title, slug, content, image, category, author_id, status, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            trim((string)$data['title']),
            $slug,
            trim((string)$data['content']),
            $data['image'] ?? null,
            (string)$data['category'],
            (int)$data['author_id'],
            $status,
            $publishedAt,
        ]);
    }

    public function updateNews(int $id, array $data): bool {
        $current = $this->findByIdForAdmin($id);
        if (!$current) {
            return false;
        }

        $title = trim((string)$data['title']);
        $status = in_array((string)$data['status'], ['draft', 'published'], true) ? (string)$data['status'] : 'draft';
        $slug = ($title !== ($current['title'] ?? ''))
            ? $this->generateUniqueSlug($title, $id)
            : (string)$current['slug'];

        $publishedAt = $current['published_at'] ?? null;
        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }
        if ($status === 'draft') {
            $publishedAt = null;
        }

        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
             SET title = ?, slug = ?, content = ?, image = COALESCE(?, image), category = ?, status = ?, published_at = ?, updated_at = CURRENT_TIMESTAMP
             WHERE id = ?"
        );

        return $stmt->execute([
            $title,
            $slug,
            trim((string)$data['content']),
            $data['image'] ?? null,
            (string)$data['category'],
            $status,
            $publishedAt,
            $id,
        ]);
    }

    public function deleteNews(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function createReview(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO news_reviews (news_id, user_id, rating, comment, status)
             VALUES (?, ?, ?, ?, 'pending')"
        );

        return $stmt->execute([
            (int)$data['news_id'],
            (int)$data['user_id'],
            (int)$data['rating'],
            trim((string)$data['comment']),
        ]);
    }

    public function getApprovedReviewsByNews(int $newsId): array {
        $stmt = $this->db->prepare(
            "SELECT nr.*, u.full_name
             FROM news_reviews nr
             JOIN users u ON u.id = nr.user_id
             WHERE nr.news_id = ? AND nr.status = 'approved'
             ORDER BY nr.created_at DESC"
        );
        $stmt->execute([$newsId]);
        return $stmt->fetchAll();
    }

    public function getReviewSummaryByNews(int $newsId): array {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total_reviews, AVG(rating) AS avg_rating
             FROM news_reviews
             WHERE news_id = ? AND status = 'approved'"
        );
        $stmt->execute([$newsId]);
        $row = $stmt->fetch() ?: ['total_reviews' => 0, 'avg_rating' => 0];

        return [
            'total_reviews' => (int)($row['total_reviews'] ?? 0),
            'avg_rating' => round((float)($row['avg_rating'] ?? 0), 1),
        ];
    }

    public function getAdminReviews(string $keyword = '', string $status = ''): array {
        $sql = "SELECT nr.*, n.title AS news_title, u.full_name
                FROM news_reviews nr
                JOIN news n ON n.id = nr.news_id
                JOIN users u ON u.id = nr.user_id
                WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (n.title LIKE ? OR u.full_name LIKE ? OR nr.comment LIKE ?)";
            $search = '%' . $keyword . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if ($status !== '' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $sql .= " AND nr.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY nr.created_at DESC, nr.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateReviewStatus(int $id, string $status): bool {
        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE news_reviews SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function deleteReview(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM news_reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function generateUniqueSlug(string $title, int $excludeId = 0): string {
        $baseSlug = $this->slugify($title);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, int $excludeId = 0): bool {
        if ($excludeId > 0) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE slug = ? AND id <> ?");
            $stmt->execute([$slug, $excludeId]);
            return (int)$stmt->fetchColumn() > 0;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function slugify(string $value): string {
        $value = trim(mb_strtolower($value, 'UTF-8'));
        $value = str_replace(
            ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'ì', 'í', 'ị', 'ỉ', 'ĩ', 'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'đ'],
            ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'd'],
            $value
        );

        $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value !== '' ? $value : ('news-' . time());
    }
}
