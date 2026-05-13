<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/app/Models/Comment.php';

class AdminCommentController extends Controller {
    private Comment $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    /**
     * Display all comments management page
     */
    public function index(string $tab = 'all') {
        $this->middlewareAdmin();

        try {
            $newsId = isset($_GET['news_id']) ? (int)$_GET['news_id'] : null;
            
            if ($newsId) {
                // Get comments for specific news article
                if ($tab === 'reported') {
                    $comments = $this->commentModel->getCommentsByNewsId($newsId, 'reported');
                } elseif ($tab === 'pending') {
                    $comments = $this->commentModel->getCommentsByNewsId($newsId, 'pending');
                } else {
                    $comments = $this->commentModel->getCommentsByNewsId($newsId);
                }
            } else {
                // Get all comments
                if ($tab === 'reported') {
                    $comments = $this->commentModel->getReported();
                } elseif ($tab === 'pending') {
                    $comments = $this->commentModel->getPending();
                } else {
                    $comments = $this->commentModel->getAll();
                }
            }

            $stats = [
                'total' => count($this->commentModel->getAll()),
                'reported' => count($this->commentModel->getReported()),
                'pending' => count($this->commentModel->getPending())
            ];

            $this->adminView('admin/comments/index', 'comments', [
                'title' => 'Quản lý bình luận',
                'comments' => $comments,
                'activeTab' => $tab,
                'selectedNewsId' => $newsId,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error loading comments";
        }
    }

    /**
     * Approve a comment
     */
    public function approve(int $id) {
        header('Content-Type: application/json');
        $this->middlewareAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $success = $this->commentModel->approve($id);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Comment approved']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to approve comment']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred']);
        }
    }

    /**
     * Delete a comment
     */
    public function delete(int $id) {
        header('Content-Type: application/json');
        $this->middlewareAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $success = $this->commentModel->deleteById($id);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Bình luận đã được xoá']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to delete comment']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred']);
        }
    }

    /**
     * Delete multiple comments
     */
    public function deleteMultiple() {
        header('Content-Type: application/json');
        $this->middlewareAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ids = $data['ids'] ?? [];

            if (empty($ids) || !is_array($ids)) {
                http_response_code(400);
                echo json_encode(['error' => 'No comments selected']);
                return;
            }

            // Validate all IDs are integers
            $ids = array_map('intval', $ids);
            $success = $this->commentModel->deleteByIds($ids);

            if ($success) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Đã xoá ' . count($ids) . ' bình luận',
                    'count' => count($ids)
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to delete comments']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred']);
        }
    }
}
