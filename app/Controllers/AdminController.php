<?php
// app/Controllers/AdminController.php

require_once ROOT . '/core/Controller.php';

class AdminController extends Controller {

    public function admin_dashboard() {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $movieModel = $this->model('Movie');
        $showtimeModel = $this->model('Showtime');
        $comboModel = $this->model('Combo');
        $newsModel = $this->model('News');

        $stats = [
            'users' => $this->getTableCount($userModel),
            'movies' => $this->getTableCount($movieModel),
            'showtimes' => $this->getTableCount($showtimeModel),
            'combos' => $this->getTableCount($comboModel),
            'news' => $this->getTableCount($newsModel),
        ];

        $this->adminView('admin/dashboard', 'dashboard', [
            'title' => 'Bảng điều khiển Admin',
            'stats' => $stats,
        ]);
    }

    private function getTableCount($model): int {
        return $model->count();
    }

    public function users($page = 1) {
        $this->middlewareAdmin();

        $sort = trim((string)($_GET['sort'] ?? 'newest'));
        $sort = in_array($sort, ['newest', 'oldest', 'name_asc', 'name_desc', 'email_asc', 'email_desc'], true) ? $sort : 'newest';
        $status = trim((string)($_GET['status'] ?? 'all'));
        $status = in_array($status, ['all', 'active', 'inactive'], true) ? $status : 'all';

        $userModel = $this->model('User');
        $perPage = 10;
        $data = $userModel->getUsersFiltered((int)$page, $perPage, $sort, $status);

        $baseUrl = BASE_URL . 'admin/users';

        $this->adminView('admin/users/index', 'users', [
            'title' => 'Quản lý Người dùng',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'base_url' => $baseUrl,
            'sort' => $sort,
            'status' => $status,
        ]);
    }

    public function search() {
        $this->middlewareAdmin();

        $keyword = substr(trim((string)($_GET['q'] ?? '')), 0, 255);
        $page = $_GET['page'] ?? 1;
        $sort = trim((string)($_GET['sort'] ?? 'newest'));
        $sort = in_array($sort, ['newest', 'oldest', 'name_asc', 'name_desc', 'email_asc', 'email_desc'], true) ? $sort : 'newest';
        $status = trim((string)($_GET['status'] ?? 'all'));
        $status = in_array($status, ['all', 'active', 'inactive'], true) ? $status : 'all';

        $perPage = 10;

        $userModel = $this->model('User');
        if (!empty($keyword)) {
            $data = $userModel->search($keyword, (int)$page, $perPage, $sort, $status);
        } else {
            $data = $userModel->getUsersFiltered((int)$page, $perPage, $sort, $status);
        }

        $baseUrl = BASE_URL . 'admin/search?q=' . urlencode($keyword) . '&sort=' . urlencode($sort) . '&status=' . urlencode($status);

        $this->adminView('admin/users/search', 'users', [
            'title' => 'Tìm kiếm Người dùng',
            'users' => $data['users'],
            'current_page' => $data['current_page'],
            'total_pages' => $data['pages'],
            'total_users' => $data['total'],
            'keyword' => $keyword,
            'base_url' => $baseUrl,
            'sort' => $sort,
            'status' => $status,
        ]);
    }

    public function news() {
        $this->renderNewsManagement(null, 'Quản lý Tin tức');
    }

    public function news_promotions() {
        $this->renderNewsManagement('khuyen-mai', 'Quản lý Ưu đãi');
    }

    public function news_monthly_movies() {
        $this->renderNewsManagement('phim-hay-thang', 'Quản lý Phim Hay Tháng');
    }

    private function renderNewsManagement(?string $category, string $title): void {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');

        $keyword = substr(trim((string)($_GET['q'] ?? '')), 0, 255);
        $sort = trim((string)($_GET['sort'] ?? 'newest'));
        $sort = in_array($sort, ['newest', 'oldest'], true) ? $sort : 'newest';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !empty($_POST['action']) && $_POST['action'] === 'delete_selected') {
            if (!$this->validateCsrfToken()) {
                $_SESSION['error'] = 'CSRF token validation failed.';
                header('Location: ' . ($_SERVER['REQUEST_URI'] ?? 'admin/news'));
                exit();
            }
            $rawSelectedIds = $_POST['selected_ids'] ?? '';
            if (is_array($rawSelectedIds)) {
                $selectedIds = array_map('intval', array_map('trim', $rawSelectedIds));
            } elseif (is_string($rawSelectedIds) && $rawSelectedIds !== '') {
                $selectedIds = array_map('intval', array_map('trim', explode(',', $rawSelectedIds)));
            } else {
                $selectedIds = [];
            }
            $selectedIds = array_values(array_filter($selectedIds, static function ($id) { return $id > 0; }));

            if (!empty($selectedIds)) {
                try {
                    $deleted = $newsModel->deleteMultipleNews($selectedIds);
                } catch (PDOException $e) {
                    error_log('Bulk delete news failed: ' . $e->getMessage());
                    $deleted = false;
                }
                if ($deleted) {
                    $_SESSION['success'] = 'Đã xóa ' . count($selectedIds) . ' bài viết.';
                } else {
                    $_SESSION['error'] = 'Không thể xóa các bài viết đã chọn.';
                }
                $redirectTarget = $_SERVER['REQUEST_URI'] ?? 'admin/news';
                header('Location: ' . $redirectTarget);
                exit();
            }
        }

        $articles = $newsModel->searchAdminNews($category, $keyword !== '' ? $keyword : null, $sort);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->adminView('admin/news/index', 'news', [
            'title' => $title,
            'articles' => $articles,
            'newsCategory' => $category,
            'keyword' => $keyword,
            'sort' => $sort,
            'extraHead' => '<link rel="stylesheet" href="' . BASE_URL . 'public/css/admin-news.css">',
        ]);
    }

    public function create_news() {
        $this->middlewareAdmin();

        $flash = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!$this->validateCsrfToken()) {
                $flash = ['type' => 'error', 'message' => 'CSRF token validation failed. Please try again.'];
            } else {
                $title = trim((string)($_POST['title'] ?? ''));
                $highlightTitle = trim((string)($_POST['highlight_title'] ?? ''));
                $content = trim((string)($_POST['content'] ?? ''));
                $detailContent = trim((string)($_POST['detail_content'] ?? ''));
                $category = trim((string)($_POST['category'] ?? 'tin-tuc'));
                $featured = isset($_POST['featured']) && $_POST['featured'] === '1';

                if ($highlightTitle === '') {
                    $highlightTitle = $title;
                }
                if ($detailContent === '') {
                    $detailContent = $content;
                }

                if ($title === '' || mb_strlen($title) < 4) {
                    $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 4 ký tự.'];
                } elseif ($content === '' || mb_strlen($content) < 10) {
                    $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 10 ký tự.'];
                } elseif (!in_array($category, ['tin-tuc', 'khuyen-mai', 'su-kien', 'phim-hay-thang'], true)) {
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

                        $authorId = (int)($_SESSION['auth_user']['id'] ?? 0);
                        $userModel = $this->model('User');
                        $author = $userModel->findById($authorId);
                        if (!$author) {
                            $flash = ['type' => 'error', 'message' => 'Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.'];
                        } else {
                            try {
                                $created = $newsModel->createNews([
                                    'title' => $title,
                                    'highlight_title' => $highlightTitle,
                                    'slug' => $slug,
                                    'content' => $content,
                                    'detail_content' => $detailContent,
                                    'image' => $imagePath,
                                    'category' => $category,
                                    'author_id' => $authorId,
                                    'status' => 'published',
                                    'featured' => $featured,
                                    'published_at' => date('Y-m-d H:i:s'),
                                ]);
                            } catch (PDOException $e) {
                                error_log('Create news failed: ' . $e->getMessage());
                                $created = false;
                            }

                            if ($created) {
                                $_SESSION['success'] = 'Đăng tin thành công.';
                                $this->redirect('admin/news');
                                return;
                            }

                            $flash = ['type' => 'error', 'message' => 'Không thể tạo tin tức. Vui lòng thử lại.'];
                        }
                    }
                }
            }
        }

        $this->adminView('admin/news/create', 'news', [
            'title' => 'Đăng Tin Tức',
            'flash' => $flash,
            'extraHead' => '<link rel="stylesheet" href="' . BASE_URL . 'public/css/admin-news.css">',
        ]);
    }

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
            if (!$this->validateCsrfToken()) {
                $flash = ['type' => 'error', 'message' => 'CSRF token validation failed. Please try again.'];
            } else {
                $title = trim((string)($_POST['title'] ?? ''));
                $highlightTitle = trim((string)($_POST['highlight_title'] ?? ''));
                $content = trim((string)($_POST['content'] ?? ''));
                $detailContent = trim((string)($_POST['detail_content'] ?? ''));
                $category = trim((string)($_POST['category'] ?? 'tin-tuc'));
                $featured = isset($_POST['featured']) && $_POST['featured'] === '1';

                if ($highlightTitle === '') {
                    $highlightTitle = $title;
                }
                if ($detailContent === '') {
                    $detailContent = $content;
                }

                if ($title === '' || mb_strlen($title) < 4) {
                    $flash = ['type' => 'error', 'message' => 'Tiêu đề phải có ít nhất 4 ký tự.'];
                } elseif ($content === '' || mb_strlen($content) < 10) {
                    $flash = ['type' => 'error', 'message' => 'Nội dung phải có ít nhất 10 ký tự.'];
                } elseif (!in_array($category, ['tin-tuc', 'khuyen-mai', 'su-kien', 'phim-hay-thang'], true)) {
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
                        try {
                            $updated = $newsModel->updateNews((int)$article['id'], [
                                'title' => $title,
                                'highlight_title' => $highlightTitle,
                                'slug' => $slug,
                                'content' => $content,
                                'detail_content' => $detailContent,
                                'image' => $imagePath,
                                'category' => $category,
                                'featured' => $featured,
                            ]);
                        } catch (PDOException $e) {
                            error_log('Update news failed: ' . $e->getMessage());
                            $updated = false;
                        }

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
                    'highlight_title' => $highlightTitle,
                    'content' => $content,
                    'detail_content' => $detailContent,
                    'category' => $category,
                    'featured' => $featured,
                ]);
            }
        }

        $this->adminView('admin/news/edit', 'news', [
            'title' => 'Sửa Tin Tức',
            'flash' => $flash,
            'article' => $article,
            'extraHead' => '<link rel="stylesheet" href="' . BASE_URL . 'public/css/admin-news.css">',
        ]);
    }

    public function delete_news($newsId) {
        $this->middlewareAdmin();

        $newsModel = $this->model('News');
        try {
            $deleted = $newsModel->deleteNews((int)$newsId);
        } catch (PDOException $e) {
            error_log('Delete news failed: ' . $e->getMessage());
            $deleted = false;
        }

        if ($deleted) {
            $_SESSION['success'] = 'Đã xóa tin tức.';
        } else {
            $_SESSION['error'] = 'Không thể xóa tin tức.';
        }

        $this->redirect('admin/news');
    }

    public function edit_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            http_response_code(404);
            $this->adminView('home/not_found', 'users', [
                'title' => 'Không tìm thấy',
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateUserHandler($userModel, $user);
            return;
        }

        $this->adminView('admin/users/edit', 'users', [
            'title' => 'Chỉnh sửa Người dùng',
            'user' => $user,
        ]);
    }

    public function reset_password($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

        $tempPassword = $userModel->resetPasswordToRandom((int)$userId);

        if ($tempPassword) {
            $_SESSION['success'] = "Mật khẩu đã được đặt lại thành: <strong>$tempPassword</strong>";
        } else {
            $_SESSION['error'] = 'Không thể đặt lại mật khẩu.';
        }

        $this->redirect('admin/users');
    }

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

    public function delete_user($userId) {
        $this->middlewareAdmin();

        $userModel = $this->model('User');
        $user = $userModel->findById((int)$userId);

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            $this->redirect('admin/users');
            return;
        }

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

    private function makeSlug(string $text): string {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', $text) ?? '';
        return trim($text, '-');
    }

    private function updateUserHandler($userModel, $user) {
        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'member';
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Tên phải có ít nhất 2 ký tự.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        } elseif ($email !== $user['email']) {
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

        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'status' => $status,
        ];

        if ($avatarPath !== null) {
            $updateData['avatar'] = $avatarPath;
        }

        if ($userModel->updateUserFull((int)$user['id'], $updateData)) {
            $_SESSION['success'] = 'Thông tin người dùng đã được cập nhật.';

            if ((int)$user['id'] === (int)($_SESSION['auth_user']['id'] ?? 0)) {
                $_SESSION['auth_user']['avatar'] = $avatarPath ?? $user['avatar'] ?? '';
                $_SESSION['auth_user']['name'] = $name;
                $_SESSION['auth_user']['email'] = $email;
            }
        } else {
            $_SESSION['error'] = 'Không thể cập nhật thông tin người dùng.';
        }

        $this->redirect('admin/edit_user/' . $user['id']);
    }

    private function validateCsrfToken(): bool {
        $csrfToken = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['csrf_token'] ?? '');
        return $csrfToken !== '' && $sessionToken !== '' && hash_equals($sessionToken, $csrfToken);
    }
}