<?php
require_once ROOT . '/app/Models/Model.php';

class Order extends Model {
    
    /**
     * 1. Lấy danh sách tất cả đơn hàng (Gộp bảng bookings và users)
     */
    public function getAllOrders() {
        $sql = "SELECT b.*, u.full_name, u.email 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 2. Lấy thông tin chung của 1 đơn hàng cụ thể
     */
    public function getOrderById($id) {
        $sql = "SELECT b.*, u.full_name, u.email, u.phone, 
                       st.start_time, m.title as movie_title, r.name as room_name
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN showtimes st ON b.showtime_id = st.id
                JOIN movies m ON st.movie_id = m.id
                JOIN rooms r ON st.room_id = r.id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3. Lấy danh sách các vé (ghế) mà khách đã đặt trong đơn này
     */
    public function getOrderTickets($booking_id) {
        $sql = "SELECT t.*, s.row_label, s.col_number, st_type.name as seat_type
                FROM tickets t
                JOIN seats s ON t.seat_id = s.id
                JOIN seat_types st_type ON s.seat_type_id = st_type.id
                WHERE t.booking_id = :booking_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 4. Lấy danh sách các Combo bắp nước mà khách đã mua kèm
     */
    public function getOrderCombos($booking_id) {
        $sql = "SELECT bc.*, c.name, c.image
                FROM booking_combos bc
                JOIN combos c ON bc.combo_id = c.id
                WHERE bc.booking_id = :booking_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 5. Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}