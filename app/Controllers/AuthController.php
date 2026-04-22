
<?php
require_once ROOT . '/core/Controller.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        require_once APPROOT . '/Models/User.php';
        $this->userModel = new User();
    }

    public function login(): void {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($_SESSION['auth_user'])) {
            $role = (string)($_SESSION['auth_user']['role'] ?? 'member');
            if ($role === 'admin') {
                $this->redirect('admin/admin_dashboard');
            }
            $this->redirect('home/index');
        }

        $flash = $_SESSION['auth_flash'] ?? null;
        unset($_SESSION['auth_flash']);

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
            'flash' => $flash,
            'authUser' => $_SESSION['auth_user'] ?? null,
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
                require_once APPROOT . '/Helpers/Email.php';
                $emailSender = new Email();

                $otp = (string)random_int(100000, 999999);
                $otpHash = password_hash($otp, PASSWORD_DEFAULT);
                $expireAt = date('Y-m-d H:i:s', time() + 5 * 60);

                $saved = $this->userModel->saveRegistrationOtp([
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'otp_hash' => $otpHash,
                    'expires_at' => $expireAt,
                ]);

                if (!$saved) {
                    $flash = ['type' => 'error', 'message' => 'Không thể tạo OTP. Vui lòng thử lại.'];
                } elseif (!$emailSender->sendRegistrationOtp($email, $name, $otp, 5)) {
                    $this->userModel->deleteRegistrationOtp($email);
                    $flash = ['type' => 'error', 'message' => $emailSender->getError()];
                } else {
                    $this->redirect('auth/verifyOtp?email=' . urlencode($email));
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Đăng ký',
            'content' => 'auth/register',
            'flash' => $flash
        ]);
    }

    public function verifyOtp(): void {
        $email = trim((string)($_POST['email'] ?? $_GET['email'] ?? ''));
        $flash = null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('auth/register');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $otp = trim((string)($_POST['otp'] ?? ''));

            if (!preg_match('/^[0-9]{6}$/', $otp)) {
                $flash = ['type' => 'error', 'message' => 'Mã OTP phải gồm đúng 6 chữ số.'];
            } else {
                $pending = $this->userModel->findRegistrationOtpByEmail($email);

                if (!$pending) {
                    $flash = ['type' => 'error', 'message' => 'Phiên xác thực không tồn tại. Vui lòng đăng ký lại.'];
                } elseif ((int)$pending['attempts'] >= 5) {
                    $this->userModel->deleteRegistrationOtp($email);
                    $flash = ['type' => 'error', 'message' => 'Bạn đã nhập sai OTP quá nhiều lần. Vui lòng đăng ký lại.'];
                } elseif (strtotime((string)$pending['expires_at']) < time()) {
                    $this->userModel->deleteRegistrationOtp($email);
                    $flash = ['type' => 'error', 'message' => 'Mã OTP đã hết hạn. Vui lòng đăng ký lại để nhận mã mới.'];
                } elseif (!password_verify($otp, (string)$pending['otp_hash'])) {
                    $this->userModel->increaseOtpAttempt($email);
                    $flash = ['type' => 'error', 'message' => 'Mã OTP không chính xác.'];
                } elseif ($this->userModel->findByEmail($email)) {
                    $this->userModel->deleteRegistrationOtp($email);
                    $flash = ['type' => 'error', 'message' => 'Email này đã được sử dụng.'];
                } else {
                    $created = $this->userModel->create([
                        'name' => $pending['full_name'],
                        'email' => $pending['email'],
                        'password_hash' => $pending['password_hash'],
                        'phone' => null,
                        'avatar' => null,
                        'role' => 'member',
                    ]);

                    if ($created) {
                        $this->userModel->deleteRegistrationOtp($email);
                        $_SESSION['auth_flash'] = ['type' => 'success', 'message' => 'Xác thực email thành công. Bạn có thể đăng nhập ngay bây giờ.'];
                        $this->redirect('auth/login');
                    }

                    $flash = ['type' => 'error', 'message' => 'Không thể tạo tài khoản. Vui lòng thử lại.'];
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Xác thực OTP',
            'content' => 'auth/verify_otp',
            'flash' => $flash,
            'email' => $email,
        ]);
    }

    public function logout(): void {
        // Xoa toan bo du lieu session cua nguoi dung hien tai
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('auth/login');
    }
}
