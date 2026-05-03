<?php
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/app/Models/Settings.php';

class HomeController extends Controller {
	public function index(): void {
    $db = Database::getInstance()->getPdo();
    $settings = new Settings();
    $data = [];

    // --- PHẦN 1: ASSETS (CSS/JS) ---
    $extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
                  <link rel="stylesheet" href="' . BASE_URL . 'public/css/home.css">';
    $extraScripts = '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                     <script src="' . BASE_URL . 'public/js/home.js"></script>';

    // --- PHẦN 2: KHÔI PHỤC DỮ LIỆU BẠN CỦA BẠN ĐÃ LÀM ---

    // 1. Lấy phim nổi bật (Featured Movie)
    $featured_movie_id = $settings->getFeaturedMovieId();
    if ($featured_movie_id) {
        $stmt = $db->prepare("SELECT id, title, slug, description, poster, banner, release_date, duration_min, age_rating, director FROM movies WHERE id = ?");
        $stmt->execute([$featured_movie_id]);
        $data['featured_movie'] = $stmt->fetch() ?: null;
    }

    // 2. Lấy 8 Phim được đề xuất (Recommendations)
    $stmt = $db->query("SELECT id, title, slug, poster, release_date, status FROM movies WHERE status = 'now_showing' ORDER BY release_date DESC LIMIT 8");
    $data['recommendations'] = $stmt->fetchAll();

    // 2b. Lấy 6 Phim sắp chiếu (Coming Soon)
    $stmt = $db->query("SELECT id, title, slug, poster, release_date, status FROM movies WHERE status = 'coming_soon' AND release_date >= CURDATE() ORDER BY release_date ASC LIMIT 6");
    $data['coming_soon'] = $stmt->fetchAll();

    // 3. Lấy danh sách Thể loại (Genres)
    $stmt = $db->query("SELECT g.id, g.name, g.slug, COUNT(m.id) as movie_count FROM genres g LEFT JOIN movie_genres mg ON g.id = mg.genre_id LEFT JOIN movies m ON mg.movie_id = m.id GROUP BY g.id ORDER BY movie_count DESC LIMIT 7");
    $data['genres'] = $stmt->fetchAll();

    // 4. Lấy 4 Tin tức mới nhất (News)
    $stmt = $db->query("SELECT id, title, slug, content, image, category, published_at FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 4");
    $data['news'] = $stmt->fetchAll();

    // 5. Dữ liệu quảng cáo (Ads)
    $data['ads'] = [
        ['id' => 1, 'title' => 'CGV Premium', 'image' => BASE_URL . 'public/assets/ads/ad-premium.jpg', 'link' => BASE_URL . 'pricing', 'description' => 'Ưu đãi độc quyền'],
        ['id' => 2, 'title' => 'Combo Đặc Biệt', 'image' => BASE_URL . 'public/assets/ads/ad-combo.jpg', 'link' => BASE_URL . 'movies', 'description' => 'Tiết kiệm 30%'],
        ['id' => 3, 'title' => 'Thành Viên VIP', 'image' => BASE_URL . 'public/assets/ads/ad-vip.jpg', 'link' => BASE_URL . 'pricing', 'description' => 'Tích điểm x3, ưu đãi đặc biệt'],
        ['id' => 4, 'title' => 'Birthday Special', 'image' => BASE_URL . 'public/assets/ads/ad-birthday.jpg', 'link' => BASE_URL . 'promotions', 'description' => 'Tặng vé miễn phí tháng sinh nhật'],
        ['id' => 5, 'title' => 'Student Deal', 'image' => BASE_URL . 'public/assets/ads/ad-student.jpg', 'link' => BASE_URL . 'promotions', 'description' => 'Giảm 20% cho học sinh sinh viên']
    ];

    // --- PHẦN 3: GỌI VIEW VÀ TRUYỀN TOÀN BỘ DỮ LIỆU ---
    $this->view('layouts/main', [
        'title'           => 'Trang Chủ - CGV Cinema',
        'content'         => 'home/index',
        'featured_movie'  => $data['featured_movie'] ?? null,
        'recommendations' => $data['recommendations'], // Đã khôi phục
        'coming_soon'     => $data['coming_soon'] ?? [],
        'genres'          => $data['genres'],          // Đã khôi phục
        'news'            => $data['news'],            // Đã khôi phục
        'ads'             => $data['ads'],             // Đã khôi phục
        'extraHead'       => $extraHead,
        'extraScripts'    => $extraScripts
        // Tuyệt đối không xóa gì thêm của nhóm ở đây nữa
    ]);
	}
    public function about(): void {
        // Load structured About page data from new tables
        $settingsModel = $this->model('AboutPageSettings');
        $timelineModel = $this->model('AboutTimelineItems');
        $statsModel = $this->model('AboutStatistics');
        $valuesModel = $this->model('AboutCoreValues');
        $leadershipModel = $this->model('AboutLeadership');

        // Get all data
        $settings = $settingsModel->getSettings();
        $timelineItems = $timelineModel->getAllItems();
        $statistics = $statsModel->getAllItems();
        $coreValues = $valuesModel->getAllItems();
        $leadership = $leadershipModel->getAllItems();

        // Check if we have structured data
        $hasStructuredData = !empty($settings);

        // Keep backward compatibility with old pages table
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
            // New structured data
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
