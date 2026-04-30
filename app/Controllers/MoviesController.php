<?php
require_once ROOT . '/core/Controller.php';

class MoviesController extends Controller {

    /**
     * Hiển thị danh sách Phim Đang Chiếu
     * Đường dẫn: /movies/current
     */
    public function current(): void {
        // 1. Khởi tạo Model Movie để làm việc với Database
        $movieModel = $this->model('Movie');

        // 2. Lấy danh sách phim có trạng thái 'now_showing' từ Database
        // Giả định Model Movie của bạn đã có hàm getMoviesByStatus
        $movies = $movieModel->getMoviesByStatus('now_showing');

        // 3. Gọi View và truyền dữ liệu sang
        // Biến truyền sang là 'nowShowing' để khớp với file View của bạn
        $this->view('layouts/main', [
            'title' => 'Phim Đang Chiếu',
            'content' => 'movies/current',
            'nowShowing' => $movies
        ]);
    }

    /**
     * Hiển thị danh sách Phim Sắp Chiếu
     * Đường dẫn: /movies/coming
     */
    public function coming(): void {
        $movieModel = $this->model('Movie');
        $movies = $movieModel->getMoviesByStatus('coming_soon');

        $this->view('layouts/main', [
            'title' => 'Phim Sắp Chiếu',
            'content' => 'movies/coming',
            'comingSoon' => $movies
        ]);
    }
}