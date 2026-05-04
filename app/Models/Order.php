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
     * Tìm kiếm, lọc và sắp xếp Đơn hàng cho Admin
     */
    /**
     * Tìm kiếm, lọc và sắp xếp Đơn hàng cho Admin
     */
    /**
     * Tìm kiếm, lọc và sắp xếp Đơn hàng cho Admin
     */
    /**
 * Tìm kiếm, lọc và sắp xếp Đơn hàng cho Admin
 * Đã sửa lỗi tên cột khớp với schema.sql (full_name)
 */
    public function searchAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all', $sort = 'newest') {
        $db = Database::getInstance()->getPdo();
        
        
        $sql = "SELECT b.*, u.full_name, u.email 
            FROM bookings b
            LEFT JOIN users u ON b.user_id = u.id
            WHERE 1=1 ";
        $params = [];

        // Lọc theo từ khóa (Mã đơn hàng, tên khách, email)
        if (!empty($keyword)) {
            // CẬP NHẬT: u.full_name thay vì u.fullname
            $sql .= " AND (b.booking_code LIKE :kw1 OR u.full_name LIKE :kw2 OR u.email LIKE :kw3) ";
            $params[':kw1'] = '%' . $keyword . '%';
            $params[':kw2'] = '%' . $keyword . '%';
            $params[':kw3'] = '%' . $keyword . '%';
        }

        // Lọc theo trạng thái xác nhận (pending, confirmed, cancelled, completed)
        if ($status !== 'all') {
            $sql .= " AND b.status = :status ";
            $params[':status'] = $status;
        }

        // Lọc theo trạng thái thanh toán (pending, paid, failed, refunded)
        if ($paymentStatus !== 'all') {
            $sql .= " AND b.payment_status = :payment_status ";
            $params[':payment_status'] = $paymentStatus;
        }

        // Sắp xếp dữ liệu
        switch ($sort) {
            case 'oldest': $sql .= " ORDER BY b.created_at ASC "; break;
            case 'total_desc': $sql .= " ORDER BY b.final_amount DESC "; break;
            case 'newest':
            default: $sql .= " ORDER BY b.created_at DESC "; break;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa hàng loạt Đơn hàng
     */
    public function deleteMultipleOrders(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        // Cảnh báo: Tùy thuộc vào thiết lập CSDL của bạn (ON DELETE CASCADE), 
        // có thể cần viết lệnh xóa tickets và booking_combos trước khi xóa bookings.
        $stmt = $db->prepare("DELETE FROM bookings WHERE id IN ($placeholders)");
        return $stmt->execute($ids);
    }
}