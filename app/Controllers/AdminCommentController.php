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

            $allCount = count($this->commentModel->getAll());
            $reportedCount = count($this->commentModel->getReported());
            $pendingCount = count($this->commentModel->getPending());

            // comment-specific stats (for the comments view)
            $commentStats = [
                'total' => $allCount,
                'reported' => $reportedCount,
                'pending' => $pendingCount,
            ];

            // layout/global stats (used by admin layout header)
            $userModel = $this->model('User');
            $movieModel = $this->model('Movie');
            $showtimeModel = $this->model('Showtime');
            $comboModel = $this->model('Combo');
            $newsModel = $this->model('News');

            $layoutStats = [
                'users' => $userModel->count(),
                'movies' => $movieModel->count(),
                'showtimes' => $showtimeModel->count(),
                'combos' => $comboModel->count(),
                'news' => $newsModel->count(),
            ];

            // Pagination
            $perPage = 15;
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

            $commentsFull = $comments; // full result set for the chosen tab/filter
            $totalComments = count($commentsFull);
            $totalPages = max(1, (int)ceil($totalComments / $perPage));
            if ($page > $totalPages) {
                $page = $totalPages;
            }
            $offset = ($page - 1) * $perPage;
            $commentsPaged = array_slice($commentsFull, $offset, $perPage);

            $this->adminView('admin/comments/index', 'comments', [
                'title' => 'Quản lý bình luận',
                'comments' => $commentsPaged,
                'activeTab' => $tab,
                'selectedNewsId' => $newsId,
                // layout stats for the header cards
                'stats' => $layoutStats,
                // comment-specific stats for badges and counts inside the page
                'commentStats' => $commentStats,
                'page' => $page,
                'totalPages' => $totalPages,
                'perPage' => $perPage,
                'totalComments' => $totalComments
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
