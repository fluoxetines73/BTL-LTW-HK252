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
    /**
 * Lấy danh sách vé (ghế) của một đơn hàng
 * Giải pháp: JOIN với bảng seats để lấy row_label và col_number
 */
/**
 * Lấy danh sách vé chi tiết của một đơn hàng
 * Giải pháp: JOIN bảng tickets với bảng seats để lấy nhãn hàng và số cột
 */
    public function getOrderTickets($bookingId) {
        $db = Database::getInstance()->getPdo();
        // JOIN bảng tickets với bảng seats để lấy nhãn hàng và số cột[cite: 9]
        $sql = "SELECT t.*, s.row_label, s.col_number 
                FROM tickets t
                JOIN seats s ON t.seat_id = s.id
                WHERE t.booking_id = :booking_id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId]);
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
/**
     * Tìm kiếm, lọc và sắp xếp Đơn hàng dành cho Admin
     * Kết hợp: Tìm kiếm từ khóa, Lọc trạng thái đơn, Lọc trạng thái thanh toán và Sắp xếp
     */
    public function searchAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all', $sort = 'newest') {
        $sql = "SELECT b.*, u.full_name, u.email 
                FROM bookings b 
                LEFT JOIN users u ON b.user_id = u.id";
        
        $params = [];
        $conditions = [];

        // 1. Lọc theo từ khóa (Mã đơn, Tên khách, Email)
        if ($keyword !== '') {
            $conditions[] = "(b.booking_code LIKE :kw OR u.full_name LIKE :kw OR u.email LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        // 2. Lọc theo trạng thái Đơn hàng (pending, confirmed, etc.)
        if ($status !== 'all') {
            $conditions[] = "b.status = :status";
            $params[':status'] = $status;
        }

        // 3. Lọc theo trạng thái Thanh toán (pending, paid, failed, etc.)
        if ($paymentStatus !== 'all') {
            $conditions[] = "b.payment_status = :payment_status";
            $params[':payment_status'] = $paymentStatus;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // 4. Sắp xếp dữ liệu (Kết hợp các tiêu chí từ cả hai bên)[cite: 5]
        $orderBy = match($sort) {
            'oldest'     => 'b.created_at ASC',
            'total_desc' => 'b.final_amount DESC',
            'price_asc'  => 'b.final_amount ASC',
            default      => 'b.created_at DESC'
        };
        $sql .= " ORDER BY $orderBy";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa hàng loạt Đơn hàng[cite: 5]
     * Lưu ý: CSDL cần thiết lập ON DELETE CASCADE cho tickets và booking_combos
     */
    public function deleteMultipleOrders(array $ids) {
        if (empty($ids)) return false;
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM bookings WHERE id IN ($placeholders)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
    }
}