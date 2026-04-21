<?php
require_once ROOT . '/core/Controller.php';

class NewsController extends Controller {
    private ?News $newsModel = null;
    private ?Movie $movieModel = null;

    public function __construct() {
        require_once APPROOT . '/Models/Model.php';
        require_once APPROOT . '/Models/News.php';
        require_once APPROOT . '/Models/Movie.php';

        try {
            $this->newsModel = new News();
        } catch (Throwable $e) {
            $this->newsModel = null;
        }

        try {
            $this->movieModel = new Movie();
        } catch (Throwable $e) {
            $this->movieModel = null;
        }
    }

    public function index(): void {
        $keyword = trim((string)($_GET['q'] ?? ''));
        $selectedType = trim((string)($_GET['type'] ?? 'all'));
        $timelinePage = max(1, (int)($_GET['timeline_page'] ?? 1));
        $articles = $this->seedNews();
        $nowShowingMovies = [];
        $timelineMovies = [];

        $allowedTypes = ['all', 'now_showing', 'coming_soon', 'promotion'];
        if (!in_array($selectedType, $allowedTypes, true)) {
            $selectedType = 'all';
        }

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

        if ($this->movieModel) {
            try {
                $nowShowingMovies = $this->movieModel->getMoviesByStatus('now_showing');
                $nowShowingMovies = array_map(function (array $movie): array {
                    $movie['poster_url'] = $this->resolveMoviePosterUrl((string)($movie['banner'] ?? $movie['poster'] ?? ''));
                    return $movie;
                }, $nowShowingMovies);
                $allMovies = $this->movieModel->getAllMovies($keyword);
                $timelineMovies = array_values(array_filter($allMovies, static function (array $movie): bool {
                    $status = (string)($movie['status'] ?? '');
                    return in_array($status, ['now_showing', 'coming_soon'], true);
                }));
            } catch (Throwable $e) {
                $nowShowingMovies = [];
                $timelineMovies = [];
            }
        }

        $timelineByDate = $this->buildTimelineByDate($articles, $timelineMovies);
        $timelineByDate = $this->filterTimelineByType($timelineByDate, $selectedType);
        [$timelineByDate, $timelinePagination] = $this->paginateTimelineByFourMonths($timelineByDate, $timelinePage);

        $typeOptions = [
            'all' => 'Tất cả',
            'now_showing' => 'Phim đang chiếu',
            'coming_soon' => 'Phim sắp chiếu',
            'promotion' => 'Ưu đãi',
        ];

        $this->view('layouts/main', [
            'title' => 'Tin tuc',
            'content' => 'news/index',
            'articles' => $articles,
            'keyword' => $keyword,
            'nowShowingMovies' => $nowShowingMovies,
            'timelineByDate' => $timelineByDate,
            'timelinePagination' => $timelinePagination,
            'selectedType' => $selectedType,
            'typeOptions' => $typeOptions,
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

    public function promotions(): void {
        $this->view('layouts/main', [
            'title' => 'Khuyen mai va uu dai',
            'content' => 'news/promotions',
        ]);
    }

    private function seedNews(): array {
        return [
            ['id' => 1, 'title' => 'Cap nhat phim dang chieu tuan nay', 'summary' => 'Danh sach phim hot dang co lich.', 'content' => 'Nhieu tua phim moi dang chieu tai he thong rap.', 'category' => 'phim-dang-chieu', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
            ['id' => 2, 'title' => 'Lich phim sap chieu thang nay', 'summary' => 'Thong tin suat chieu som.', 'content' => 'Cac phim sap chieu da mo dat ve som tren he thong.', 'category' => 'phim-sap-chieu', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 day'))],
            ['id' => 3, 'title' => 'Uu dai mua he tai rap', 'summary' => 'Combo va khuyen mai hap dan.', 'content' => 'Khach hang co the nhan nhieu uu dai khi dat ve online.', 'category' => 'uu-dai', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 day'))],
        ];
    }

    private function buildTimelineByDate(array $articles, array $timelineMovies = []): array {
        $timeline = [];

        foreach ($articles as $article) {
            $dateSource = (string)($article['published_at'] ?? $article['created_at'] ?? date('Y-m-d H:i:s'));
            $timestamp = strtotime($dateSource) ?: time();
            $dateKey = date('Y-m-d', $timestamp);

            if (!isset($timeline[$dateKey])) {
                $timeline[$dateKey] = [];
            }

            $article['news_type_key'] = $this->detectNewsTypeKey($article);
            $article['news_type'] = $this->mapNewsTypeLabel($article['news_type_key']);
            $article['display_datetime'] = date('d/m/Y H:i', $timestamp);
            $article['timeline_timestamp'] = $timestamp;
            $article['timeline_image'] = $this->normalizePublicPath((string)($article['image'] ?? ''));
            $timeline[$dateKey][] = $article;
        }

        foreach ($timelineMovies as $movie) {
            $releaseDate = (string)($movie['release_date'] ?? '');
            if ($releaseDate === '') {
                continue;
            }

            $timestamp = strtotime($releaseDate . ' 09:00:00');
            if ($timestamp === false) {
                continue;
            }

            $dateKey = date('Y-m-d', $timestamp);
            if (!isset($timeline[$dateKey])) {
                $timeline[$dateKey] = [];
            }

            $movieStatus = (string)($movie['status'] ?? 'coming_soon');
            $newsTypeKey = $movieStatus === 'now_showing' ? 'now_showing' : 'coming_soon';
            $timeline[$dateKey][] = [
                'id' => (int)($movie['id'] ?? 0),
                'title' => (string)($movie['title'] ?? 'Phim điện ảnh'),
                'content' => (string)($movie['description'] ?? ''),
                'summary' => 'Khởi chiếu: ' . date('d/m/Y', $timestamp),
                'news_type_key' => $newsTypeKey,
                'news_type' => $this->mapNewsTypeLabel($newsTypeKey),
                'display_datetime' => date('d/m/Y', $timestamp),
                'timeline_timestamp' => $timestamp,
                'is_movie_item' => true,
                'movie_id' => (int)($movie['id'] ?? 0),
                'timeline_link' => BASE_URL . 'product/detail/' . (int)($movie['id'] ?? 0),
                'timeline_image' => $this->resolveMoviePosterUrl((string)($movie['banner'] ?? $movie['poster'] ?? '')),
            ];
        }

        foreach ($timeline as &$items) {
            usort($items, static function (array $a, array $b): int {
                $ta = (int)($a['timeline_timestamp'] ?? 0);
                $tb = (int)($b['timeline_timestamp'] ?? 0);
                return $tb <=> $ta;
            });
        }
        unset($items);

        krsort($timeline);
        return $timeline;
    }

    private function paginateTimelineByFourMonths(array $timelineByDate, int $page): array {
        if (empty($timelineByDate)) {
            return [
                [],
                [
                    'current_page' => 1,
                    'total_pages' => 1,
                    'from_month' => '',
                    'to_month' => '',
                ],
            ];
        }

        $dateKeys = array_keys($timelineByDate);
        rsort($dateKeys);

        $latestDate = (string)($dateKeys[0] ?? date('Y-m-d'));
        $oldestDate = (string)($dateKeys[count($dateKeys) - 1] ?? $latestDate);

        $latestMonth = new DateTime(date('Y-m-01', strtotime($latestDate) ?: time()));
        $oldestMonth = new DateTime(date('Y-m-01', strtotime($oldestDate) ?: time()));

        $monthSpan = ((int)$latestMonth->format('Y') - (int)$oldestMonth->format('Y')) * 12
            + ((int)$latestMonth->format('n') - (int)$oldestMonth->format('n')) + 1;
        $totalPages = max(1, (int)ceil($monthSpan / 4));
        $currentPage = min(max(1, $page), $totalPages);

        $windowEndMonth = clone $latestMonth;
        $windowEndMonth->modify('-' . (($currentPage - 1) * 4) . ' months');

        $windowStartMonth = clone $windowEndMonth;
        $windowStartMonth->modify('-3 months');

        $windowStartTs = strtotime($windowStartMonth->format('Y-m-01 00:00:00')) ?: 0;
        $windowEndTs = strtotime($windowEndMonth->format('Y-m-t 23:59:59')) ?: PHP_INT_MAX;

        $pagedTimeline = [];
        foreach ($timelineByDate as $dateKey => $items) {
            $dateTs = strtotime((string)$dateKey . ' 12:00:00') ?: 0;
            if ($dateTs >= $windowStartTs && $dateTs <= $windowEndTs) {
                $pagedTimeline[$dateKey] = $items;
            }
        }

        $fromMonth = $windowStartMonth->format('m/Y');
        $toMonth = $windowEndMonth->format('m/Y');

        return [
            $pagedTimeline,
            [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'from_month' => $fromMonth,
                'to_month' => $toMonth,
            ],
        ];
    }

    private function filterTimelineByType(array $timelineByDate, string $selectedType): array {
        if ($selectedType === 'all') {
            return $timelineByDate;
        }

        $filtered = [];
        foreach ($timelineByDate as $dateKey => $items) {
            $dayItems = array_values(array_filter($items, static function (array $article) use ($selectedType): bool {
                return (string)($article['news_type_key'] ?? '') === $selectedType;
            }));

            if (!empty($dayItems)) {
                $filtered[$dateKey] = $dayItems;
            }
        }

        return $filtered;
    }

    private function mapNewsTypeLabel(string $typeKey): string {
        return match ($typeKey) {
            'now_showing' => 'Phim đang chiếu',
            'coming_soon' => 'Phim sắp chiếu',
            'promotion' => 'Ưu đãi',
            default => 'Tin điện ảnh',
        };
    }

    private function detectNewsTypeKey(array $article): string {
        $category = mb_strtolower((string)($article['category'] ?? ''), 'UTF-8');
        $text = mb_strtolower(
            trim((string)($article['title'] ?? '') . ' ' . (string)($article['summary'] ?? '') . ' ' . (string)($article['content'] ?? '')),
            'UTF-8'
        );

        if (
            str_contains($category, 'dang-chieu') || str_contains($category, 'now_showing') ||
            str_contains($text, 'dang chieu') || str_contains($text, 'đang chiếu')
        ) {
            return 'now_showing';
        }

        if (
            str_contains($category, 'sap-chieu') || str_contains($category, 'coming_soon') ||
            str_contains($text, 'sap chieu') || str_contains($text, 'sắp chiếu')
        ) {
            return 'coming_soon';
        }

        if (
            str_contains($category, 'uu-dai') || str_contains($category, 'khuyen-mai') ||
            str_contains($text, 'uu dai') || str_contains($text, 'ưu đãi') ||
            str_contains($text, 'khuyen mai') || str_contains($text, 'khuyến mãi')
        ) {
            return 'promotion';
        }

        return 'other';
    }

    private function findSeedById(int $id): array|false {
        foreach ($this->seedNews() as $item) {
            if ((int)$item['id'] === $id) {
                return $item;
            }
        }
        return false;
    }

    private function resolveMoviePosterUrl(string $path): string {
        $path = trim($path);
        if ($path === '') {
            return BASE_URL . 'public/images/about/about-6.png';
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        if (str_starts_with($path, 'public/')) {
            return BASE_URL . $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return BASE_URL . 'public/' . ltrim($path, '/');
        }

        return BASE_URL . 'public/uploads/movies/' . ltrim($path, '/');
    }

    private function normalizePublicPath(string $path): string {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        if (str_starts_with($path, 'public/')) {
            return BASE_URL . $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return BASE_URL . 'public/' . ltrim($path, '/');
        }

        return BASE_URL . 'public/' . ltrim($path, '/');
    }
}
