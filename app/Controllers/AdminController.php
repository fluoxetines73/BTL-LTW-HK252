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

        $db = Database::getInstance()->getPdo();
        $stats = [
            'total_users' => $this->safeCount($db, "SELECT COUNT(*) FROM users"),
            'total_products' => $this->safeCount($db, "SELECT COUNT(*) FROM products"),
            'total_news' => $this->safeCount($db, "SELECT COUNT(*) FROM news"),
            'locked_users' => $this->safeCount($db, "SELECT COUNT(*) FROM users WHERE status = 'inactive'"),
        ];

        // Gọi View giao diện quản trị
        $this->view('layouts/main', [
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
        $data = $userModel->getUsersPaginated((int)$page, $perPage);

        // Tính url phân trang
        $baseUrl = BASE_URL . 'admin/users';

        $this->view('layouts/main', [
            'title' => 'Quản lý Người dùng',
            'content' => 'admin/users/index',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'base_url' => $baseUrl,
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

        $userModel = $this->model('User');
        if (!empty($keyword)) {
            $data = $userModel->search($keyword, (int)$page, $perPage);
        } else {
            $data = $userModel->getUsersPaginated((int)$page, $perPage);
        }

        $baseUrl = BASE_URL . 'admin/search?q=' . urlencode($keyword);

        $this->view('layouts/main', [
            'title' => 'Tìm kiếm Người dùng',
            'content' => 'admin/users/search',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'keyword' => $keyword,
            'base_url' => $baseUrl,
        ]);
    }

    /**
     * Quản lý tin tức
     * Route: /admin/news
     */
    public function news() {
        $this->renderNewsManagement(null, 'Quản lý Tin tức');
    }

    /**
     * Quản lý ưu đãi
     * Route: /admin/news_promotions
     */
    public function news_promotions() {
        $this->renderNewsManagement('khuyen-mai', 'Quản lý Ưu đãi');
    }

    /**
     * Quản lý phim hay tháng
     * Route: /admin/news_monthly_movies
     */
    public function news_monthly_movies() {
        $this->renderNewsManagement('tin-tuc', 'Quản lý Phim Hay Tháng');
    }

    private function renderNewsManagement(?string $category, string $title): void {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        $articles = $newsModel->getAdminList($category);

        $this->view('layouts/main', [
            'title' => $title,
            'content' => 'admin/news/index',
            'articles' => $articles,
            'newsCategory' => $category,
        ]);
    }

    /**
     * Tạo tin tức mới (chỉ admin)
     * Route: /admin/create_news
     */
    public function create_news() {
        $this->middlewareAdmin();

        $flash = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $title = trim((string)($_POST['title'] ?? ''));
            $content = trim((string)($_POST['content'] ?? ''));
            $category = trim((string)($_POST['category'] ?? 'tin-tuc'));

            if ($title === '' || mb_strlen($title) < 4) {
                $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 4 ký tự.'];
            } elseif ($content === '' || mb_strlen($content) < 10) {
                $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 10 ký tự.'];
            } elseif (!in_array($category, ['tin-tuc', 'khuyen-mai', 'su-kien'], true)) {
                $flash = ['type' => 'error', 'message' => 'Danh mục không hợp lệ.'];
            } else {
                require_once APPROOT . '/Helpers/Upload.php';
                $uploader = new Upload();
                $imagePath = $uploader->handle($_FILES['image'] ?? [], 'news');

                if ($imagePath === null) {
                    $uploadError = $uploader->getError();
                    $flash = ['type' => 'error', 'message' => $uploadError !== '' ? $uploadError : 'Vui lòng chọn ảnh cho tin tức.'];
                } else {
                    $newsModel = $this->model('News');
                    $slug = $this->makeSlug($title) . '-' . time();

                    $created = $newsModel->createNews([
                        'title' => $title,
                        'slug' => $slug,
                        'content' => $content,
                        'image' => $imagePath,
                        'category' => $category,
                        'author_id' => (int)($_SESSION['auth_user']['id'] ?? 0),
                        'status' => 'published',
                        'published_at' => date('Y-m-d H:i:s'),
                    ]);

                    if ($created) {
                        $_SESSION['success'] = 'Đăng tin thành công.';
                        $this->redirect('admin/news');
                        return;
                    }

                    $flash = ['type' => 'error', 'message' => 'Không thể tạo tin tức. Vui lòng thử lại.'];
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Đăng Tin Tức',
            'content' => 'admin/news/create',
            'flash' => $flash,
        ]);
    }

    /**
     * Sửa tin tức
     * Route: /admin/edit_news/5
     */
    public function edit_news($newsId) {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        $article = $newsModel->findByIdWithAuthor((int)$newsId);

        if (!$article) {
            $_SESSION['error'] = 'Không tìm thấy tin tức.';
            $this->redirect('admin/news');
            return;
        }

        $flash = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $title = trim((string)($_POST['title'] ?? ''));
            $content = trim((string)($_POST['content'] ?? ''));
            $category = trim((string)($_POST['category'] ?? 'tin-tuc'));

            if ($title === '' || mb_strlen($title) < 4) {
                $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 4 ký tự.'];
            } elseif ($content === '' || mb_strlen($content) < 10) {
                $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 10 ký tự.'];
            } elseif (!in_array($category, ['tin-tuc', 'khuyen-mai', 'su-kien'], true)) {
                $flash = ['type' => 'error', 'message' => 'Danh mục không hợp lệ.'];
            } else {
                require_once APPROOT . '/Helpers/Upload.php';
                $uploader = new Upload();

                $imagePath = $article['image'] ?? null;
                if (isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    $uploaded = $uploader->handle($_FILES['image'], 'news', (string)($article['image'] ?? ''));
                    if ($uploaded === null && $uploader->getError() !== '') {
                        $flash = ['type' => 'error', 'message' => $uploader->getError()];
                    } else {
                        $imagePath = $uploaded;
                    }
                }

                if ($flash === null) {
                    $slug = $this->makeSlug($title) . '-' . (int)$article['id'];
                    $updated = $newsModel->updateNews((int)$article['id'], [
                        'title' => $title,
                        'slug' => $slug,
                        'content' => $content,
                        'image' => $imagePath,
                        'category' => $category,
                    ]);

                    if ($updated) {
                        $_SESSION['success'] = 'Cập nhật tin tức thành công.';
                        $this->redirect('admin/news');
                        return;
                    }

                    $flash = ['type' => 'error', 'message' => 'Không thể cập nhật tin tức.'];
                }
            }

            $article = array_merge($article, [
                'title' => $title,
                'content' => $content,
                'category' => $category,
            ]);
        }

        $this->view('layouts/main', [
            'title' => 'Sửa Tin Tức',
            'content' => 'admin/news/edit',
            'flash' => $flash,
            'article' => $article,
        ]);
    }

    /**
     * Xóa tin tức
     * Route: /admin/delete_news/5
     */
    public function delete_news($newsId) {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        if ($newsModel->deleteNews((int)$newsId)) {
            $_SESSION['success'] = 'Đã xóa tin tức.';
        } else {
            $_SESSION['error'] = 'Không thể xóa tin tức.';
        }

        $this->redirect('admin/news');
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
            $this->view('layouts/main', [
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

        $this->view('layouts/main', [
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

    private function safeCount(PDO $db, string $sql): int {
        try {
            $stmt = $db->query($sql);
            return (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            return 0;
        }
    }

    private function makeSlug(string $text): string {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', $text) ?? '';
        return trim($text, '-');
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
}
