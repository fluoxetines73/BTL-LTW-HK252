<?php
require_once ROOT . '/core/Controller.php';

class ProductController extends Controller {

    public function index() {
        $movieModel = $this->model('Movie');
        $movies = $movieModel->getAllMovies();

        $this->view('layouts/main', [
            'title' => 'Danh sách Phim',
            'content' => 'movies/index',
            'movies' => $movies,
        ]);
    }

    public function detail($id = null) {
        if (!$id) { 
            $this->redirect('home/index'); 
            return; 
        }
        
        $movie = $this->model('Movie')->getMovieById($id);
        $combos = $this->model('Combo')->getAllCombos();
        $allShowtimes = $this->model('Showtime')->getUpcomingShowtimesByMovieId($id);
        
        if (!$movie) { 
            $this->redirect('home/not_found'); 
            return; 
        }

        $this->view('layouts/main', [
            'title' => $movie['title'],
            'content' => 'product/detail',
            'movie' => $movie,
            'combos' => $combos,
            'allShowtimes' => $allShowtimes
        ]);
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home/index');
            return;
        }

        $movieId = $_POST['movie_id'] ?? 0;
        $showtimeId = $_POST['showtime_id'] ?? 0;
        $selectedSeats = $_POST['selected_seats'] ?? '';
        $ticketQty = (int)($_POST['ticket_qty'] ?? 0);
        $ticketPrice = (int)($_POST['ticket_price'] ?? 100000);
        $combosPost = $_POST['combos'] ?? [];

        $movieModel = $this->model('Movie');
        $showtimeModel = $this->model('Showtime');
        $comboModel = $this->model('Combo');

        $movie = $movieModel->getMovieById($movieId);
        $showtime = $showtimeModel->getShowtimeById($showtimeId);
        
        $selectedCombos = [];
        $comboTotal = 0;
        $allCombos = $comboModel->getAllCombos(); 
        
        foreach ($combosPost as $comboId => $qty) {
            if ($qty > 0) {
                foreach ($allCombos as $c) {
                    if ($c['id'] == $comboId) {
                        $subtotal = $qty * $c['price'];
                        $comboTotal += $subtotal;
                        $selectedCombos[] = [
                            'id' => $c['id'],
                            'name' => $c['name'],
                            'qty' => $qty,
                            'price' => $c['price'],
                            'subtotal' => $subtotal
                        ];
                        break;
                    }
                }
            }
        }

        $ticketTotal = $ticketQty * $ticketPrice;
        $grandTotal = $ticketTotal + $comboTotal;

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

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home/index');
            return;
        }

        $showtimeId = $_POST['showtime_id'] ?? 0;
        $selectedSeatsStr = $_POST['selected_seats'] ?? '';
        $grandTotal = $_POST['grand_total'] ?? 0;
        $selectedCombosPost = $_POST['selected_combos'] ?? [];
        $userId = $_SESSION['user']['id'] ?? 1;

        error_log("=== processPayment DEBUG ===");
        error_log("showtimeId: $showtimeId");
        error_log("selectedSeatsStr: $selectedSeatsStr");
        error_log("grandTotal: $grandTotal");
        error_log("userId: $userId");

        $bookingCode = 'CGV-' . strtoupper(bin2hex(random_bytes(4)));
        $bookingModel = $this->model('Booking');
        $showtimeModel = $this->model('Showtime');

        $bookingData = [
            'booking_code'    => $bookingCode,
            'user_id'        => $userId,
            'showtime_id'    => $showtimeId,
            'total_amount'   => $grandTotal,
            'discount_amount' => 0,
            'final_amount'   => $grandTotal,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'status'         => 'confirmed'
        ];

        $bookingId = $bookingModel->createBooking($bookingData);
        error_log("bookingId created: $bookingId");

        if ($bookingId) {
            $showtime = $showtimeModel->getShowtimeById($showtimeId);

            if (!$showtime) {
                error_log("ERROR: Showtime not found for ID: $showtimeId");
                $_SESSION['error'] = 'Suất chiếu không tồn tại!';
                $this->redirect('home/index');
                return;
            }

            error_log("Showtime found - room_id: " . $showtime['room_id'] . ", base_price: " . $showtime['base_price']);

            $basePrice = $showtime['base_price'];
            $roomId = $showtime['room_id'];

            error_log("Processing seats for booking: $bookingId, room_id: $roomId");

            $seatCodes = explode(',', $selectedSeatsStr);
            error_log("Selected seat codes: " . implode('|', $seatCodes));

            foreach ($seatCodes as $code) {
                $code = trim($code);
                if (empty($code)) continue;

                $row = substr($code, 0, 1);
                $col = (int)substr($code, 1);

                error_log("Searching for seat: room_id=$roomId, row='$row', col=$col (code=$code)");

                $db = Database::getInstance()->getPdo();
                $stmt = $db->prepare("SELECT id FROM seats WHERE room_id = :room_id AND row_label = :row AND col_number = :col LIMIT 1");
                $stmt->execute([':room_id' => $roomId, ':row' => $row, ':col' => $col]);
                $seatId = $stmt->fetchColumn();

                error_log("Seat query result: " . ($seatId ? "found (id=$seatId)" : "NOT FOUND"));

                if ($seatId) {
                    $bookingModel->createTicket([
                        'booking_id' => $bookingId,
                        'seat_id'    => $seatId,
                        'price'      => $basePrice
                    ]);
                    error_log("Ticket saved for seat_id=$seatId");
                } else {
                    error_log("ERROR: Seat not found: room_id=$roomId, row='$row', col=$col for code=$code");
                }
            }

            foreach ($selectedCombosPost as $comboId => $comboData) {
                list($qty, $price) = explode('|', $comboData);
                $bookingModel->createBookingCombo([
                    'booking_id' => $bookingId,
                    'combo_id'   => $comboId,
                    'quantity'   => $qty,
                    'price'      => $price
                ]);
            }

            $this->redirect('product/success');
        } else {
            echo "Lỗi: Không thể khởi tạo đơn hàng.";
        }
    }

    public function success() {
        $this->view('layouts/main', [
            'title' => 'Đặt vé thành công',
            'content' => 'product/success'
        ]);
    }
}