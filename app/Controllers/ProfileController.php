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
            'title' => 'Ho so ca nhan',
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
            $address = trim($_POST['address'] ?? '');

            if ($name === '') {
                $flash = ['type' => 'error', 'message' => 'Vui long nhap ho ten.'];
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
                        'address' => $address,
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
            'title' => 'Chinh sua ho so',
            'content' => 'profile/edit',
            'user' => $user,
            'flash' => $flash ?? null,
        ]);
    }

    public function changePassword(): void {
        $this->middlewareAuth();
        $this->view('layouts/main', [
            'title' => 'Doi mat khau',
            'content' => 'profile/change_password',
        ]);
    }

    public function updatePassword(): void {
        $this->middlewareAuth();

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['new_password_confirmation'] ?? '';

        $user = $this->userModel->findById((int)$_SESSION['auth_user']['id']);
        if (!$user || !password_verify($current, $user['password_hash'])) {
            $flash = ['type' => 'error', 'message' => 'Mat khau hien tai khong dung.'];
        } elseif (strlen($new) < 6) {
            $flash = ['type' => 'error', 'message' => 'Mat khau moi phai co it nhat 6 ky tu.'];
        } elseif ($new !== $confirm) {
            $flash = ['type' => 'error', 'message' => 'Xac nhan mat khau khong khop.'];
        } else {
            $this->userModel->updatePassword((int)$user['id'], password_hash($new, PASSWORD_DEFAULT));
            $this->redirect('profile/index');
        }

        $this->view('layouts/main', [
            'title' => 'Doi mat khau',
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
