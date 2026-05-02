<?php
// Bắt buộc phải có thẻ mở PHP

class Booking extends Model {
    
    // Tên bảng chính mà Model này quản lý
    protected string $table = 'bookings';

    /**
     * Lấy danh sách các mã ghế (VD: A1, B2) đã bị chiếm chỗ trong một suất chiếu
     * Bao gồm: Ghế đã mua (tickets) VÀ Ghế đang bị khóa tạm thời (seat_reservations)
     * 
     * @param int $showtimeId ID của suất chiếu
     * @return array Mảng chứa các mã ghế (VD: ['A1', 'A2', 'C5'])
     */
    public function getOccupiedSeats($showtimeId) {
        try {
            // Câu lệnh SQL sử dụng UNION để gộp 2 kết quả:
            // 1. Ghế đã bán thành công (từ bảng tickets + bookings)
            // 2. Ghế đang bị khóa tạm thời (từ bảng seat_reservations)
            $sql = "
                -- Lấy ghế đã bán (không tính đơn hàng đã hủy)
                SELECT CONCAT(s.row_label, s.col_number) AS seat_code
                FROM seats s
                JOIN tickets t ON s.id = t.seat_id
                JOIN bookings b ON t.booking_id = b.id
                WHERE b.showtime_id = :showtime_id 
                  AND b.status != 'cancelled'
                
                UNION
                
                -- Lấy ghế đang bị khóa (chưa hết hạn)
                SELECT CONCAT(s.row_label, s.col_number) AS seat_code
                FROM seats s
                JOIN seat_reservations sr ON s.id = sr.seat_id
                WHERE sr.showtime_id = :showtime_id2 
                  AND sr.status = 'locked' 
                  AND sr.locked_until > NOW()
            ";

            $stmt = $this->db->prepare($sql);
            
            // Truyền ID suất chiếu vào cả 2 tham số
            $stmt->execute([
                ':showtime_id'  => $showtimeId,
                ':showtime_id2' => $showtimeId
            ]);
            
            // Trả về một mảng 1 chiều chứa trực tiếp các mã ghế
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $results ? $results : [];

        } catch (PDOException $e) {
            // Nếu có lỗi CSDL, trả về mảng rỗng để không làm sập giao diện
            error_log("Lỗi lấy ghế đã đặt: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tương lai: Hàm tạo đơn hàng mới sẽ được viết ở đây
     */
    // public function createBooking($data) { ... }
}