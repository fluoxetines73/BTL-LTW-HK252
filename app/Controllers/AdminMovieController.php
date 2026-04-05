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
        // Kiểm tra xem có đúng là request POST từ form gửi lên không
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Gom dữ liệu từ $_POST vào một mảng
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
                'poster' => 'default_poster.jpg' // Tạm thời fix cứng tên ảnh
            ];

            // Gọi Model và tiến hành lưu
            $movieModel = $this->model('Movie');
            $success = $movieModel->createMovie($data);

            if ($success) {
                // Nếu lưu thành công, chuyển hướng về trang danh sách
                $this->redirect('admin/movie/index');
            } else {
                echo "Có lỗi xảy ra khi lưu vào CSDL!";
            }
        }
    }
}