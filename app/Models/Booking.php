<?php
class Booking extends Model {
    protected string $table = 'bookings';

    public function getOccupiedSeats($showtimeId, $currentUserId = 0) {
        $sql = "SELECT DISTINCT CONCAT(s.row_label, s.col_number) as seat_code
                FROM seats s
                LEFT JOIN tickets t ON s.id = t.seat_id
                LEFT JOIN bookings b ON t.booking_id = b.id
                LEFT JOIN seat_reservations sr ON s.id = sr.seat_id
                WHERE (b.showtime_id = :sid1 AND b.status != 'cancelled')
                OR (sr.showtime_id = :sid2 AND sr.locked_until > NOW() AND sr.user_id != :uid)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sid1' => $showtimeId,
            ':sid2' => $showtimeId,
            ':uid'  => $currentUserId // Truyền ID người dùng hiện tại vào đây
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createBooking($data) {
        $sql = "INSERT INTO {$this->table}
                (booking_code, user_id, showtime_id, total_amount, discount_amount, final_amount, payment_method, payment_status, status)
                VALUES
                (:booking_code, :user_id, :showtime_id, :total_amount, :discount_amount, :final_amount, :payment_method, :payment_status, :status)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':booking_code'    => $data['booking_code'],
            ':user_id'         => $data['user_id'],
            ':showtime_id'     => $data['showtime_id'],
            ':total_amount'    => $data['total_amount'],
            ':discount_amount' => $data['discount_amount'],
            ':final_amount'    => $data['final_amount'],
            ':payment_method'  => $data['payment_method'],
            ':payment_status'  => $data['payment_status'],
            ':status'          => $data['status']
        ]);

        return $this->db->lastInsertId();
    }

    public function createTicket($data) {
        $sql = "INSERT INTO tickets (booking_id, seat_id, price) VALUES (:booking_id, :seat_id, :price)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':booking_id' => $data['booking_id'],
            ':seat_id'    => $data['seat_id'],
            ':price'      => $data['price']
        ]);
    }

    public function createBookingCombo($data) {
        $sql = "INSERT INTO booking_combos (booking_id, combo_id, quantity, price) VALUES (:booking_id, :combo_id, :quantity, :price)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':booking_id' => $data['booking_id'],
            ':combo_id'   => $data['combo_id'],
            ':quantity'   => $data['quantity'],
            ':price'      => $data['price']
        ]);
    }

    public function updateSeatReservation($showtimeId, $seatId, $userId, $action) {
        if ($action === 'lock') {
            $sql = "INSERT INTO seat_reservations (showtime_id, seat_id, user_id, status, locked_until) 
                    VALUES (:showtime_id, :seat_id, :user_id, 'locked', DATE_ADD(NOW(), INTERVAL 5 MINUTE))
                    ON DUPLICATE KEY UPDATE status='locked', locked_until=DATE_ADD(NOW(), INTERVAL 5 MINUTE)";
        } else {
            $sql = "DELETE FROM seat_reservations WHERE showtime_id = :showtime_id AND seat_id = :seat_id AND user_id = :user_id";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':showtime_id' => $showtimeId,
            ':seat_id'     => $seatId,
            ':user_id'     => $userId
        ]);
    }
}