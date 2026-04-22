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
        $articles = $this->newsModel ? $this->newsModel->getPublished() : $this->seedNews();
        $this->renderNewsTimelinePage($articles, 'Tin tức mới nhất');
    }

    public function promotions(): void {
        $articles = $this->newsModel ? $this->newsModel->getAdminList('khuyen-mai') : [];
        $this->renderNewsTimelinePage($articles, 'Khuyến mãi và ưu đãi');
    }

    public function monthlyMovies(): void {
        $articles = $this->newsModel ? $this->newsModel->getAdminList('tin-tuc') : [];
        $this->renderNewsTimelinePage($articles, 'Phim hay tháng');
    }

    private function renderNewsTimelinePage(array $articles, string $timelineTitle): void {
        $latest = array_slice($articles, 0, 5);

        $timelineItems = [];
        foreach ($articles as $article) {
            $dateSource = (string)($article['published_at'] ?? $article['created_at'] ?? date('Y-m-d H:i:s'));
            $timestamp = strtotime($dateSource) ?: time();
            $day = (int)date('d', $timestamp);

            $timelineItems[] = [
                'id' => (int)($article['id'] ?? 0),
                'title' => (string)($article['title'] ?? 'Tin tức'),
                'content' => (string)($article['content'] ?? ''),
                'summary' => (string)($article['summary'] ?? ''),
                'image_url' => $this->resolveNewsImageUrl((string)($article['image'] ?? '')),
                'display_date' => date('d/m/Y', $timestamp),
                'day' => $day,
                'is_even_day' => $day % 2 === 0,
                'author_name' => (string)($article['author_name'] ?? 'Admin'),
            ];
        }

        $this->view('layouts/main', [
            'title' => $timelineTitle,
            'content' => 'news/index',
            'articles' => $articles,
            'latestNews' => $latest,
            'timelineItems' => $timelineItems,
            'timelineTitle' => $timelineTitle,
        ]);
    }

    public function detail(int $id = 0): void {
        $article = $this->newsModel ? $this->newsModel->findPublishedById($id) : $this->findSeedById($id);

        if (!$article) {
            http_response_code(404);
            $this->view('layouts/main', [
                'title' => 'Khong tim thay bai viet',
                'content' => 'home/not_found',
            ]);
            return;
        }

        $this->view('layouts/main', [
            'title' => 'Chi tiet tin tuc',
            'content' => 'news/detail',
            'article' => $article,
            'articleImageUrl' => $this->resolveNewsImageUrl((string)($article['image'] ?? '')),
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

    private function resolveNewsImageUrl(string $path): string {
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

        return BASE_URL . 'public/' . ltrim($path, '/');
    }
}
