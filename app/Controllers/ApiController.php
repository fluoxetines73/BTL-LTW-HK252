<?php
require_once ROOT . '/core/Controller.php';

class ApiController extends Controller {
    public function getShowtimes() {
        header('Content-Type: application/json');
        $movieId = $_GET['movie_id'] ?? 0;
        $date = $_GET['date'] ?? '';

        if ($movieId && $date) {
            $showtimes = $this->model('Showtime')->getShowtimesByMovieAndDate($movieId, $date);
            echo json_encode(['success' => true, 'data' => $showtimes]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số']);
        }
    }

    public function getOccupiedSeats() {
        $showtimeId = $_GET['showtime_id'] ?? 0;
        // Lấy ID người dùng từ session
        $userId = $_SESSION['auth_user']['id'] ?? 0; 
        
        $bookingModel = $this->model('Booking');
        $data = $bookingModel->getOccupiedSeats($showtimeId, $userId);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
    }
    public function updateReservation() {
        header('Content-Type: application/json');
        
        // Đọc dữ liệu JSON từ request body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $showtimeId = $data['showtime_id'] ?? 0;
        $seatCode   = $data['seat_code'] ?? ''; // VD: 'A1', 'B5'
        $action     = $data['action'] ?? '';    // 'lock' hoặc 'unlock'
        $userId = $_SESSION['auth_user']['id'] ?? 0;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để chọn ghế']);
            return;
        }

        if ($showtimeId && $seatCode && $action) {
            $bookingModel = $this->model('Booking');
            
            // 1. Tìm seat_id từ seatCode (A1 -> ID trong DB)
            // Lưu ý: Bạn cần biết room_id của suất chiếu này
            $showtime = $this->model('Showtime')->getShowtimeById($showtimeId);
            $roomId = $showtime['room_id'];

            // Tách mã ghế: A1 -> Row: A, Col: 1
            $row = substr($seatCode, 0, 1);
            $col = (int)substr($seatCode, 1);

            $db = Database::getInstance()->getPdo();
            $stmt = $db->prepare("SELECT id FROM seats WHERE room_id = :room_id AND row_label = :row AND col_number = :col LIMIT 1");
            $stmt->execute([':room_id' => $roomId, ':row' => $row, ':col' => $col]);
            $seatId = $stmt->fetchColumn();

            if ($seatId) {
                // 2. Gọi hàm model đã viết ở bước trước
                $result = $bookingModel->updateSeatReservation($showtimeId, $seatId, $userId, $action);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy ghế trong hệ thống']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu đầu vào']);
        }
    }
}