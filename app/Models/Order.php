<?php
require_once ROOT . '/app/Models/Model.php';

class Order extends Model {
    
    public function getAllOrders() {
        $sql = "SELECT b.*, u.full_name, u.email 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getOrderTickets($bookingId) {
        $sql = "SELECT t.*, s.row_label, s.col_number 
                FROM tickets t
                JOIN seats s ON t.seat_id = s.id
                WHERE t.booking_id = :booking_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all') {
        // Đã thêm JOIN tới showtimes và movies
        $sql = "SELECT COUNT(*) FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                JOIN showtimes st ON b.showtime_id = st.id
                JOIN movies m ON st.movie_id = m.id
                WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            // Nâng cấp: Cho phép tìm kiếm đơn hàng theo cả Tên Phim (m.title)
            $sql .= " AND (b.booking_code LIKE :kw1 OR u.full_name LIKE :kw2 OR u.email LIKE :kw3 OR m.title LIKE :kw4)";
            $params[':kw1'] = $params[':kw2'] = $params[':kw3'] = $params[':kw4'] = "%$keyword%";
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

    public function searchAdminOrders($keyword = '', $status = 'all', $paymentStatus = 'all', $sort = 'newest', $limit = 10, $offset = 0) {
        // Đã thêm JOIN tới showtimes và movies, SELECT thêm m.title
        $sql = "SELECT b.*, u.full_name, u.email, m.title as movie_title
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                JOIN showtimes st ON b.showtime_id = st.id
                JOIN movies m ON st.movie_id = m.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($keyword)) {
            // Nâng cấp: Cho phép tìm kiếm đơn hàng theo cả Tên Phim (m.title)
            $sql .= " AND (b.booking_code LIKE :kw1 OR u.full_name LIKE :kw2 OR u.email LIKE :kw3 OR m.title LIKE :kw4)";
            $params[':kw1'] = $params[':kw2'] = $params[':kw3'] = $params[':kw4'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND b.status = :status";
            $params[':status'] = $status;
        }
        if ($paymentStatus !== 'all') {
            $sql .= " AND b.payment_status = :p_status";
            $params[':p_status'] = $paymentStatus;
        }

        // Sắp xếp theo ID làm phụ để tránh trùng lặp thứ tự
        $orderBy = match($sort) {
            'oldest' => 'b.created_at ASC, b.id ASC',
            'price_desc' => 'b.final_amount DESC, b.id DESC',
            'price_asc' => 'b.final_amount ASC, b.id ASC',
            default => 'b.created_at DESC, b.id DESC'
        };
        $sql .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteMultipleOrders(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $db->prepare("DELETE FROM tickets WHERE booking_id IN ($placeholders)")->execute($ids);
            $db->prepare("DELETE FROM booking_combos WHERE booking_id IN ($placeholders)")->execute($ids);
            $db->prepare("DELETE FROM bookings WHERE id IN ($placeholders)")->execute($ids);
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public function cancelMultipleOrders(array $ids) {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE bookings SET status = 'cancelled' WHERE id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($ids);
    }
}