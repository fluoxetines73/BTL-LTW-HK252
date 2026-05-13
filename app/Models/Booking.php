<?php
class Booking extends Model {
    protected string $table = 'bookings';

    public function getOccupiedSeats($showtimeId) {
        $sql = "
            SELECT CONCAT(s.row_label, s.col_number) AS seat_code
            FROM seats s
            JOIN tickets t ON s.id = t.seat_id
            JOIN bookings b ON t.booking_id = b.id
            WHERE b.showtime_id = :showtime_id AND b.status != 'cancelled'

            UNION

            SELECT CONCAT(s.row_label, s.col_number) AS seat_code
            FROM seats s
            JOIN seat_reservations sr ON s.id = sr.seat_id
            WHERE sr.showtime_id = :showtime_id AND sr.status = 'locked' AND sr.locked_until > NOW()
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':showtime_id' => $showtimeId,
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
}