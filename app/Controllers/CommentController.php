<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/Comment.php';

class CommentController extends Controller {
    private Comment $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    private function getCurrentUserId(): int {
        if (isset($_SESSION['auth_user']['id'])) {
            return (int)$_SESSION['auth_user']['id'];
        }
        if (isset($_SESSION['user_id'])) {
            return (int)$_SESSION['user_id'];
        }
        // If admin flag exists but no auth_user, try to map to an admin user record
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            try {
                $db = Database::getInstance()->getPdo();
                $stmt = $db->prepare("SELECT id, full_name, email, avatar, role FROM users WHERE role = 'admin' LIMIT 1");
                $stmt->execute();
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($admin) {
                    $_SESSION['auth_user'] = [
                        'id' => (int)$admin['id'],
                        'name' => $admin['full_name'] ?? '',
                        'email' => $admin['email'] ?? '',
                        'avatar' => $admin['avatar'] ?? null,
                        'role' => $admin['role'] ?? 'admin'
                    ];
                    return (int)$admin['id'];
                }
            } catch (Throwable $e) {
                // ignore and fallthrough
            }
        }
        return 0;
    }

    private function isCurrentUserAdmin(): bool {
        if (!empty($_SESSION['auth_user']['role']) && $_SESSION['auth_user']['role'] === 'admin') {
            return true;
        }
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            // ensure auth_user maps to a real admin user when possible
            $this->getCurrentUserId();
            return true;
        }
        return false;
    }

    /**
     * Get comments for a news article (AJAX endpoint)
     */
    public function getComments(int $newsId) {
        header('Content-Type: application/json');

        if (!is_numeric($newsId) || $newsId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid news ID']);
            return;
        }

        try {
            $comments = $this->commentModel->getByNewsId($newsId);
            // Normalize avatar URL and other fields for frontend
            $mapped = array_map(function($c) {
                $avatar = $c['avatar'] ?? null;
                $default = rtrim(BASE_URL, '/') . '/public/uploads/avatars/default-avatar.svg';
                if (empty($avatar)) {
                    $avatarUrl = $default;
                } else {
                    // If avatar already contains a full URL, use it
                    if (preg_match('#^https?://#i', $avatar)) {
                        $avatarUrl = $avatar;
                    } else {
                        // Normalize various stored formats
                        $clean = ltrim($avatar, '/');
                        
                        // Case 1: Already has full path including public (public/uploads/avatars/file.jpg)
                        if (strpos($clean, 'public/uploads/avatars') !== false) {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/' . $clean;
                        }
                        // Case 2: Has uploads/avatars but missing public (uploads/avatars/file.jpg)
                        elseif (strpos($clean, 'uploads/avatars') !== false) {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/public/' . $clean;
                        }
                        // Case 3: Just filename (file.jpg) - add full path
                        else {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/public/uploads/avatars/' . $clean;
                        }
                    }
                }
                $c['avatar'] = $avatarUrl;
                $c['username'] = $c['username'] ?? ($c['full_name'] ?? 'Người dùng');
                return $c;
            }, $comments);

            echo json_encode([
                'success' => true,
                'count' => count($mapped),
                'comments' => $mapped
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch comments']);
        }
    }

    /**
     * Create new comment (AJAX endpoint)
     */
    public function add() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Check if user is logged in
        $userId = $this->getCurrentUserId();
        if ($userId <= 0) {
            http_response_code(401);
            echo json_encode(['error' => 'User not authenticated']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $newsId = (int)($input['news_id'] ?? 0);
        $content = trim($input['content'] ?? '');

        if ($newsId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid news ID']);
            return;
        }

        // Ensure referenced news and user exist to avoid FK violations
        if (!$this->commentModel->newsExists($newsId)) {
            http_response_code(400);
            echo json_encode(['error' => 'News item not found']);
            return;
        }

        if (!$this->commentModel->userExists($userId)) {
            error_log('CommentController::add failed - invalid user id: ' . $userId);
            http_response_code(400);
            echo json_encode(['error' => 'User not found or session invalid']);
            return;
        }

        if (strlen($content) < 3 || strlen($content) > 1000) {
            http_response_code(400);
            echo json_encode(['error' => 'Comment must be between 3 and 1000 characters']);
            return;
        }

        try {
            // Check if user is admin - admin comments are auto-approved
            $isAdmin = $this->isCurrentUserAdmin();
            
            if ($isAdmin) {
                $commentId = $this->commentModel->createAdminComment($newsId, $userId, $content);
                $message = 'Admin comment posted successfully';
            } else {
                $commentId = $this->commentModel->create($newsId, $userId, $content);
                $message = 'Comment submitted and pending approval';
            }
            
            if ($commentId) {
                // Fetch the created comment data for immediate frontend display
                $comment = $this->commentModel->getById($commentId);
                
                // Normalize avatar URL
                $avatar = $comment['avatar'] ?? null;
                $default = rtrim(BASE_URL, '/') . '/public/uploads/avatars/default-avatar.svg';
                if (empty($avatar)) {
                    $avatarUrl = $default;
                } else {
                    if (preg_match('#^https?://#i', $avatar)) {
                        $avatarUrl = $avatar;
                    } else {
                        $clean = ltrim($avatar, '/');
                        
                        // Case 1: Already has full path including public (public/uploads/avatars/file.jpg)
                        if (strpos($clean, 'public/uploads/avatars') !== false) {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/' . $clean;
                        }
                        // Case 2: Has uploads/avatars but missing public (uploads/avatars/file.jpg)
                        elseif (strpos($clean, 'uploads/avatars') !== false) {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/public/' . $clean;
                        }
                        // Case 3: Just filename (file.jpg) - add full path
                        else {
                            $avatarUrl = rtrim(BASE_URL, '/') . '/public/uploads/avatars/' . $clean;
                        }
                    }
                }
                
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'isAdmin' => $isAdmin,
                    'comment' => [
                        'id' => (int)$comment['id'],
                        'content' => $comment['content'],
                        'username' => $comment['username'] ?? 'Người dùng',
                        'avatar' => $avatarUrl,
                        'created_at' => $comment['created_at']
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create comment']);
            }
        } catch (Throwable $e) {
            error_log('CommentController::add failed - ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Không thể gửi bình luận lúc này. Vui lòng thử lại.']);
        }
    }

    /**
     * Report a comment (AJAX endpoint)
     */
    public function report() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $commentId = (int)($input['comment_id'] ?? 0);
        $reason = trim($input['reason'] ?? '');

        if ($commentId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid comment ID']);
            return;
        }

        if (strlen($reason) < 5 || strlen($reason) > 255) {
            http_response_code(400);
            echo json_encode(['error' => 'Report reason must be between 5 and 255 characters']);
            return;
        }

        try {
            $success = $this->commentModel->report($commentId, $reason);
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Comment reported successfully'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to report comment']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred']);
        }
    }
}
