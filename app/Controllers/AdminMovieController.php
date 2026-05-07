<?php
// Không cần require_once Model thủ công nữa vì đã có class Controller cha lo việc đó
require_once ROOT . '/core/Controller.php';

class AdminMovieController extends Controller {
    
    // Gắn middleware để bắt buộc phải là Admin mới được vào các trang này
    public function __construct() {
        $this->middlewareAdmin();
    }

    /**
/**
     * Trang danh sách Phim (Kết hợp: Search, Filter, Sort và Bulk Delete)
     * Đã tích hợp Validate từ bản cập nhật mới nhất
     */
    public function index() {
        $movieModel = $this->model('Movie');
        $keyword = trim((string)($_GET['q'] ?? ''));
        $status  = trim((string)($_GET['status'] ?? 'all'));
        $sort    = trim((string)($_GET['sort'] ?? 'newest'));
        
        // Logic Phân trang
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $totalRows = $movieModel->countAdminMovies($keyword, $status);
        $totalPages = ceil($totalRows / $limit);

        $movies = $movieModel->searchAdminMovies($keyword, $status, $sort, $limit, $offset);

        $this->adminView('admin/movies/index', 'movie', [
            'movies' => $movies,
            'keyword' => $keyword,
            'status' => $status,
            'sort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * Giao diện thêm phim mới
     */
    public function create() {
        $this->adminView('admin/movies/create', 'movie', [
            'title' => 'Thêm Phim Mới'
        ]);
    }
    /**
     * Xử lý dữ liệu form thêm mới và lưu vào database
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Khởi tạo tên file mặc định
            $posterName = null;
            $bannerName = null;
            $uploadErrors = [];

            // Xử lý Upload POSTER
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = ROOT . '/public/uploads/movies/';
                    
                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
                    $posterName = 'poster_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    
                    if (move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $posterName)) {
                    } else {
                        $uploadErrors[] = "Lỗi khi di chuyển file poster";
                    }
                } else {
                    $uploadErrors[] = "Lỗi upload poster: " . $this->getUploadErrorMessage($_FILES['poster']['error']);
                }
            } else {
            }

            // Xử lý Upload BANNER
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = ROOT . '/public/uploads/movies/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                    $bannerName = 'banner_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    
                    if (move_uploaded_file($_FILES['banner']['tmp_name'], $uploadDir . $bannerName)) {
                    } else {
                        $uploadErrors[] = "Lỗi khi di chuyển file banner";
                    }
                } else {
                    $uploadErrors[] = "Lỗi upload banner: " . $this->getUploadErrorMessage($_FILES['banner']['error']);
                }
            } else {
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
                'poster' => $posterName,
                'banner' => $bannerName
            ];

            // 1. Lấy mảng thể loại từ Form
            $selectedGenres = $_POST['genres'] ?? [];

            $movieModel = $this->model('Movie');
            $newMovieId = $movieModel->createMovieWithImages($data);

            if ($newMovieId) {
                // 2. Đồng bộ thể loại vào Database
                $movieModel->syncMovieGenres($newMovieId, $selectedGenres);
                
                // Set success message
                $_SESSION['success'] = 'Thêm phim thành công!' . (!empty($uploadErrors) ? ' (Có lỗi upload: ' . implode(', ', $uploadErrors) . ')' : '');
                $this->redirect('admin/movie/index');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi lưu vào CSDL!';
                $this->redirect('admin/movie/create');
            }
        }
    }

    /**
     * Helper method to get upload error message
     */
    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File vượt quá kích thước cho phép trong php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File vượt quá kích thước cho phép trong form',
            UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần',
            UPLOAD_ERR_NO_FILE => 'Không có file nào được upload',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào đĩa',
            UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi extension'
        ];
        return $errors[$errorCode] ?? 'Lỗi không xác định';
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

        $this->adminView('admin/movies/edit', 'movie', [
            'movie' => $movie,
            'currentGenres' => $currentGenres,
            'title' => 'Sửa Phim'
        ]);
    }
    /**
     * Xử lý dữ liệu form sửa và cập nhật database
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $movieModel = $this->model('Movie');
            $oldMovie = $movieModel->getMovieById($id);
            
            if (!$oldMovie) {
                $_SESSION['error'] = 'Không tìm thấy phim!';
                $this->redirect('admin/movie/index');
                return;
            }

            // Khởi tạo với giá trị cũ
            $posterName = $oldMovie['poster'];
            $bannerName = $oldMovie['banner'];
            $uploadErrors = [];
            $uploadSuccess = [];

            // Xử lý Upload POSTER mới
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = ROOT . '/public/uploads/movies/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Xóa ảnh cũ nếu có
                    if (!empty($oldMovie['poster'])) {
                        $oldPath = $uploadDir . $oldMovie['poster'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $fileExtension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
                    $posterName = 'poster_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    
                    if (move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $posterName)) {
                        $uploadSuccess[] = "Poster";
                    } else {
                        $uploadErrors[] = "Lỗi khi di chuyển file poster";
                        $posterName = $oldMovie['poster']; // Giữ ảnh cũ
                    }
                } else {
                    $uploadErrors[] = "Lỗi upload poster: " . $this->getUploadErrorMessage($_FILES['poster']['error']);
                }
            }

            // Xử lý Upload BANNER mới
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = ROOT . '/public/uploads/movies/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Xóa ảnh cũ nếu có
                    if (!empty($oldMovie['banner'])) {
                        $oldPath = $uploadDir . $oldMovie['banner'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $fileExtension = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                    $bannerName = 'banner_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    
                    if (move_uploaded_file($_FILES['banner']['tmp_name'], $uploadDir . $bannerName)) {
                        $uploadSuccess[] = "Banner";
                    } else {
                        $uploadErrors[] = "Lỗi khi di chuyển file banner";
                        $bannerName = $oldMovie['banner']; // Giữ ảnh cũ
                    }
                } else {
                    $uploadErrors[] = "Lỗi upload banner: " . $this->getUploadErrorMessage($_FILES['banner']['error']);
                }
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
                'poster' => $posterName,
                'banner' => $bannerName
            ];

            // 1. Lấy mảng thể loại từ Form
            $selectedGenres = $_POST['genres'] ?? [];

            $success = $movieModel->updateMovieWithImages($id, $data);

            if ($success) {
                // 2. Đồng bộ thể loại vào Database
                $movieModel->syncMovieGenres($id, $selectedGenres);
                
                // Build success message
                $successMsg = 'Cập nhật phim thành công!';
                if (!empty($uploadSuccess)) {
                    $successMsg .= ' Đã upload: ' . implode(', ', $uploadSuccess) . '.';
                }
                $_SESSION['success'] = $successMsg;
                
                if (!empty($uploadErrors)) {
                    $_SESSION['error'] = 'Lỗi upload: ' . implode(', ', $uploadErrors);
                }
                
                $this->redirect('admin/movie/index');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật CSDL!';
                $this->redirect('admin/movie/edit/' . $id);
            }
        }
    }
}
