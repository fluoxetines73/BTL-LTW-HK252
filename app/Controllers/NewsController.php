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
        $articles = $this->newsModel ? $this->newsModel->getPublishedByCategory('khuyen-mai') : [];
        $this->renderNewsTimelinePage($articles, 'Khuyến mãi và ưu đãi');
    }

    public function monthlyMovies(): void {
        $articles = $this->newsModel ? $this->newsModel->getPublishedByCategory('phim-hay-thang') : [];
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
                'highlight_title' => (string)($article['highlight_title'] ?? ''),
                'content' => (string)($article['content'] ?? ''),
                'detail_content' => (string)($article['detail_content'] ?? ''),
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
            'extraHead' => '<link rel="stylesheet" href="' . BASE_URL . 'public/css/news-timeline.css">',
            'extraScripts' => '<script src="' . BASE_URL . 'public/js/news-timeline.js"></script>',
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

        $detailContent = trim((string)($article['detail_content'] ?? ''));
        if ($detailContent === '') {
            $detailContent = (string)($article['content'] ?? '');
        }

        $this->view('layouts/main', [
            'title' => 'Chi tiết tin tức',
            'content' => 'news/detail',
            'article' => $article,
            'articleImageUrl' => $this->resolveNewsImageUrl((string)($article['image'] ?? '')),
            'articleDetailHtml' => $this->sanitizeDetailHtml($detailContent),
            'extraHead' => '<link rel="stylesheet" href="' . BASE_URL . 'public/css/news-detail.css">',
        ]);
    }

    private function sanitizeDetailHtml(string $html): string {
        $allowedTags = '<h1><h2><h3><h4><p><br><strong><b><em><i><u><ul><ol><li><span><div>';
        $clean = strip_tags($html, $allowedTags);

        // Remove event handlers and risky scriptable URLs.
        $clean = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean) ?? $clean;
        $clean = preg_replace('/(javascript:|data:)/i', '', $clean) ?? $clean;

        // Keep only a small safe subset of inline style declarations (mainly for text color/format).
        $clean = preg_replace_callback('/style\s*=\s*"([^"]*)"/i', function ($matches) {
            $style = $matches[1];
            $allowed = [];
            foreach (explode(';', $style) as $rule) {
                $rule = trim($rule);
                if ($rule === '' || strpos($rule, ':') === false) {
                    continue;
                }
                [$prop, $val] = array_map('trim', explode(':', $rule, 2));
                $propLower = strtolower($prop);
                if (!in_array($propLower, ['color', 'font-weight', 'text-decoration', 'text-align'], true)) {
                    continue;
                }
                if (preg_match('/javascript:|data:/i', $val)) {
                    continue;
                }
                $allowed[] = $propLower . ': ' . $val;
            }

            return empty($allowed) ? '' : 'style="' . implode('; ', $allowed) . '"';
        }, $clean) ?? $clean;

        return $clean;
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
