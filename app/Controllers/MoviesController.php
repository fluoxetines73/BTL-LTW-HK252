<?php
require_once ROOT . '/core/Controller.php';

class MoviesController extends Controller {
    private array $genres = [
        ['slug' => 'hanh-dong', 'name' => 'Hành Động'],
        ['slug' => 'hai', 'name' => 'Hài'],
        ['slug' => 'khoa-hoc-vien-tuong', 'name' => 'Khoa Học Viễn Tưởng'],
        ['slug' => 'tam-ly', 'name' => 'Tâm Lý'],
        ['slug' => 'phieu-luu', 'name' => 'Phiêu Lưu'],
        ['slug' => 'kinh-di', 'name' => 'Kinh Dị'],
        ['slug' => 'hoat-hinh', 'name' => 'Hoạt Hình'],
        ['slug' => 'tinh-cam', 'name' => 'Tình Cảm'],
        ['slug' => 'gia-dinh', 'name' => 'Gia Đình'],
        ['slug' => 'bi-an', 'name' => 'Bí Ẩn'],
        ['slug' => 'tai-lieu', 'name' => 'Tài Liệu'],
        ['slug' => 'vo-thuat', 'name' => 'Võ Thuật']
    ];

    public function current(): void {
        $movieModel = $this->model('Movie');
        $selectedGenres = $this->filterGenres();
        $keyword = trim((string)($_GET['q'] ?? ''));

        // Cấu hình phân trang
        $limit = 8; // Số phim trên 1 trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu
        $totalRows = $movieModel->countClientMovies('now_showing', $selectedGenres, $keyword);
        $totalPages = ceil($totalRows / $limit);
        $movies = $movieModel->getClientMovies('now_showing', $selectedGenres, $keyword, $limit, $offset);

        $this->view('layouts/main', [
            'title' => 'Phim Đang Chiếu',
            'content' => 'movies/current',
            'nowShowing' => $movies,
            'genres' => $this->genres,
            'selectedGenres' => $selectedGenres,
            'keyword' => $keyword,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function coming(): void {
        $movieModel = $this->model('Movie');
        $selectedGenres = $this->filterGenres();
        $keyword = trim((string)($_GET['q'] ?? ''));

        // Cấu hình phân trang
        $limit = 8;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu
        $totalRows = $movieModel->countClientMovies('coming_soon', $selectedGenres, $keyword);
        $totalPages = ceil($totalRows / $limit);
        $movies = $movieModel->getClientMovies('coming_soon', $selectedGenres, $keyword, $limit, $offset);

        $this->view('layouts/main', [
            'title' => 'Phim Sắp Chiếu',
            'content' => 'movies/coming',
            'comingSoon' => $movies,
            'genres' => $this->genres,
            'selectedGenres' => $selectedGenres,
            'keyword' => $keyword,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    private function filterGenres(): array {
        if (!isset($_GET['genre'])) {
            return [];
        }
        return is_array($_GET['genre']) ? $_GET['genre'] : [trim($_GET['genre'])];
    }
}