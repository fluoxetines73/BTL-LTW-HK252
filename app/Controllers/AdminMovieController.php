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
                'poster' => 'default_poster.jpg'
            ];

            // Xử lý upload poster nếu có
            if (!empty($_FILES['poster']) && (int)($_FILES['poster']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $uploadedPoster = $this->uploadMoviePoster($_FILES['poster']);
                if ($uploadedPoster !== null) {
                    $data['poster'] = $uploadedPoster;
                }
            }

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
            $movieModel = $this->model('Movie');
            $movie = $movieModel->getMovieById($id);

            if (!$movie) {
                $this->redirect('admin/movie/index');
                return;
            }

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
            ];

            if (!empty($_FILES['poster']) && (int)($_FILES['poster']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $uploadedPoster = $this->uploadMoviePoster($_FILES['poster']);
                if ($uploadedPoster === null) {
                    echo "Ảnh poster không hợp lệ hoặc không thể tải lên.";
                    return;
                }

                $data['poster'] = $uploadedPoster;
            }

            $success = $movieModel->updateMovie($id, $data);

            if ($success) {
                if (!empty($data['poster']) && !empty($movie['poster']) && $movie['poster'] !== $data['poster']) {
                    $oldFileName = basename((string)$movie['poster']);
                    $oldPosterPath = ROOT . '/public/uploads/movies/' . $oldFileName;
                    if (is_file($oldPosterPath)) {
                        @unlink($oldPosterPath);
                    }
                }
                $this->redirect('admin/movie/index');
            } else {
                echo "Có lỗi xảy ra khi cập nhật CSDL!";
            }
        }
    }

    private function uploadMoviePoster(array $file): ?string {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($extensions[$mime])) {
            return null;
        }

        $uploadDir = ROOT . '/public/uploads/movies/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        $targetPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return null;
        }

        return 'uploads/movies/' . $filename;
    }
}