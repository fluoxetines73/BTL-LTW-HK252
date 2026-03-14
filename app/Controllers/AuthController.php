<?php
require_once ROOT . '/core/Controller.php';

class AuthController extends Controller {
	public function index(): void {
		$this->redirect('auth/login');
	}

	public function login(): void {
		$flash = null;

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
			$email = trim($_POST['email'] ?? '');
			$password = trim($_POST['password'] ?? '');

			if ($email === '' || $password === '') {
				$flash = ['type' => 'error', 'message' => 'Email va mat khau la bat buoc.'];
			} else {
				$_SESSION['auth_user'] = [
					'id' => 1,
					'name' => 'Demo User',
					'email' => $email,
				];
				$flash = ['type' => 'success', 'message' => 'Dang nhap demo thanh cong.'];
			}
		}

		$this->view('layouts/main', [
			'title' => 'Dang nhap',
			'content' => 'auth/login',
			'flash' => $flash,
			'authUser' => $_SESSION['auth_user'] ?? null,
		]);
	}

	public function register(): void {
		$flash = null;

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$password = trim($_POST['password'] ?? '');

			if ($name === '' || $email === '' || $password === '') {
				$flash = ['type' => 'error', 'message' => 'Vui long nhap day du thong tin.'];
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$flash = ['type' => 'error', 'message' => 'Email khong hop le.'];
			} else {
				$flash = ['type' => 'success', 'message' => 'Dang ky demo thanh cong.'];
			}
		}

		$this->view('layouts/main', [
			'title' => 'Dang ky',
			'content' => 'auth/register',
			'flash' => $flash,
		]);
	}

	public function logout(): void {
		unset($_SESSION['auth_user']);
		$this->redirect('auth/login');
	}
}
