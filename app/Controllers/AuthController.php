
<?php
require_once ROOT . '/core/Controller.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        require_once APPROOT . '/Models/User.php';
        $this->userModel = new User();
    }

    public function login(): void {
        $flash = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $flash = ['type' => 'error', 'message' => 'Email không hợp lệ.'];
            } elseif (strlen($password) < 6) {
                $flash = ['type' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
            }

            $user = $flash ? false : $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] === 'locked') {
                    $flash = ['type' => 'error', 'message' => 'Tài khoản của bạn đã bị khóa.'];
                } else {
                    // Lưu thông tin vào Session để phân quyền [cite: 32]
                    $_SESSION['auth_user'] = [
                        'id' => $user['id'],
                        'name' => $user['full_name'],
                        'email' => $user['email'],
                        'avatar' => $user['avatar'] ?? null,
                        'role' => $user['role'] // 'admin' hoặc 'member' [cite: 5]
                    ];
                    if ($user['role'] === 'admin') {
                        $this->redirect('admin/admin_dashboard');
                    }
                    $this->redirect('home/index');
                }
            } else {
                $flash = ['type' => 'error', 'message' => 'Email hoặc mật khẩu không chính xác.'];
            }
        }

        $this->view('layouts/main', [
            'title' => 'Đăng nhập',
            'content' => 'auth/login',
            'flash' => $flash
        ]);
    }

    public function register(): void {
        $flash = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($name === '' || strlen($name) < 2) {
                $flash = ['type' => 'error', 'message' => 'Họ tên phải có ít nhất 2 ký tự.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $flash = ['type' => 'error', 'message' => 'Email không hợp lệ.'];
            } elseif (strlen($password) < 6) {
                $flash = ['type' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
            } elseif ($this->userModel->findByEmail($email)) {
                $flash = ['type' => 'error', 'message' => 'Email này đã được sử dụng.'];
            } else {
                $success = $this->userModel->create([
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'phone' => null,
                    'avatar' => null,
                    'role' => 'member'
                ]);

                if ($success) {
                    $this->redirect('auth/login');
                } else {
                    $flash = ['type' => 'error', 'message' => 'Có lỗi xảy ra, vui lòng thử lại.'];
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Đăng ký',
            'content' => 'auth/register',
            'flash' => $flash
        ]);
    }
}
