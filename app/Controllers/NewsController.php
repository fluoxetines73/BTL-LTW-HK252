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
        $articles = $this->newsModel ? $this->newsModel->findAll() : $this->seedNews();

        $this->view('layouts/main', [
            'title' => 'Tin tuc',
            'content' => 'news/index',
            'articles' => $articles,
        ]);
    }

    public function detail(int $id = 0): void {
        $article = $this->newsModel ? $this->newsModel->findById($id) : $this->findSeedById($id);

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
