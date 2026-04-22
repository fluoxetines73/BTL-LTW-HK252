<?php
// app/Controllers/AdminController.php

require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Database.php';

class AdminController extends Controller {

    /**
     * Trang chủ quản trị (Dashboard)
     * Nhiệm vụ của Thành viên C: Đảm bảo phân quyền admin ở đây
     */
    public function admin_dashboard() {
        // Kiểm tra quyền Admin trước khi cho phép xem trang 
        $this->middlewareAdmin(); 

        // Lấy dữ liệu thống kê
        $pdo = Database::getInstance()->getPdo();
        
        $stats = [
            'total_users' => 0,
            'total_movies' => 0,
            'total_news' => 0,
            'locked_accounts' => 0,
        ];

        try {
            $stats['total_users'] = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        } catch (Throwable $e) {
            $stats['total_users'] = 0;
        }

        try {
            $stats['total_movies'] = (int)$pdo->query('SELECT COUNT(*) FROM movies')->fetchColumn();
        } catch (Throwable $e) {
            $stats['total_movies'] = 0;
        }

        try {
            // Tổng nội dung hiển thị ở khu vực News: bài viết + phim timeline
            $stats['total_news'] = (int)$pdo->query('SELECT (SELECT COUNT(*) FROM news) + (SELECT COUNT(*) FROM movies)')->fetchColumn();
        } catch (Throwable $e) {
            $stats['total_news'] = 0;
        }

        try {
            $stats['locked_accounts'] = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE status = 'inactive'")->fetchColumn();
        } catch (Throwable $e) {
            $stats['locked_accounts'] = 0;
        }

        // Gọi View giao diện quản trị (đồng bộ cùng layout với các trang admin khác)
        $this->view('layouts/admin', [
            'title' => 'Bảng điều khiển Admin',
            'content' => 'admin/dashboard',
            'stats' => $stats,
        ]);
    }

    // ===== USER MANAGEMENT =====

    /**
     * Danh sách người dùng với phân trang
     * Route: /admin/users hoặc /admin/users/1 (page 1)
     */
    public function users($page = 1) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $perPage = 10;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : (int)$page;
        $sort = (string)($_GET['sort'] ?? 'id_asc');
        $allowedSorts = ['id_asc', 'id_desc', 'created_desc', 'created_asc'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id_asc';
        }

        $data = $userModel->getUsersPaginated($currentPage, $perPage, $sort);

        // Tính url phân trang
        $baseUrl = BASE_URL . 'admin/users';

        $this->view('layouts/admin', [
            'title' => 'Quản lý Người dùng',
            'content' => 'admin/users/index',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'base_url' => $baseUrl,
            'sort' => $sort,
        ]);
    }

    /**
     * Tìm kiếm người dùng
     * Route: /admin/search?q=keyword&page=1
     */
    public function search() {
        $this->middlewareAdmin();

        $keyword = $_GET['q'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $sort = (string)($_GET['sort'] ?? 'id_asc');
        $allowedSorts = ['id_asc', 'id_desc', 'created_desc', 'created_asc'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id_asc';
        }

        $userModel = $this->model('User');
        if (!empty($keyword)) {
            $data = $userModel->search($keyword, (int)$page, $perPage, $sort);
        } else {
            $data = $userModel->getUsersPaginated((int)$page, $perPage, $sort);
        }

        $baseUrl = BASE_URL . 'admin/search?q=' . urlencode($keyword) . '&sort=' . urlencode($sort);

        $this->view('layouts/admin', [
            'title' => 'Tìm kiếm Người dùng',
            'content' => 'admin/users/search',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'keyword' => $keyword,
            'base_url' => $baseUrl,
            'sort' => $sort,
        ]);
    }

    /**
     * Chi tiết người dùng và form chỉnh sửa
     * Route: /admin/edit_user/5
     */
    public function edit_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            http_response_code(404);
            $this->view('layouts/admin', [
                'title' => 'Không tìm thấy',
                'content' => 'home/not_found'
            ]);
            return;
        }

        // Xử lý POST update thông tin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateUserHandler($userModel, $user);
            return;
        }

        $this->view('layouts/admin', [
            'title' => 'Chỉnh sửa Người dùng',
            'content' => 'admin/users/edit',
            'user' => $user,
        ]);
    }

    /**
     * Reset mật khẩu người dùng
     * Route: /admin/reset_password/5
     */
    public function reset_password($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

        // Reset mật khẩu
        $tempPassword = $userModel->resetPasswordToRandom((int)$userId);

        if ($tempPassword) {
            $_SESSION['success'] = "Mật khẩu đã được đặt lại thành: <strong>$tempPassword</strong>";
        } else {
            $_SESSION['error'] = 'Không thể đặt lại mật khẩu.';
        }

        $this->redirect('admin/users');
    }

    /**
     * Khóa người dùng
     * Route: /admin/lock_user/5
     */
    public function lock_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

        if ($userModel->updateStatus((int)$userId, 'inactive')) {
            $_SESSION['success'] = "Người dùng đã bị khóa.";
        } else {
            $_SESSION['error'] = 'Không thể khóa người dùng.';
        }

        $this->redirect('admin/users');
    }

    /**
     * Mở khóa người dùng
     * Route: /admin/unlock_user/5
     */
    public function unlock_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

        if ($userModel->updateStatus((int)$userId, 'active')) {
            $_SESSION['success'] = "Người dùng đã được mở khóa.";
        } else {
            $_SESSION['error'] = 'Không thể mở khóa người dùng.';
        }

        $this->redirect('admin/users');
    }

    /**
     * Xóa người dùng
     * Route: /admin/delete_user/5
     */
    public function delete_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

        // Không cho phép xóa chính mình
        if ((int)$userId === $_SESSION['auth_user']['id']) {
            $_SESSION['error'] = 'Không thể xóa tài khoản của chính bạn.';
            $this->redirect('admin/users');
            return;
        }

        if ($userModel->deleteUser((int)$userId)) {
            $_SESSION['success'] = "Người dùng đã bị xóa.";
        } else {
            $_SESSION['error'] = 'Không thể xóa người dùng. Có thể đây là admin duy nhất.';
        }

        $this->redirect('admin/users');
    }

    /**
     * Xử lý update thông tin người dùng (form POST handler)
     */
    private function updateUserHandler($userModel, $user) {
        // Validate dữ liệu
        $errors = [];
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'member';
        $status = $_POST['status'] ?? 'active';

        // Validation
        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Tên phải có ít nhất 2 ký tự.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        } elseif ($email !== $user['email']) {
            // Kiểm tra email có trùng không
            $existingUser = $userModel->findByEmail($email);
            if ($existingUser) {
                $errors[] = 'Email đã được sử dụng.';
            }
        }

        if ($phone && !preg_match('/^[0-9\-\+\s]{10,}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ.';
        }

        if (!in_array($role, ['member', 'admin'], true)) {
            $errors[] = 'Vai trò không hợp lệ.';
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            $errors[] = 'Trạng thái không hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('admin/edit_user/' . $user['id']);
            return;
        }

        // Xử lý upload avatar
        $avatarPath = null;
        if (!empty($_FILES['avatar'])) {
            require_once APPROOT . '/Helpers/Upload.php';
            $upload = new Upload();
            $avatarPath = $upload->handle($_FILES['avatar'], 'avatars', $user['avatar'] ?? '');
            
            if ($avatarPath === null && !empty($upload->getError())) {
                $_SESSION['errors'] = [$upload->getError()];
                $this->redirect('admin/edit_user/' . $user['id']);
                return;
            }
        }

        // Cập nhật thông tin người dùng
        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'avatar' => $avatarPath,
            'role' => $role,
            'status' => $status,
        ];

        if ($userModel->updateUserFull((int)$user['id'], $updateData)) {
            $_SESSION['success'] = 'Thông tin người dùng đã được cập nhật.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật thông tin người dùng.';
        }

        $this->redirect('admin/edit_user/' . $user['id']);
    }

    // ===== NEWS MANAGEMENT =====

    public function news() {
        $this->middlewareAdmin();

        $keyword = trim((string)($_GET['q'] ?? ''));
        $newsModel = $this->model('News');
        $movieModel = $this->model('Movie');

        try {
            $newsItems = $newsModel->getAdminList($keyword);
        } catch (Throwable $e) {
            $newsItems = [];
            $_SESSION['error'] = 'Không thể tải danh sách tin tức. Hãy kiểm tra bảng news.';
        }

        try {
            $movies = $movieModel->getAllMovies($keyword);
        } catch (Throwable $e) {
            $movies = [];
            if (!isset($_SESSION['error'])) {
                $_SESSION['error'] = 'Không thể tải danh sách phim để gộp vào trang quản lý tin tức.';
            }
        }

        $movieItems = array_values(array_map(function (array $movie): array {
            $status = (string)($movie['status'] ?? 'coming_soon');
            $category = $status === 'now_showing' ? 'phim-dang-chieu' : 'phim-sap-chieu';
            $releaseDate = (string)($movie['release_date'] ?? '');

            return [
                'id' => (int)($movie['id'] ?? 0),
                'title' => (string)($movie['title'] ?? 'Phim điện ảnh'),
                'slug' => (string)($movie['slug'] ?? ''),
                'content' => (string)($movie['description'] ?? ''),
                'category' => $category,
                'status' => 'published',
                'author_name' => 'Hệ thống phim',
                'created_at' => $releaseDate !== '' ? ($releaseDate . ' 00:00:00') : date('Y-m-d H:i:s'),
                'published_at' => $releaseDate !== '' ? ($releaseDate . ' 00:00:00') : date('Y-m-d H:i:s'),
                'source_type' => 'movie',
            ];
        }, array_filter($movies, static function (array $movie): bool {
            $status = (string)($movie['status'] ?? '');
            return in_array($status, ['now_showing', 'coming_soon'], true);
        })));

        $articles = array_merge(
            array_map(static function (array $item): array {
                $item['source_type'] = 'news';
                return $item;
            }, $newsItems),
            $movieItems
        );

        usort($articles, static function (array $a, array $b): int {
            $ta = strtotime((string)($a['published_at'] ?? $a['created_at'] ?? '')) ?: 0;
            $tb = strtotime((string)($b['published_at'] ?? $b['created_at'] ?? '')) ?: 0;
            if ($tb !== $ta) {
                return $tb <=> $ta;
            }
            return ((int)($b['id'] ?? 0)) <=> ((int)($a['id'] ?? 0));
        });

        $this->view('layouts/admin', [
            'title' => 'Quản lý Tin tức',
            'content' => 'admin/news/index',
            'articles' => $articles,
            'keyword' => $keyword,
            'categories' => $this->newsCategories(),
        ]);
    }

    public function create_news() {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        $flash = null;
        $old = [
            'title' => '',
            'content' => '',
            'category' => 'tin-tuc',
            'status' => 'draft',
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $title = trim((string)($_POST['title'] ?? ''));
            $content = trim((string)($_POST['content'] ?? ''));
            $category = (string)($_POST['category'] ?? 'tin-tuc');
            $status = (string)($_POST['status'] ?? 'draft');

            $old = [
                'title' => $title,
                'content' => $content,
                'category' => $category,
                'status' => $status,
            ];

            if ($title === '' || mb_strlen($title, 'UTF-8') < 5) {
                $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 5 ký tự.'];
            } elseif ($content === '' || mb_strlen($content, 'UTF-8') < 20) {
                $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 20 ký tự.'];
            } elseif (!array_key_exists($category, $this->newsCategories())) {
                $flash = ['type' => 'error', 'message' => 'Danh mục không hợp lệ.'];
            } elseif (!in_array($status, ['draft', 'published'], true)) {
                $flash = ['type' => 'error', 'message' => 'Trạng thái không hợp lệ.'];
            } else {
                $imagePath = null;
                if (!empty($_FILES['image']) && (($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)) {
                    require_once APPROOT . '/Helpers/Upload.php';
                    $upload = new Upload();
                    $imagePath = $upload->handle($_FILES['image'], 'news');
                    if ($imagePath === null && $upload->getError() !== '') {
                        $flash = ['type' => 'error', 'message' => $upload->getError()];
                    }
                }

                if (!$flash) {
                    try {
                        $saved = $newsModel->createNews([
                            'title' => $title,
                            'content' => $content,
                            'category' => $category,
                            'status' => $status,
                            'image' => $imagePath,
                            'author_id' => (int)($_SESSION['auth_user']['id'] ?? 0),
                        ]);

                        if ($saved) {
                            $_SESSION['success'] = 'Đã tạo bài viết mới.';
                            $this->redirect('admin/news');
                        }

                        $flash = ['type' => 'error', 'message' => 'Không thể tạo bài viết.'];
                    } catch (Throwable $e) {
                        $flash = ['type' => 'error', 'message' => 'Không thể tạo bài viết do lỗi dữ liệu.'];
                    }
                }
            }
        }

        $this->view('layouts/admin', [
            'title' => 'Thêm Tin tức',
            'content' => 'admin/news/form',
            'article' => $old,
            'isEdit' => false,
            'flash' => $flash,
            'categories' => $this->newsCategories(),
        ]);
    }

    public function edit_news($newsId) {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        $article = $newsModel->findByIdForAdmin((int)$newsId);

        if (!$article) {
            http_response_code(404);
            $this->view('layouts/admin', [
                'title' => 'Không tìm thấy',
                'content' => 'home/not_found',
            ]);
            return;
        }

        $flash = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $title = trim((string)($_POST['title'] ?? ''));
            $content = trim((string)($_POST['content'] ?? ''));
            $category = (string)($_POST['category'] ?? 'tin-tuc');
            $status = (string)($_POST['status'] ?? 'draft');

            if ($title === '' || mb_strlen($title, 'UTF-8') < 5) {
                $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 5 ký tự.'];
            } elseif ($content === '' || mb_strlen($content, 'UTF-8') < 20) {
                $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 20 ký tự.'];
            } elseif (!array_key_exists($category, $this->newsCategories())) {
                $flash = ['type' => 'error', 'message' => 'Danh mục không hợp lệ.'];
            } elseif (!in_array($status, ['draft', 'published'], true)) {
                $flash = ['type' => 'error', 'message' => 'Trạng thái không hợp lệ.'];
            } else {
                $imagePath = null;
                if (!empty($_FILES['image']) && (($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)) {
                    require_once APPROOT . '/Helpers/Upload.php';
                    $upload = new Upload();
                    $imagePath = $upload->handle($_FILES['image'], 'news', $article['image'] ?? '');
                    if ($imagePath === null && $upload->getError() !== '') {
                        $flash = ['type' => 'error', 'message' => $upload->getError()];
                    }
                }

                if (!$flash) {
                    try {
                        $updated = $newsModel->updateNews((int)$newsId, [
                            'title' => $title,
                            'content' => $content,
                            'category' => $category,
                            'status' => $status,
                            'image' => $imagePath,
                        ]);

                        if ($updated) {
                            $_SESSION['success'] = 'Cập nhật bài viết thành công.';
                            $this->redirect('admin/news');
                        }

                        $flash = ['type' => 'error', 'message' => 'Không thể cập nhật bài viết.'];
                    } catch (Throwable $e) {
                        $flash = ['type' => 'error', 'message' => 'Không thể cập nhật bài viết do lỗi dữ liệu.'];
                    }
                }

                $article = array_merge($article, [
                    'title' => $title,
                    'content' => $content,
                    'category' => $category,
                    'status' => $status,
                ]);
            }
        }

        $this->view('layouts/admin', [
            'title' => 'Sửa Tin tức',
            'content' => 'admin/news/form',
            'article' => $article,
            'isEdit' => true,
            'flash' => $flash,
            'categories' => $this->newsCategories(),
        ]);
    }

    public function delete_news($newsId) {
        $this->middlewareAdmin();

        try {
            $newsModel = $this->model('News');
            $deleted = $newsModel->deleteNews((int)$newsId);
            $_SESSION['success'] = $deleted ? 'Đã xóa bài viết.' : 'Không thể xóa bài viết.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể xóa bài viết lúc này.';
        }

        $this->redirect('admin/news');
    }

    // ===== NEWS REVIEWS MANAGEMENT =====

    public function reviews() {
        $this->middlewareAdmin();

        $keyword = trim((string)($_GET['q'] ?? ''));
        $status = trim((string)($_GET['status'] ?? ''));

        $newsModel = $this->model('News');
        try {
            $reviews = $newsModel->getAdminReviews($keyword, $status);
        } catch (Throwable $e) {
            $reviews = [];
            $_SESSION['error'] = 'Không thể tải danh sách bình luận/đánh giá. Hãy kiểm tra bảng news_reviews.';
        }

        $this->view('layouts/admin', [
            'title' => 'Quản lý Bình luận/Đánh giá',
            'content' => 'admin/reviews/index',
            'reviews' => $reviews,
            'keyword' => $keyword,
            'statusFilter' => $status,
        ]);
    }

    public function review_status($reviewId, $status) {
        $this->middlewareAdmin();

        $status = trim((string)$status);
        $newsModel = $this->model('News');

        try {
            $updated = $newsModel->updateReviewStatus((int)$reviewId, $status);
            $_SESSION['success'] = $updated ? 'Đã cập nhật trạng thái đánh giá.' : 'Không thể cập nhật trạng thái.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái bình luận/đánh giá.';
        }

        $this->redirect('admin/reviews');
    }

    public function delete_review($reviewId) {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        try {
            $deleted = $newsModel->deleteReview((int)$reviewId);
            $_SESSION['success'] = $deleted ? 'Đã xóa bình luận/đánh giá.' : 'Không thể xóa bình luận/đánh giá.';
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Không thể xóa bình luận/đánh giá.';
        }

        $this->redirect('admin/reviews');
    }

    private function newsCategories(): array {
        return [
            'tin-tuc' => 'Tin tức',
            'khuyen-mai' => 'Khuyến mãi',
            'su-kien' => 'Sự kiện',
            'phim-dang-chieu' => 'Phim đang chiếu',
            'phim-sap-chieu' => 'Phim sắp chiếu',
            'uu-dai' => 'Ưu đãi',
        ];
    }
}