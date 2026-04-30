<?php
// Không cần require_once Model thủ công nữa vì đã có class Controller cha lo việc đó
require_once ROOT . '/core/Controller.php';

class AdminMovieController extends Controller {
    
    // Gắn middleware để bắt buộc phải là Admin mới được vào các trang này
    public function __construct() {
        $this->middlewareAdmin();
    }

    /**
     * Hiển thị danh sách phim
     */
    public function index() {
        // Sử dụng hàm model() của class cha để gọi Movie Model
        $movieModel = $this->model('Movie');
        
        $movies = $movieModel->getAllMovies();
        
        // Truyền mảng data với key là 'movies'
        $this->view('admin/movies/index', [
            'movies' => $movies
        ]);
    }

    /**
     * Giao diện thêm phim mới
     */
    public function create() {
        $this->view('admin/movies/create');
    }
    /**
     * Xử lý dữ liệu form thêm mới và lưu vào database
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title']),
                'slug' => trim($_POST['slug']),
                'description' => trim($_POST['description'] ?? ''),
                'director' => trim($_POST['director'] ?? ''),
                'cast' => trim($_POST['cast'] ?? ''),
                'duration_min' => (int)$_POST['duration_min'],
                'release_date' => $_POST['release_date'],
                'age_rating' => $_POST['age_rating'],
                'status' => $_POST['status'],
                'poster' => 'default_poster.jpg' // Hoặc xử lý upload ảnh ở đây
            ];

            // 1. Lấy mảng thể loại từ Form
            $selectedGenres = $_POST['genres'] ?? [];

            $movieModel = $this->model('Movie');
            $newMovieId = $movieModel->createMovie($data); // Model cần trả về ID của phim vừa tạo

            if ($newMovieId) {
                // 2. Đồng bộ thể loại vào Database
                $movieModel->syncMovieGenres($newMovieId, $selectedGenres);
                $this->redirect('admin/movie/index');
            } else {
                echo "Có lỗi xảy ra khi lưu vào CSDL!";
            }
        }
    }
    /**
     * Xử lý xóa phim
     */
    public function delete($id = null) {
        if ($id) {
            $movieModel = $this->model('Movie');
            $movieModel->deleteMovie($id);
        }
        // Xóa xong thì tự động quay về trang danh sách
        $this->redirect('admin/movie/index');
    }
    /**
     * Giao diện sửa thông tin phim
     */
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('admin/movie/index');
            return;
        }

        $movieModel = $this->model('Movie');
        $movie = $movieModel->getMovieById($id);

        // Nếu người dùng nhập ID bậy bạ trên URL, đẩy về trang chủ admin
        if (!$movie) {
            $this->redirect('admin/movie/index');
            return;
        }

        $currentGenres = $this->model('Movie')->getGenreSlugsByMovieId($id); 

        $this->view('admin/movies/edit', [
            'movie' => $movie,
            'currentGenres' => $currentGenres // Truyền mảng này sang để View biết nút nào cần bật sáng
        ]);
    }
    /**
     * Xử lý dữ liệu form sửa và cập nhật database
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $data = [
                'title' => trim($_POST['title']),
                'slug' => trim($_POST['slug']),
                'description' => trim($_POST['description'] ?? ''),
                'director' => trim($_POST['director'] ?? ''),
                'cast' => trim($_POST['cast'] ?? ''),
                'duration_min' => (int)$_POST['duration_min'],
                'release_date' => $_POST['release_date'],
                'age_rating' => $_POST['age_rating'],
                'status' => $_POST['status']
                // Thêm xử lý upload ảnh ở đây nếu có
            ];

            // 1. Lấy mảng thể loại từ Form
            $selectedGenres = $_POST['genres'] ?? [];

            $movieModel = $this->model('Movie');
            $success = $movieModel->updateMovie($id, $data);

            if ($success) {
                // 2. Đồng bộ thể loại vào Database
                $movieModel->syncMovieGenres($id, $selectedGenres);
                $this->redirect('admin/movie/index');
            } else {
                echo "Có lỗi xảy ra khi cập nhật CSDL!";
            }
        }
    }
}