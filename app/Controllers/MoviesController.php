<?php
require_once ROOT . '/core/Controller.php';

class MoviesController extends Controller {

    /**
     * Hiển thị danh sách Phim Đang Chiếu
     * Đường dẫn: /movies/current
     */
   public function current(): void {
        $movieModel = $this->model('Movie');
        
        // Mảng thể loại đã được cập nhật đầy đủ 12 mục đồng bộ với Admin
        $genres = [
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

        $selectedGenres = [];
        if (isset($_GET['genre'])) {
            // Đảm bảo dữ liệu luôn là một mảng
            $selectedGenres = is_array($_GET['genre']) ? $_GET['genre'] : [trim($_GET['genre'])];
        }

        // 2. Lấy phim theo trạng thái VÀ mảng thể loại
        if (!empty($selectedGenres)) {
            // Sửa 'now_showing' thành 'coming_soon' nếu bạn đang ở hàm coming()
            $movies = $movieModel->getMoviesByStatusAndGenre('now_showing', $selectedGenres); 
        } else {
            $movies = $movieModel->getMoviesByStatus('now_showing');
        }

        $this->view('layouts/main', [
            'title' => 'Phim Đang Chiếu',
            'content' => 'movies/current',
            'nowShowing' => $movies,
            'genres' => $genres,
            'selectedGenres' => $selectedGenres
        ]);
    }
    /**
     * Hiển thị danh sách Phim Sắp Chiếu
     * Đường dẫn: /movies/coming
     */
    public function coming(): void {
        $movieModel = $this->model('Movie');
        
        // Sử dụng chung mảng 12 thể loại cho sự đồng nhất
        $genres = [
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

        $selectedGenres = [];
        if (isset($_GET['genre'])) {
            // Đảm bảo dữ liệu luôn là một mảng
            $selectedGenres = is_array($_GET['genre']) ? $_GET['genre'] : [trim($_GET['genre'])];
        }

        // 2. Lấy phim theo trạng thái VÀ mảng thể loại
        if (!empty($selectedGenres)) {
            // Sửa 'now_showing' thành 'coming_soon' nếu bạn đang ở hàm coming()
            $movies = $movieModel->getMoviesByStatusAndGenre('coming_soon', $selectedGenres); 
        } else {
            $movies = $movieModel->getMoviesByStatus('coming_soon');
        }

        $this->view('layouts/main', [
            'title' => 'Phim Sắp Chiếu',
            'content' => 'movies/coming',
            'comingSoon' => $movies,
            'genres' => $genres,
            'selectedGenres' => $selectedGenres
        ]);
    }
}