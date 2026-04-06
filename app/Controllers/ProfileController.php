<?php
require_once ROOT . '/core/Controller.php';

class ProfileController extends Controller {
    private User $userModel;

    public function __construct() {
        require_once APPROOT . '/Models/Model.php';
        require_once APPROOT . '/Models/User.php';
        require_once APPROOT . '/Helpers/Upload.php';
        $this->userModel = new User();
    }

    public function index(): void {
        $this->middlewareAuth();
        $user = $this->userModel->findById((int)$_SESSION['auth_user']['id']);
        if (!$user) {
            $this->notFound();
            return;
        }

        $this->view('layouts/main', [
            'title' => 'Hồ sơ cá nhân',
            'content' => 'profile/index',
            'user' => $user,
        ]);
    }

    public function edit(): void {
        $this->middlewareAuth();
        $user = $this->userModel->findById((int)$_SESSION['auth_user']['id']);
        if (!$user) {
            $this->notFound();
            return;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if ($name === '' || strlen($name) < 2) {
                $flash = ['type' => 'error', 'message' => 'Vui lòng nhập họ tên.'];
            } elseif ($phone !== '' && !preg_match('/^[0-9\-\+\s]{10,}$/', $phone)) {
                $flash = ['type' => 'error', 'message' => 'Số điện thoại không hợp lệ.'];
            } else {
                $avatarPath = null;
                if (isset($_FILES['avatar']) && ($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    $uploader = new Upload();
                    $avatarPath = $uploader->handle($_FILES['avatar'], 'avatars', $user['avatar'] ?? '');
                    if ($avatarPath === null && $uploader->getError() !== '') {
                        $flash = ['type' => 'error', 'message' => $uploader->getError()];
                    }
                }

                if (!isset($flash)) {
                    $this->userModel->updateProfile((int)$user['id'], [
                        'name' => $name,
                        'phone' => $phone,
                        'avatar' => $avatarPath,
                    ]);
                    $_SESSION['auth_user']['name'] = $name;
                    if ($avatarPath !== null) {
                        $_SESSION['auth_user']['avatar'] = $avatarPath;
                    }
                    $this->redirect('profile/index');
                }
            }
        }

        $this->view('layouts/main', [
            'title' => 'Chỉnh sửa hồ sơ',
            'content' => 'profile/edit',
            'user' => $user,
            'flash' => $flash ?? null,
        ]);
    }

    public function changePassword(): void {
        $this->middlewareAuth();
        $this->view('layouts/main', [
            'title' => 'Đổi mật khẩu',
            'content' => 'profile/change_password',
        ]);
    }

    public function updatePassword(): void {
        $this->middlewareAuth();

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['new_password_confirmation'] ?? '';

        $user = $this->userModel->findById((int)$_SESSION['auth_user']['id']);
        if (!$user || !password_verify($current, $user['password'])) {
            $flash = ['type' => 'error', 'message' => 'Mật khẩu hiện tại không đúng.'];
        } elseif (strlen($new) < 6) {
            $flash = ['type' => 'error', 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'];
        } elseif ($new !== $confirm) {
            $flash = ['type' => 'error', 'message' => 'Xác nhận mật khẩu không khớp.'];
        } else {
            $this->userModel->updatePassword((int)$user['id'], password_hash($new, PASSWORD_DEFAULT));
            $this->redirect('profile/index');
        }

        $this->view('layouts/main', [
            'title' => 'Đổi mật khẩu',
            'content' => 'profile/change_password',
            'flash' => $flash ?? null,
        ]);
    }

    public function notFound(): void {
        $this->view('layouts/main', [
            'title' => '404',
            'content' => 'home/not_found',
        ]);
    }
}
