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
        $combosPost = $_POST['combos'] ?? []; 

        // Khởi tạo các Model cần thiết
        $movieModel = $this->model('Movie');
        $showtimeModel = $this->model('Showtime');
        $comboModel = $this->model('Combo');

        
        $movie = $movieModel->getMovieById($movieId);
        $showtime = $showtimeModel->getShowtimeById($showtimeId);
        
        if (!$showtime) {
            $_SESSION['error'] = "Suất chiếu không tồn tại!";
            $this->redirect('home/index');
            return;
        }

        
        $ticketPrice = (int)$showtime['base_price'];

        
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

        
        $showtimeId = (int)($_POST['showtime_id'] ?? 0);
        $selectedSeatsStr = trim($_POST['selected_seats'] ?? '');
        $selectedCombosPost = $_POST['selected_combos'] ?? []; 
        $userId = $_SESSION['user']['id'] ?? 1;

        // Khởi tạo các Model cần thiết
        $bookingModel = $this->model('Booking');
        $showtimeModel = $this->model('Showtime');
        $comboModel = $this->model('Combo');

        
        $showtime = $showtimeModel->getShowtimeById($showtimeId);
        if (!$showtime) {
            $_SESSION['error'] = 'Suất chiếu không tồn tại!';
            $this->redirect('home/index');
            return;
        }
        $realTicketPrice = (float)$showtime['base_price'];

        
        $seatCodes = array_filter(explode(',', $selectedSeatsStr));
        $ticketTotal = count($seatCodes) * $realTicketPrice;

        
        $comboTotal = 0;
        $allCombosFromDB = $comboModel->getAllCombos(); 
        $validatedCombos = [];

        foreach ($selectedCombosPost as $comboId => $comboData) {
            
            list($qty, $clientPrice) = explode('|', $comboData);
            $qty = (int)$qty;

            if ($qty > 0) {
                foreach ($allCombosFromDB as $dbCombo) {
                    if ($dbCombo['id'] == $comboId) {
                        $subtotal = $qty * (float)$dbCombo['price']; 
                        $comboTotal += $subtotal;
                        $validatedCombos[] = [
                            'id' => $dbCombo['id'],
                            'qty' => $qty,
                            'price' => $dbCombo['price']
                        ];
                        break;
                    }
                }
            }
        }

        
        $finalAmount = $ticketTotal + $comboTotal;

        
        $bookingCode = 'CGV-' . strtoupper(bin2hex(random_bytes(4)));
        $bookingData = [
            'booking_code'    => $bookingCode,
            'user_id'         => $userId,
            'showtime_id'     => $showtimeId,
            'total_amount'    => $finalAmount,
            'discount_amount' => 0,
            'final_amount'    => $finalAmount,
            'payment_method'  => 'cash',
            'payment_status'  => 'pending',
            'status'          => 'confirmed'
        ];

        $bookingId = $bookingModel->createBooking($bookingData);

        if ($bookingId) {
            $roomId = $showtime['room_id'];

            
            foreach ($seatCodes as $code) {
                $code = trim($code);
                $row = substr($code, 0, 1);
                $col = (int)substr($code, 1);

                
                $db = Database::getInstance()->getPdo();
                $stmt = $db->prepare("SELECT id FROM seats WHERE room_id = :room_id AND row_label = :row AND col_number = :col LIMIT 1");
                $stmt->execute([':room_id' => $roomId, ':row' => $row, ':col' => $col]);
                $seatId = $stmt->fetchColumn();

                if ($seatId) {
                    $res = $bookingModel->createTicket([
                        'booking_id' => $bookingId,
                        'seat_id'    => $seatId,
                        'price'      => $realTicketPrice
                    ]);
                    if (!$res) error_log("Lỗi: Không thể insert vào bảng tickets cho ghế $code");
                } else {
                   
                    error_log("Lỗi: Không tìm thấy seat_id cho ghế $code trong room $roomId");
                }
            }

            
            foreach ($validatedCombos as $vc) {
                $bookingModel->createBookingCombo([
                    'booking_id' => $bookingId,
                    'combo_id'   => $vc['id'],
                    'quantity'   => $vc['qty'],
                    'price'      => $vc['price'] 
                ]);
            }

            $this->redirect('product/success');
        } else {
            die("Lỗi: Không thể khởi tạo đơn hàng.");
        }
    }

    public function success() {
        $this->view('layouts/main', [
            'title' => 'Đặt vé thành công',
            'content' => 'product/success'
        ]);
    }
}