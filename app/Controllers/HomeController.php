<?php
require_once ROOT . '/core/Controller.php';

class HomeController extends Controller {
    public function index(): void {
        $movieModel = $this->model('Movie');
        
        // Bắt từ khóa tìm kiếm
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        $nowShowing = [];
        $comingSoon = [];

        if ($keyword !== '') {
            $movies = $movieModel->searchMovies($keyword);
            foreach ($movies as $m) {
                if ($m['status'] == 'now_showing') $nowShowing[] = $m;
                if ($m['status'] == 'coming_soon') $comingSoon[] = $m;
            }
        } else {
            $nowShowing = $movieModel->getMoviesByStatus('now_showing');
            $comingSoon = $movieModel->getMoviesByStatus('coming_soon');
        }

        // Gọi View thông qua Layout chung của nhóm
        $this->view('layouts/main', [
            'title' => 'Trang Chủ - Đặt Vé Xem Phim',
            'content' => 'home/index', // Nạp mảnh ghép index.php vào giữa layout
            'nowShowing' => $nowShowing,
            'comingSoon' => $comingSoon,
            'keyword' => $keyword
        ]);
    }
}