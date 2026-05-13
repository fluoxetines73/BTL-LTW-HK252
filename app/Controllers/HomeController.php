<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/app/Models/Settings.php';

class HomeController extends Controller {
	public function index(): void {
    	$db = Database::getInstance()->getPdo();
    	$settings = new Settings();
    	$data = [];

    	$extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
                  <link rel="stylesheet" href="' . BASE_URL . 'public/css/home.css">';
    	$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                     <script src="' . BASE_URL . 'public/js/home.js"></script>';

    	$featured_movie_id = $settings->getFeaturedMovieId();
    	if ($featured_movie_id) {
        	$stmt = $db->prepare("SELECT id, title, slug, description, poster, banner, release_date, duration_min, age_rating, director FROM movies WHERE id = ?");
        	$stmt->execute([$featured_movie_id]);
        	$data['featured_movie'] = $stmt->fetch() ?: null;
    	}

    	$stmt = $db->query("SELECT id, title, slug, poster, release_date, status FROM movies WHERE status = 'now_showing' ORDER BY release_date DESC LIMIT 8");
    	$data['recommendations'] = $stmt->fetchAll();

    	$stmt = $db->query("SELECT id, title, slug, poster, release_date, status FROM movies WHERE status = 'coming_soon' AND release_date >= CURDATE() ORDER BY release_date ASC LIMIT 6");
    	$data['coming_soon'] = $stmt->fetchAll();

    	$stmt = $db->query("SELECT g.id, g.name, g.slug, COUNT(m.id) as movie_count FROM genres g LEFT JOIN movie_genres mg ON g.id = mg.genre_id LEFT JOIN movies m ON mg.movie_id = m.id GROUP BY g.id ORDER BY movie_count DESC LIMIT 7");
    	$data['genres'] = $stmt->fetchAll();

    	$newsModel = $this->model('News');
    	$rawNews = $newsModel ? $newsModel->getLatestPublished(4) : [];

    	$resolveImage = function(string $path) {
    		$path = trim($path);
    		if ($path === '') {
    			return BASE_URL . 'public/images/about/about-6.png';
    		}
    		if (preg_match('#^https?://#i', $path) === 1) {
    			return $path;
    		}
    		if (str_starts_with($path, 'public/')) {
    			return BASE_URL . $path;
    		}
    		if (str_starts_with($path, 'uploads/')) {
    			return BASE_URL . 'public/' . ltrim($path, '/');
    		}
    		return BASE_URL . 'public/' . ltrim($path, '/');
    	};

    	$data['news'] = [];
    	foreach ($rawNews as $n) {
    		$n['image'] = $resolveImage((string)($n['image'] ?? ''));
    		$data['news'][] = $n;
    	}

    	$promotionsRaw = $newsModel ? $newsModel->getPublishedByCategory('khuyen-mai') : [];

    	$data['ads'] = [];
    	foreach ($promotionsRaw as $promo) {
    		$img = !empty($promo['image']) ? $resolveImage((string)$promo['image']) : null;
    		$data['ads'][] = [
            	'id' => $promo['id'],
            	'title' => $promo['title'],
            	'image' => $img,
            	'link' => BASE_URL . 'news/' . ($promo['slug'] ?? ''),
            	'description' => $promo['content'] ?? ''
    		];
    	}

    	$this->view('layouts/main', [
        	'title'           => 'Trang Chủ - CGV Cinema',
        	'content'         => 'home/index',
        	'featured_movie'  => $data['featured_movie'] ?? null,
        	'recommendations' => $data['recommendations'],
        	'coming_soon'     => $data['coming_soon'] ?? [],
        	'genres'          => $data['genres'],
        	'news'            => $data['news'],
        	'ads'             => $data['ads'],
        	'extraHead'       => $extraHead,
        	'extraScripts'    => $extraScripts
    	]);
	}

	public function about(): void {
		$settingsModel = $this->model('AboutPageSettings');
		$timelineModel = $this->model('AboutTimelineItems');
		$statsModel = $this->model('AboutStatistics');
		$valuesModel = $this->model('AboutCoreValues');
		$leadershipModel = $this->model('AboutLeadership');

		$settings = $settingsModel->getSettings();
		$timelineItems = $timelineModel->getAllItems();
		$statistics = $statsModel->getAllItems();
		$coreValues = $valuesModel->getAllItems();
		$leadership = $leadershipModel->getAllItems();

		$hasStructuredData = !empty($settings);

		$pageModel = $this->model('Page');
		$page = $pageModel->findBySlug('gioi-thieu');

		$extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
<link rel="stylesheet" href="' . BASE_URL . 'public/assets/css/about.css">';
		$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:800,once:true});</script>';

		$this->view('layouts/main', [
			'title' => 'Giới thiệu',
			'content' => 'pages/about',
			'page' => $page,
			'settings' => $settings,
			'timelineItems' => $timelineItems,
			'statistics' => $statistics,
			'coreValues' => $coreValues,
			'leadership' => $leadership,
			'hasStructuredData' => $hasStructuredData,
			'extraHead' => $extraHead,
			'extraScripts' => $extraScripts,
		]);
	}

	public function contact(): void {
		$flash = null;
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$message = trim($_POST['message'] ?? '');

			if ($name === '' || $email === '' || $message === '') {
				$flash = ['type' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin.'];
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$flash = ['type' => 'error', 'message' => 'Email không hợp lệ.'];
			} else {
				$flash = ['type' => 'success', 'message' => 'Gửi liên hệ thành công.'];
			}
		}

		$this->view('layouts/main', [
			'title' => 'Liên hệ',
			'content' => 'pages/contact',
			'flash' => $flash,
		]);
	}

	public function faq(): void {
		$faqModel = $this->model('Faq');
		$grouped = $faqModel->findAllGroupedByCategory();
		$total = array_sum(array_map('count', $grouped));

		$extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
					<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
					<link rel="stylesheet" href="' . BASE_URL . 'public/assets/css/faq.css">';
		$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';

		$this->view('layouts/main', [
			'title' => 'Hỏi đáp',
			'content' => 'pages/faq',
			'pageTitle' => 'Câu hỏi thường gặp',
			'grouped' => $grouped,
			'total' => $total,
			'extraHead' => $extraHead,
			'extraScripts' => $extraScripts,
		]);
	}

	public function privacy(): void {
		$pageModel = $this->model('Page');
		$page = $pageModel->findBySlug('chinh-sach-bao-mat');

		$this->view('layouts/main', [
			'title' => 'Chính sách bảo mật',
			'content' => 'pages/privacy',
			'page' => $page,
		]);
	}

	public function terms(): void {
		$pageModel = $this->model('Page');
		$page = $pageModel->findBySlug('dieu-khoan-su-dung');

		$this->view('layouts/main', [
			'title' => 'Điều khoản sử dụng',
			'content' => 'pages/terms',
			'page' => $page,
		]);
	}

	public function regulations(): void {
		$pageModel = $this->model('Page');
		$page = $pageModel->findBySlug('quy-dinh-rap-chieu');

		$this->view('layouts/main', [
			'title' => 'Quy định rạp chiếu',
			'content' => 'pages/regulations',
			'page' => $page,
		]);
	}

	public function notFound(): void {
		$this->view('layouts/main', [
			'title' => '404 - Không tìm thấy trang',
			'content' => 'errors/404',
        ]);
	}
}