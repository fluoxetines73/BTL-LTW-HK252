<?php
// Không cần require_once Model thủ công nữa vì đã có class Controller cha lo việc đó
require_once ROOT . '/core/Controller.php';

class AdminMovieController extends Controller {
    
    // Gắn middleware để bắt buộc phải là Admin mới được vào các trang này
    public function __construct() {
        $this->middlewareAdmin();
    }

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
            $movieModel = $this->model('Movie');
            $slug = trim($_POST['slug']);

            // 1. Validate Slug trùng
            if ($movieModel->isSlugExists($slug)) {
                $_SESSION['error'] = "Đường dẫn tĩnh (Slug) này đã tồn tại, vui lòng đổi tên khác!";
                $this->redirect('admin/movie/create');
                return;
            }

            // 2. Validate Thời lượng
            $duration = (int)$_POST['duration_min'];
            if ($duration <= 0) {
                $_SESSION['error'] = "Thời lượng phim phải là số dương!";
                $this->redirect('admin/movie/create');
                return;
            }

            // 3. Xử lý Upload Ảnh (Có kiểm tra bảo mật)
            $posterName = $this->handleFileUpload('poster');
            $bannerName = $this->handleFileUpload('banner');

            $data = [
                'title' => trim($_POST['title']),
                'slug' => $slug,
                'description' => trim($_POST['description'] ?? ''),
                'director' => trim($_POST['director'] ?? ''),
                'cast' => trim($_POST['cast'] ?? ''),
                'duration_min' => $duration,
                'release_date' => $_POST['release_date'],
                'age_rating' => $_POST['age_rating'],
                'status' => $_POST['status'],
                'poster' => $posterName,
                'banner' => $bannerName
            ];

            $newMovieId = $movieModel->createMovieWithImages($data);
            if ($newMovieId) {
                $movieModel->syncMovieGenres($newMovieId, $_POST['genres'] ?? []);
                $_SESSION['success'] = 'Thêm phim thành công!';
                $this->redirect('admin/movie/index');
            }
        }
    }
    // Hàm hỗ trợ upload an toàn
    private function handleFileUpload($fieldName) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
            
            // Kiểm tra định dạng và kích thước (Giới hạn 2MB)
            if (in_array($ext, $allowed) && $_FILES[$fieldName]['size'] <= 2 * 1024 * 1024) {
                $uploadDir = ROOT . '/public/uploads/movies/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $newName = $fieldName . '_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadDir . $newName)) {
                    return $newName;
                }
            }
        }
        return null;
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
    // Sửa hàm delete trong AdminMovieController.php
    public function delete($id = null) {
        if ($id) {
            $movieModel = $this->model('Movie');
            $movie = $movieModel->getMovieById($id);
            
            if ($movie) {
                $uploadDir = ROOT . '/public/uploads/movies/';
                // Xóa Poster
                if (!empty($movie['poster']) && file_exists($uploadDir . $movie['poster'])) {
                    unlink($uploadDir . $movie['poster']);
                }
                // Xóa Banner
                if (!empty($movie['banner']) && file_exists($uploadDir . $movie['banner'])) {
                    unlink($uploadDir . $movie['banner']);
                }
                
                $movieModel->deleteMovie($id);
                $_SESSION['success'] = "Đã xóa phim và dọn dẹp bộ nhớ!";
            }
        }
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
    /**
 * Xử lý cập nhật thông tin phim (Đã Audit: Bảo mật + Dọn rác)
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

            $slug = trim($_POST['slug']);
            // 1. Validate Slug trùng (trừ chính nó)
            if ($movieModel->isSlugExists($slug, $id)) {
                $_SESSION['error'] = "Slug '$slug' đã bị phim khác sử dụng!";
                $this->redirect('admin/movie/edit/' . $id);
                return;
            }

            // 2. Validate Thời lượng
            $duration = (int)$_POST['duration_min'];
            if ($duration <= 0) {
                $_SESSION['error'] = "Thời lượng không hợp lệ!";
                $this->redirect('admin/movie/edit/' . $id);
                return;
            }

            $uploadDir = ROOT . '/public/uploads/movies/';

            // 3. Xử lý Poster mới (Nếu có upload thì xóa ảnh cũ)
            $posterName = $oldMovie['poster']; // Mặc định giữ tên cũ
            $newPoster = $this->handleFileUpload('poster');
            if ($newPoster) {
                if (!empty($oldMovie['poster']) && file_exists($uploadDir . $oldMovie['poster'])) {
                    unlink($uploadDir . $oldMovie['poster']); // Xóa file cũ khỏi server
                }
                $posterName = $newPoster;
            }

            // 4. Xử lý Banner mới
            $bannerName = $oldMovie['banner'];
            $newBanner = $this->handleFileUpload('banner');
            if ($newBanner) {
                if (!empty($oldMovie['banner']) && file_exists($uploadDir . $oldMovie['banner'])) {
                    unlink($uploadDir . $oldMovie['banner']); // Xóa file cũ khỏi server
                }
                $bannerName = $newBanner;
            }

            $data = [
                'title' => trim($_POST['title']),
                'slug' => $slug,
                'description' => trim($_POST['description'] ?? ''),
                'director' => trim($_POST['director'] ?? ''),
                'cast' => trim($_POST['cast'] ?? ''),
                'duration_min' => $duration,
                'release_date' => $_POST['release_date'],
                'age_rating' => $_POST['age_rating'],
                'status' => $_POST['status'],
                'poster' => $posterName,
                'banner' => $bannerName
            ];

            if ($movieModel->updateMovieWithImages($id, $data)) {
                $movieModel->syncMovieGenres($id, $_POST['genres'] ?? []);
                $_SESSION['success'] = "Cập nhật phim thành công!";
                $this->redirect('admin/movie/index');
            }
        }
    }

/**
 * Xử lý xóa phim (Xóa sạch dấu vết ảnh vật lý)
 */
}
