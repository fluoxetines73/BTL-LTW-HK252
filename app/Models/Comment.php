<?php
require_once ROOT . '/app/Models/Model.php';

class Comment extends Model {
    protected string $table = 'comments';

    /**
     * Get all approved comments for a news article
     */
    public function getByNewsId(int $newsId): array {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name AS username, u.avatar
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            WHERE c.news_id = ? AND c.is_approved = 1
            ORDER BY c.created_at DESC");
        $stmt->execute([$newsId]);
        return $stmt->fetchAll();
    }

    /**
     * Get a single comment by ID with user info (fresh avatar from users table)
     */
    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name AS username, u.avatar
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
            LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new comment (auto-approved) - returns comment ID or false on failure
     */
    public function create(int $newsId, int $userId, string $content) {
        // Auto-approve comments by default (no moderation step)
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (news_id, user_id, content, is_approved, is_admin) VALUES (?, ?, ?, 1, 0)");
        if ($stmt->execute([$newsId, $userId, $content])) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Create admin comment (auto-approved) - returns comment ID or false on failure
     */
    public function createAdminComment(int $newsId, int $userId, string $content) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (news_id, user_id, content, is_approved, is_admin) VALUES (?, ?, ?, 1, 1)");
        if ($stmt->execute([$newsId, $userId, $content])) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Get pending comments (not approved) for admin
     */
    public function getPending(): array {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name AS username, n.title as news_title
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            JOIN news n ON c.news_id = n.id
            WHERE c.is_approved = 0
            ORDER BY c.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get all comments (including reported ones) for admin
     */
    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name AS username, n.title as news_title
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            JOIN news n ON c.news_id = n.id
            ORDER BY c.is_reported DESC, c.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get reported comments for admin
     */
    public function getReported(): array {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name AS username, n.title as news_title
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            JOIN news n ON c.news_id = n.id
            WHERE c.is_reported = 1
            ORDER BY c.report_count DESC, c.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Approve a comment
     */
    public function approve(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_approved = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Report a comment
     */
    public function report(int $id, string $reason): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_reported = 1, report_reason = ?, report_count = report_count + 1 WHERE id = ?");
        return $stmt->execute([$reason, $id]);
    }

    /**
     * Get comment count by news
     */
    public function countByNewsId(int $newsId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE news_id = ? AND is_approved = 1");
        $stmt->execute([$newsId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Check if user already commented on this news
     */
    public function hasUserCommented(int $newsId, int $userId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM {$this->table} WHERE news_id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$newsId, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Check user exists in users table
     */
    public function userExists(int $userId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Check news exists in news table
     */
    public function newsExists(int $newsId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM news WHERE id = ? LIMIT 1");
        $stmt->execute([$newsId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Delete a comment by ID
     */
    public function deleteById(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Delete multiple comments by IDs
     */
    public function deleteByIds(array $ids): bool {
        if (empty($ids)) {
            return false;
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id IN ({$placeholders})");
        return $stmt->execute($ids);
    }

    /**
     * Get all comments for a news article (for admin) - includes unapproved
     */
    public function getCommentsByNewsId(int $newsId, ?string $filter = null): array {
        $sql = "SELECT c.*, u.full_name AS username, n.title as news_title
                FROM {$this->table} c
                JOIN users u ON c.user_id = u.id
                JOIN news n ON c.news_id = n.id
                WHERE c.news_id = ?";
        
        if ($filter === 'reported') {
            $sql .= " AND c.is_reported = 1";
        } elseif ($filter === 'pending') {
            $sql .= " AND c.is_approved = 0";
        }
        
        $sql .= " ORDER BY c.is_reported DESC, c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$newsId]);
        return $stmt->fetchAll();
    }
}
