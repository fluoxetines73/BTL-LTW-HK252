<?php
require_once ROOT . '/core/Controller.php';

class ProductController extends Controller {

    /**
     * Hiển thị danh sách phim (Thay thế cho danh sách sản phẩm cũ)
     */
    public function index() {
        // Gọi model Movie để lấy danh sách phim
        $movieModel = $this->model('Movie');
        $movies = $movieModel->getAllMovies();

        // Truyền dữ liệu sang View
        $this->view('layouts/main', [
            'title' => 'Danh sách Phim',
            'content' => 'movies/index', // Cập nhật đường dẫn view phù hợp với dự án của bạn
            'movies' => $movies,
        ]);
    }

    /**
     * Trang chi tiết phim và chọn ghế (Đã chuẩn theo mã của bạn)
     */
    public function detail($id = null) {
        if (!$id) { 
            $this->redirect('home/index'); 
            return; 
        }
        
        $movie = $this->model('Movie')->getMovieById($id);
        $combos = $this->model('Combo')->getAllCombos();
        
        if (!$movie) { 
            $this->redirect('home/not_found'); 
            return; 
        }

        $this->view('layouts/main', [
            'title' => $movie['title'],
            'content' => 'product/detail',
            'movie' => $movie,
            'combos' => $combos
        ]);
    }

    /**
     * Xử lý dữ liệu đặt vé và hiển thị trang Hóa đơn (Checkout)
     */
    public function checkout() {
        // 1. Nếu người dùng truy cập trực tiếp bằng URL (không qua nút submit form), đẩy về trang chủ
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home/index');
            return;
        }

        // 2. Lấy dữ liệu từ form POST bên trang detail
        $movieId = $_POST['movie_id'] ?? 0;
        $showtimeId = $_POST['showtime_id'] ?? 0;
        $selectedSeats = $_POST['selected_seats'] ?? '';
        $ticketQty = (int)($_POST['ticket_qty'] ?? 0);
        $ticketPrice = (int)($_POST['ticket_price'] ?? 100000);
        $combosPost = $_POST['combos'] ?? []; // Mảng chứa [id_combo => số_lượng]

        // 3. Gọi Model để lấy thông tin chi tiết
        $movieModel = $this->model('Movie');
        $showtimeModel = $this->model('Showtime');
        $comboModel = $this->model('Combo');

        $movie = $movieModel->getMovieById($movieId);
        $showtime = $showtimeModel->getShowtimeById($showtimeId);
        
        // 4. Tính toán tiền Combo Bắp Nước
        $selectedCombos = [];
        $comboTotal = 0;
        $allCombos = $comboModel->getAllCombos(); 
        
        foreach ($combosPost as $comboId => $qty) {
            if ($qty > 0) {
                foreach ($allCombos as $c) {
                    if ($c['id'] == $comboId) {
                        $subtotal = $qty * $c['price'];
                        $comboTotal += $subtotal;
                        
                        // Lưu lại để hiển thị ra View
                        $selectedCombos[] = [
                            'name' => $c['name'],
                            'qty' => $qty,
                            'price' => $c['price'],
                            'subtotal' => $subtotal
                        ];
                        break; // Tìm thấy combo thì thoát vòng lặp con
                    }
                }
            }
        }

        // 5. Tính tổng cộng tiền thanh toán
        $ticketTotal = $ticketQty * $ticketPrice;
        $grandTotal = $ticketTotal + $comboTotal;

        // 6. Truyền toàn bộ dữ liệu đã xử lý sang trang giao diện checkout.php
        $this->view('layouts/main', [
            'content' => 'product/checkout',
            'title' => 'Xác nhận Đặt vé',
            'movie' => $movie,
            'showtime' => $showtime,
            'selectedSeats' => $selectedSeats,
            'ticketQty' => $ticketQty,
            'ticketTotal' => $ticketTotal,
            'selectedCombos' => $selectedCombos,
            'comboTotal' => $comboTotal,
            'grandTotal' => $grandTotal
        ]);
    }
}