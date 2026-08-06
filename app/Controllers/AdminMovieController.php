<?php
require_once ROOT . '/core/Controller.php';

class AdminMovieController extends Controller {

    public function __construct() {
        $this->middlewareAdmin();
    }

    public function index() {
        $movieModel = $this->model('Movie');
        $keyword = trim((string)($_GET['q'] ?? ''));
        $status  = trim((string)($_GET['status'] ?? 'all'));
        $sort    = trim((string)($_GET['sort'] ?? 'newest'));

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

    public function create() {
        $this->adminView('admin/movies/create', 'movie', [
            'title' => 'Thêm Phim Mới'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieModel = $this->model('Movie');
            $slug = trim($_POST['slug']);

            if ($movieModel->isSlugExists($slug)) {
                $_SESSION['error'] = "Đường dẫn tĩnh (Slug) này đã tồn tại, vui lòng đổi tên khác!";
                $this->redirect('admin/movie/create');
                return;
            }

            $duration = (int)$_POST['duration_min'];
            if ($duration <= 0) {
                $_SESSION['error'] = "Thời lượng phim phải là số dương!";
                $this->redirect('admin/movie/create');
                return;
            }

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

    private function handleFileUpload($fieldName) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

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

    public function delete($id = null) {
        if ($id) {
            $movieModel = $this->model('Movie');
            if ($movieModel->hasBookings($id)) {
                $_SESSION['error'] = "Không thể xóa! Phim này đã phát sinh giao dịch đặt vé. Vui lòng chuyển trạng thái sang 'Ngừng chiếu' để bảo toàn dữ liệu doanh thu.";
                $this->redirect('admin/movie/index');
                return;
            }
            $movie = $movieModel->getMovieById($id);

            if ($movie) {
                $uploadDir = ROOT . '/public/uploads/movies/';
                if (!empty($movie['poster']) && file_exists($uploadDir . $movie['poster'])) {
                    unlink($uploadDir . $movie['poster']);
                }
                if (!empty($movie['banner']) && file_exists($uploadDir . $movie['banner'])) {
                    unlink($uploadDir . $movie['banner']);
                }

                $movieModel->deleteMovie($id);
                $_SESSION['success'] = "Đã xóa phim và dọn dẹp bộ nhớ!";
            }
        }
        $this->redirect('admin/movie/index');
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('admin/movie/index');
            return;
        }

        $movieModel = $this->model('Movie');
        $movie = $movieModel->getMovieById($id);

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
            if ($movieModel->isSlugExists($slug, $id)) {
                $_SESSION['error'] = "Slug '$slug' đã bị phim khác sử dụng!";
                $this->redirect('admin/movie/edit/' . $id);
                return;
            }

            $duration = (int)$_POST['duration_min'];
            if ($duration <= 0) {
                $_SESSION['error'] = "Thời lượng không hợp lệ!";
                $this->redirect('admin/movie/edit/' . $id);
                return;
            }

            $uploadDir = ROOT . '/public/uploads/movies/';
            $posterName = $oldMovie['poster'];
            $newPoster = $this->handleFileUpload('poster');
            if ($newPoster) {
                if (!empty($oldMovie['poster']) && file_exists($uploadDir . $oldMovie['poster'])) {
                    unlink($uploadDir . $oldMovie['poster']);
                }
                $posterName = $newPoster;
            }

            $bannerName = $oldMovie['banner'];
            $newBanner = $this->handleFileUpload('banner');
            if ($newBanner) {
                if (!empty($oldMovie['banner']) && file_exists($uploadDir . $oldMovie['banner'])) {
                    unlink($uploadDir . $oldMovie['banner']);
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
}
