<?php
require_once ROOT . '/core/Controller.php';

class HomeController extends Controller {
	public function index(): void {
		$this->view('layouts/main', [
			'title' => 'Trang chu',
			'content' => 'home/index',
			'highlights' => [
				'Kien truc MVC tu viet de ca nhom dung chung',
				'Template Header / Footer / Navigation thong nhat',
				'Route convention ro rang de ghep code an toan',
			],
		]);
	}

	public function about(): void {
		$this->view('layouts/main', [
			'title' => 'Gioi thieu',
			'content' => 'home/about',
		]);
	}

	public function contact(): void {
		$flash = null;
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$message = trim($_POST['message'] ?? '');

			if ($name === '' || $email === '' || $message === '') {
				$flash = ['type' => 'error', 'message' => 'Vui long nhap day du thong tin.'];
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$flash = ['type' => 'error', 'message' => 'Email khong hop le.'];
			} else {
				$flash = ['type' => 'success', 'message' => 'Gui lien he thanh cong (demo).'];
			}
		}

		$this->view('layouts/main', [
			'title' => 'Lien he',
			'content' => 'home/contact',
			'flash' => $flash,
		]);
	}

	public function pricing(): void {
		$this->view('layouts/main', [
			'title' => 'Bang gia',
			'content' => 'home/pricing',
		]);
	}

	public function faq(): void {
		$this->view('layouts/main', [
			'title' => 'Hoi dap',
			'content' => 'home/faq',
		]);
	}

	public function notFound(): void {
		$this->view('layouts/main', [
			'title' => '404',
			'content' => 'home/not_found',
		]);
	}
}
