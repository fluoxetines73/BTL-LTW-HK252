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

        if (!empty($selectedGenres)) {
            $movies = $movieModel->getMoviesByStatusAndGenre('now_showing', $selectedGenres);
        } else {
            $movies = $movieModel->getMoviesByStatus('now_showing');
        }

        $this->view('layouts/main', [
            'title' => 'Phim Đang Chiếu',
            'content' => 'movies/current',
            'nowShowing' => $movies,
            'genres' => $this->genres,
            'selectedGenres' => $selectedGenres
        ]);
    }

    public function coming(): void {
        $movieModel = $this->model('Movie');
        $selectedGenres = $this->filterGenres();

        if (!empty($selectedGenres)) {
            $movies = $movieModel->getMoviesByStatusAndGenre('coming_soon', $selectedGenres);
        } else {
            $movies = $movieModel->getMoviesByStatus('coming_soon');
        }

        $this->view('layouts/main', [
            'title' => 'Phim Sắp Chiếu',
            'content' => 'movies/coming',
            'comingSoon' => $movies,
            'genres' => $this->genres,
            'selectedGenres' => $selectedGenres
        ]);
    }

    private function filterGenres(): array {
        if (!isset($_GET['genre'])) {
            return [];
        }
        return is_array($_GET['genre']) ? $_GET['genre'] : [trim($_GET['genre'])];
    }
}