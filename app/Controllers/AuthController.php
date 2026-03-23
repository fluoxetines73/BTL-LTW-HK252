// app/Controllers/AuthController.php
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

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['status'] === 'locked') {
                    $flash = ['type' => 'error', 'message' => 'Tài khoản của bạn đã bị khóa.'];
                } else {
                    // Lưu thông tin vào Session để phân quyền [cite: 32]
                    $_SESSION['auth_user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'avatar' => $user['avatar'] ?? null,
                        'role' => $user['role'] // 'admin' hoặc 'member' [cite: 5]
                    ];
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

            if ($this->userModel->findByEmail($email)) {
                $flash = ['type' => 'error', 'message' => 'Email này đã được sử dụng.'];
            } else {
                $success = $this->userModel->create([
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'phone' => null,
                    'address' => null,
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
