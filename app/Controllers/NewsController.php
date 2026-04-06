<?php
require_once ROOT . '/core/Controller.php';

class NewsController extends Controller {
    private ?News $newsModel = null;

    public function __construct() {
        require_once APPROOT . '/Models/Model.php';
        require_once APPROOT . '/Models/News.php';

        try {
            $this->newsModel = new News();
        } catch (Throwable $e) {
            $this->newsModel = null;
        }
    }

    public function index(): void {
        $keyword = trim((string)($_GET['q'] ?? ''));
        $articles = $this->seedNews();

        if ($this->newsModel) {
            try {
                $articles = $this->newsModel->getPublishedList($keyword);
            } catch (Throwable $e) {
                $articles = $this->seedNews();
                if ($keyword !== '') {
                    $articles = array_values(array_filter($articles, static function (array $item) use ($keyword): bool {
                        $haystack = mb_strtolower(($item['title'] ?? '') . ' ' . ($item['content'] ?? ''), 'UTF-8');
                        return mb_strpos($haystack, mb_strtolower($keyword, 'UTF-8')) !== false;
                    }));
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Tin tuc',
            'content' => 'news/index',
            'articles' => $articles,
            'keyword' => $keyword,
        ]);
    }

    public function detail(int $id = 0): void {
        if ($id <= 0) {
            $this->notFound();
            return;
        }

        $flash = $_SESSION['news_flash'] ?? null;
        unset($_SESSION['news_flash']);

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->middlewareAuth();

            $rating = (int)($_POST['rating'] ?? 0);
            $comment = trim((string)($_POST['comment'] ?? ''));

            if ($rating < 1 || $rating > 5) {
                $_SESSION['news_flash'] = ['type' => 'error', 'message' => 'Đánh giá phải từ 1 đến 5 sao.'];
                $this->redirect('news/detail/' . $id);
            }

            if (mb_strlen($comment, 'UTF-8') < 5) {
                $_SESSION['news_flash'] = ['type' => 'error', 'message' => 'Nội dung bình luận phải có ít nhất 5 ký tự.'];
                $this->redirect('news/detail/' . $id);
            }

            if ($this->newsModel) {
                try {
                    $saved = $this->newsModel->createReview([
                        'news_id' => $id,
                        'user_id' => (int)$_SESSION['auth_user']['id'],
                        'rating' => $rating,
                        'comment' => $comment,
                    ]);

                    $_SESSION['news_flash'] = $saved
                        ? ['type' => 'success', 'message' => 'Đã gửi đánh giá. Quản trị viên sẽ duyệt trước khi hiển thị.']
                        : ['type' => 'error', 'message' => 'Không thể gửi đánh giá.'];
                } catch (Throwable $e) {
                    $_SESSION['news_flash'] = ['type' => 'error', 'message' => 'Không thể gửi đánh giá lúc này.'];
                }
            }

            $this->redirect('news/detail/' . $id);
        }

        $article = $this->findSeedById($id);
        $reviews = [];
        $reviewSummary = ['total_reviews' => 0, 'avg_rating' => 0.0];

        if ($this->newsModel) {
            try {
                $article = $this->newsModel->findPublishedById($id);
                if ($article) {
                    $reviews = $this->newsModel->getApprovedReviewsByNews($id);
                    $reviewSummary = $this->newsModel->getReviewSummaryByNews($id);
                }
            } catch (Throwable $e) {
                $article = $this->findSeedById($id);
                $reviews = [];
                $reviewSummary = ['total_reviews' => 0, 'avg_rating' => 0.0];
            }
        }

        if (!$article) {
            $this->notFound();
            return;
        }

        $this->view('layouts/main', [
            'title' => 'Chi tiet tin tuc',
            'content' => 'news/detail',
            'article' => $article,
            'reviews' => $reviews,
            'reviewSummary' => $reviewSummary,
            'flash' => $flash,
            'authUser' => $_SESSION['auth_user'] ?? null,
        ]);
    }

    public function notFound(): void {
        http_response_code(404);
        $this->view('layouts/main', [
            'title' => 'Khong tim thay bai viet',
            'content' => 'home/not_found',
        ]);
    }

    private function seedNews(): array {
        return [
            ['id' => 1, 'title' => 'Khoi dong du an BTL', 'summary' => 'Thong nhat kien truc va chia module.', 'content' => 'Tuan 1-2 tap trung xay dung nen tang MVC va template chung.'],
            ['id' => 2, 'title' => 'Tieu chuan code cua nhom', 'summary' => 'Thong nhat route convention va naming.', 'content' => 'Moi thanh vien bam sat convention de merge an toan.'],
            ['id' => 3, 'title' => 'Ke hoach sprint tiep theo', 'summary' => 'Implement module theo phan cong #1 #2 #3 #4.', 'content' => 'Sau khi co skeleton, moi module se duoc trien khai doc lap.'],
        ];
    }

    private function findSeedById(int $id): array|false {
        foreach ($this->seedNews() as $item) {
            if ((int)$item['id'] === $id) {
                return $item;
            }
        }
        return false;
    }
}
