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

        $this->view('admin/movies/edit', [
            'movie' => $movie
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
            ];

            $movieModel = $this->model('Movie');
            $success = $movieModel->updateMovie($id, $data);

            if ($success) {
                $this->redirect('admin/movie/index');
            } else {
                echo "Có lỗi xảy ra khi cập nhật CSDL!";
            }
        }
    }
}