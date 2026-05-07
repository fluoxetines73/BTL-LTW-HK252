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
    // 1. Hàm đếm tổng số đơn hàng để tính số trang
    public function countAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all') {
        $sql = "SELECT COUNT(*) FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (b.booking_code LIKE :kw1 OR u.full_name LIKE :kw2 OR u.email LIKE :kw3)";
            $params[':kw1'] = $params[':kw2'] = $params[':kw3'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND b.status = :status";
            $params[':status'] = $status;
        }
        if ($paymentStatus !== 'all') {
            $sql .= " AND b.payment_status = :p_status";
            $params[':p_status'] = $paymentStatus;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // 2. Cập nhật hàm search có phân trang (Fix lỗi tham số trùng tên)
    public function searchAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all', $sort = 'newest', $limit = 10, $offset = 0) {
        $sql = "SELECT b.*, u.full_name, u.email 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (b.booking_code LIKE :kw1 OR u.full_name LIKE :kw2 OR u.email LIKE :kw3)";
            $params[':kw1'] = $params[':kw2'] = $params[':kw3'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND b.status = :status";
            $params[':status'] = $status;
        }
        if ($paymentStatus !== 'all') {
            $sql .= " AND b.payment_status = :p_status";
            $params[':p_status'] = $paymentStatus;
        }

        $orderBy = match($sort) {
            'oldest' => 'b.created_at ASC',
            'price_desc' => 'b.final_amount DESC',
            'price_asc' => 'b.final_amount ASC',
            default => 'b.created_at DESC'
        };
        $sql .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa hàng loạt Đơn hàng[cite: 5]
     * Lưu ý: CSDL cần thiết lập ON DELETE CASCADE cho tickets và booking_combos
     */
    public function deleteMultipleOrders(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            // 1. Xóa vé và combo bắp nước đi kèm đơn hàng
            $db->prepare("DELETE FROM tickets WHERE booking_id IN ($placeholders)")->execute($ids);
            $db->prepare("DELETE FROM booking_combos WHERE booking_id IN ($placeholders)")->execute($ids);
            
            // 2. Xóa đơn hàng chính
            $db->prepare("DELETE FROM bookings WHERE id IN ($placeholders)")->execute($ids);
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}