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

    // 3. Lấy danh sách Thể loại (Genres)
    $stmt = $db->query("SELECT g.id, g.name, g.slug, COUNT(m.id) as movie_count FROM genres g LEFT JOIN movie_genres mg ON g.id = mg.genre_id LEFT JOIN movies m ON mg.movie_id = m.id GROUP BY g.id ORDER BY movie_count DESC LIMIT 7");
    $data['genres'] = $stmt->fetchAll();

    // 4. Lấy 4 Tin tức mới nhất (News)
    $stmt = $db->query("SELECT id, title, slug, content, image, category, published_at FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 4");
    $data['news'] = $stmt->fetchAll();

    // 5. Dữ liệu quảng cáo (Ads)
    $data['ads'] = [
        ['id' => 1, 'title' => 'CGV Premium', 'image' => BASE_URL . 'public/assets/ads/ad-premium.jpg', 'link' => BASE_URL . 'pricing', 'description' => 'Ưu đãi độc quyền'],
        ['id' => 2, 'title' => 'Combo Đặc Biệt', 'image' => BASE_URL . 'public/assets/ads/ad-combo.jpg', 'link' => BASE_URL . 'movies', 'description' => 'Tiết kiệm 30%']
        // Bạn có thể thêm các phần quảng cáo khác của nhóm vào đây
    ];

    // --- PHẦN 3: GỌI VIEW VÀ TRUYỀN TOÀN BỘ DỮ LIỆU ---
    $this->view('layouts/main', [
        'title'           => 'Trang Chủ - CGV Cinema',
        'content'         => 'home/index',
        'featured_movie'  => $data['featured_movie'] ?? null,
        'recommendations' => $data['recommendations'], // Đã khôi phục
        'genres'          => $data['genres'],          // Đã khôi phục
        'news'            => $data['news'],            // Đã khôi phục
        'ads'             => $data['ads'],             // Đã khôi phục
        'extraHead'       => $extraHead,
        'extraScripts'    => $extraScripts
        // Tuyệt đối không xóa gì thêm của nhóm ở đây nữa
    ]);
	}
    public function about(): void {
        $movieModel = $this->model('Movie');
        
        // Bắt từ khóa tìm kiếm
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        $nowShowing = [];
        $comingSoon = [];

        if ($keyword !== '') {
            $movies = $movieModel->searchMovies($keyword);
            foreach ($movies as $m) {
                if ($m['status'] == 'now_showing') $nowShowing[] = $m;
                if ($m['status'] == 'coming_soon') $comingSoon[] = $m;
            }
        } else {
            $nowShowing = $movieModel->getMoviesByStatus('now_showing');
            $comingSoon = $movieModel->getMoviesByStatus('coming_soon');
        }

		$extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
<link rel="stylesheet" href="' . BASE_URL . 'public/assets/css/about.css">';
		$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:800,once:true});</script>';

		$this->view('layouts/main', [
			'title' => 'Giới thiệu',
			'content' => 'pages/about',
			'page' => $page,
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
		$grouped = [
			'Vé & Đặt chỗ' => [
				['id' => 1, 'question' => 'Làm sao để đặt vé xem phim trực tuyến?', 'answer' => 'Bạn có thể truy cập website hoặc ứng dụng CGV Cinema, chọn phim, giờ chiếu, ghế ngồi và thanh toán. Vé điện tử sẽ được gửi qua email.'],
				['id' => 2, 'question' => 'Vé có những loại nào?', 'answer' => 'CGV Cinema cung cấp vé cho khán giả P, K (dưới 13), T13 (từ 13+), T16 (từ 16+), T18 (từ 18+). Ngoài ra, có vé VIP Suite cho trải nghiệm cao cấp.'],
				['id' => 3, 'question' => 'Có chiết khấu cho nhóm đoàn không?', 'answer' => 'Có. Đặt vé từ 10 vé trở lên, khách hàng được giảm giá 15%. Liên hệ bộ phận bán vé nhóm tại các rạp để được tư vấn chi tiết.'],
				['id' => 4, 'question' => 'Tôi đã mua vé nhưng muốn đổi giờ chiếu, có được không?', 'answer' => 'Có thể đổi vé miễn phí nếu còn ghế trống ở suất chiếu mong muốn. Yêu cầu phải được thực hiện trước 1 giờ chiếu.'],
				['id' => 5, 'question' => 'Có chính sách hoàn tiền vé không?', 'answer' => 'Vé không hoàn tiền nhưng có thể đổi sang suất chiếu khác miễn phí. Trường hợp đặc biệt liên hệ với bộ phận chăm sóc khách hàng.'],
			],
			'Thành viên & Rewards' => [
				['id' => 6, 'question' => 'Chương trình thành viên của CGV Cinema hoạt động như thế nào?', 'answer' => 'Thành viên nhận điểm thưởng khi mua vé, bắp nước, combo. Tích lũy điểm để đổi vé miễn phí, đồ ăn hoặc các ưu đãi đặc biệt.'],
				['id' => 7, 'question' => 'Cách đăng ký thành viên?', 'answer' => 'Đăng ký miễn phí trên website, ứng dụng, hoặc tại quầy vé các rạp. Cung cấp email, số điện thoại, và tên để tạo tài khoản.'],
				['id' => 8, 'question' => 'Thành viên được ưu đãi gì?', 'answer' => 'Ưu đãi bao gồm: giảm giá vé, ưu tiên mua vé sớm, khuyến mãi cho bắp nước, sự kiện VIP dành riêng cho thành viên VIP.'],
				['id' => 9, 'question' => 'Điểm thưởng có hạn sử dụng không?', 'answer' => 'Điểm có hiệu lực 1 năm kể từ ngày tích lũy. Khuyến khích sử dụng đều đặn để không bị mất điểm.'],
			],
			'Thông tin Rạp' => [
				['id' => 10, 'question' => 'CGV Cinema có những công nghệ gì?', 'answer' => 'CGV Cinema sở hữu công nghệ 4K Laser, Dolby Atmos, 4DX (ghế rung động với hiệu ứng), ScreenX (màn hình xung quanh 270 độ). Không phải tất cả phòng chiếu đều có công nghệ này.'],
				['id' => 11, 'question' => 'Các rạp CGV Cinema nằm ở những đâu?', 'answer' => 'CGV Cinema có 8 rạp tại 4 thành phố lớn: Hà Nội, TP. Hồ Chí Minh, Hải Phòng, Đà Nẵng. Chi tiết địa chỉ xem trên website.'],
				['id' => 12, 'question' => 'Rạp có phòng chờ tiện nghi không?', 'answer' => 'Tất cả rạp CGV Cinema đều có phòng chờ thoải mái với WiFi miễn phí, bàn ghế, nước uống. VIP Suite có lounge riêng với tiện nghi cao cấp.'],
				['id' => 13, 'question' => 'Có hỗ trợ cho khách hàng khuyết tật không?', 'answer' => 'Có. CGV Cinema cung cấp khu vực chuyên dành cho người khuyết tật, nhân viên hỗ trợ, và các tiện nghi phù hợp. Vui lòng thông báo trước khi mua vé.'],
			],
			'Chính sách & Quy định' => [
				['id' => 14, 'question' => 'Có thể mang thức ăn từ bên ngoài vào rạp không?', 'answer' => 'Không. Theo chính sách, khách hàng phải mua bắp nước tại cửa hàng trong rạp. Tuy nhiên, nước uống không có khí được phép mang vào.'],
				['id' => 15, 'question' => 'Trẻ em dưới 3 tuổi có cần mua vé không?', 'answer' => 'Trẻ em dưới 3 tuổi không cần mua vé riêng nếu ngồi chung ghế với phụ huynh. Từ 3 tuổi trở lên cần mua vé.'],
				['id' => 16, 'question' => 'Rạp có quy định về sử dụng điện thoại không?', 'answer' => 'Yêu cầu tắt điện thoại hoặc chế độ im lặng trong phòng chiếu. Chụp ảnh hoặc quay phim không được phép để bảo vệ bản quyền.'],
				['id' => 17, 'question' => 'Có thể hủy chương trình tổ chức sinh nhật tại rạp không?', 'answer' => 'Có thể hủy nếu thông báo 7 ngày trước. Hủy muộn hơn sẽ bị tính phí huỷ bỏ 50% chi phí.'],
				['id' => 18, 'question' => 'Rạp có cho phép quay video bộ phim để chia sẻ không?', 'answer' => 'Không. Quay phim bộ đồ hoặc bất kỳ nội dung nào từ rạp chiếu là vi phạm bản quyền và sẽ bị xử lý theo pháp luật.'],
			],
			'Bắp & Đồ ăn' => [
				['id' => 19, 'question' => 'Menu bắp nước của CGV Cinema có gì?', 'answer' => 'Menu bao gồm: bắp rang bơ/mặn, nước ngọt, trà/cà phê, cam ép, kem, bánh ngọt, và các combo ưu đãi giá tốt.'],
				['id' => 20, 'question' => 'Combo bắp nước rẻ hơn bao nhiêu so với mua lẻ?', 'answer' => 'Combo tiết kiệm khoảng 20-30% so với mua từng loại. Ví dụ: combo bắp + nước lớn có giá ưu đãi từ 79.000đ.'],
				['id' => 21, 'question' => 'Có thể nâng cỡ bắp/nước không?', 'answer' => 'Có. Khách hàng có thể nâng cỡ với giá thêm cho từng loại. Chi tiết giá xem tại quầy bắp hoặc website.'],
				['id' => 22, 'question' => 'Bắp có các hương vị nào?', 'answer' => 'Bắp ring bơ (butter), mặn (salt), phô mai (cheese), caramel, và một số hương vị đặc biệt theo mùa.'],
				['id' => 23, 'question' => 'Người ăn chay có thể chọn đồ gì?', 'answer' => 'Bắp mặn không dùng bơ, nước ngọt, nước ép trái cây, cà phê đen, trà. Vui lòng hỏi nhân viên quầy để chắc chắn.'],
			],
			'Công nghệ & Định dạng' => [
				['id' => 24, 'question' => '4DX là gì?', 'answer' => '4DX là công nghệ phòng chiếu với ghế chuyển động, phun nước, khí, mưa, và các hiệu ứng khác để tăng cảm giác thực tế khi xem phim.'],
				['id' => 25, 'question' => 'Dolby Atmos mang lại trải nghiệm gì khác?', 'answer' => 'Dolby Atmos cung cấp hệ thống âm thanh 3D đắm đuối với loa phía trên tạo cảm giác âm thanh bao quanh tuyệt vời.'],
				['id' => 26, 'question' => 'ScreenX là gì và khác gì so với phòng chiếu thường?', 'answer' => 'ScreenX có màn hình xung quanh 270 độ (thay vì 170 độ). Bạn sẽ nhìn thấy hình ảnh trên tường hai bên tạo cảm giác bao quanh hoàn toàn.'],
				['id' => 27, 'question' => '4K Laser có ưu điểm gì?', 'answer' => '4K Laser cho độ sáng, độ tương phản, và sắc độ màu tuyệt vời hơn so với chiếu phim thường, mang lại hình ảnh sắc nét và chân thực.'],
			],
			'Sự kiện & Chương trình đặc biệt' => [
				['id' => 28, 'question' => 'CGV Cinema có tổ chức sự kiện gì?', 'answer' => 'CGV Cinema tổ chức: buổi chiếu phim độc quyền, gặp gỡ diễn viên, buổi tiệc phim, sự kiện cộng đồng, và các chương trình khuyến mãi theo mùa.'],
				['id' => 29, 'question' => 'Có thể thuê rạp tổ chức sự kiện riêng không?', 'answer' => 'Có. CGV Cinema cho phép thuê phòng chiếu để tổ chức sinh nhật, team building, họp công ty, hoặc sự kiện riêng. Liên hệ 1900xxxx để tư vấn.'],
				['id' => 30, 'question' => 'Gói sinh nhật tại CGV Cinema bao gồm những gì?', 'answer' => 'Gói bao gồm: vé xem phim, phòng chờ riêng, combo bắp nước, bánh sinh nhật, và dịch vụ phục vụ chuyên nghiệp trong 2-3 giờ.'],
			],
		];

		$extraHead = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
					<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
					<link rel="stylesheet" href="' . BASE_URL . 'public/assets/css/faq.css">';
		$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';

		$this->view('layouts/main', [
			'title' => 'Hỏi đáp',
			'content' => 'pages/faq',
			'pageTitle' => 'Câu hỏi thường gặp',
			'grouped' => $grouped,
			'total' => 30,
			'extraHead' => $extraHead,
			'extraScripts' => $extraScripts,
		]);
	}

	public function privacy(): void {
		$this->view('layouts/main', [
			'title' => 'Chinh sach bao mat',
			'content' => 'home/privacy',
		]);
	}

	public function terms(): void {
		$this->view('layouts/main', [
			'title' => 'Dieu khoang su dung',
			'content' => 'home/terms',
		]);
	}

	public function regulations(): void {
		$this->view('layouts/main', [
			'title' => 'Quy dinh rap',
			'content' => 'home/regulations',
		]);
	}

	public function notFound(): void {
		$this->view('layouts/main', [
			'title' => '404',
			'content' => 'home/not_found',
		]);
	}
}
//         // Gọi View thông qua Layout chung của nhóm
//         $this->view('layouts/main', [
//             'title' => 'Trang Chủ - Đặt Vé Xem Phim',
//             'content' => 'home/index', // Nạp mảnh ghép index.php vào giữa layout
//             'nowShowing' => $nowShowing,
//             'comingSoon' => $comingSoon,
//             'keyword' => $keyword
//         ]);
//     }
// }
