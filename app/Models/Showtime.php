<?php
class Showtime extends Model {
    protected string $table = 'showtimes';

    // Cho Frontend: Lấy suất chiếu theo phim và ngày
    public function getShowtimesByMovieAndDate($movieId, $date) {
        $sql = "SELECT s.*, r.name as room_name 
                FROM {$this->table} s
                JOIN rooms r ON s.room_id = r.id
                WHERE s.movie_id = :movie_id 
                AND DATE(s.start_time) = :date 
                AND s.status = 'scheduled'
                ORDER BY s.start_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':movie_id' => $movieId, ':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cho Admin: Lấy tất cả suất chiếu
    public function getAllShowtimes() {
        $sql = "SELECT s.*, m.title as movie_title, r.name as room_name 
                FROM {$this->table} s
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms r ON s.room_id = r.id
                ORDER BY s.start_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cho Admin: Thêm suất chiếu mới
    public function createShowtime($data) {
        $sql = "INSERT INTO {$this->table} (movie_id, room_id, start_time, end_time, base_price, status) 
                VALUES (:movie_id, :room_id, :start_time, :end_time, :base_price, 'scheduled')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    public function getShowtimeById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật dữ liệu suất chiếu
    public function updateShowtime($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET movie_id = :movie_id, 
                    room_id = :room_id, 
                    start_time = :start_time, 
                    end_time = :end_time, 
                    base_price = :base_price 
                WHERE id = :id";
                
        // Thêm ID vào mảng data để thực thi
        $data[':id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    public function deleteShowtime($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function getSeatIdByCode($row, $col) {
        $sql = "SELECT id FROM seats WHERE row_label = :row AND col_number = :col LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':row' => $row, ':col' => $col]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
    /**
     * Kiểm tra xem phòng chiếu có trống trong khoảng thời gian chỉ định không
     * Đã bao gồm 10 phút dọn dẹp sau khi phim kết thúc.
     */
    public function isRoomAvailable($roomId, $startTime, $endTime, $excludeShowtimeId = null): bool {
        $db = Database::getInstance()->getPdo();
        
        // Logic: Hai khoảng thời gian (A và B) bị trùng nhau KHI VÀ CHỈ KHI:
        // Bắt đầu A < Kết thúc B (đã tính 10p dọn dẹp) VÀ Kết thúc A (đã tính 10p dọn dẹp) > Bắt đầu B
        $sql = "SELECT COUNT(*) FROM showtimes 
                WHERE room_id = :room_id ";
        
        // Nếu đang update, cần loại trừ chính suất chiếu đang được sửa
        if ($excludeShowtimeId) {
            $sql .= " AND id != :exclude_id ";
        }

        $sql .= " AND ( :start_time < DATE_ADD(end_time, INTERVAL 10 MINUTE) )
                  AND ( DATE_ADD(:end_time, INTERVAL 10 MINUTE) > start_time )";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindValue(':start_time', $startTime);
        $stmt->bindValue(':end_time', $endTime);
        
        if ($excludeShowtimeId) {
            $stmt->bindValue(':exclude_id', $excludeShowtimeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Nếu count == 0 nghĩa là phòng trống, trả về true
        return $count == 0;
    }
    /**
     * Kiểm tra xem bộ phim này có đang được chiếu ở phòng khác trong cùng khung giờ hay không
     * Đã bao gồm 10 phút an toàn.
     */
    public function isMovieAvailable($movieId, $startTime, $endTime, $excludeShowtimeId = null): bool {
        $db = Database::getInstance()->getPdo();
        
        $sql = "SELECT COUNT(*) FROM showtimes 
                WHERE movie_id = :movie_id ";
        
        if ($excludeShowtimeId) {
            $sql .= " AND id != :exclude_id ";
        }

        $sql .= " AND ( :start_time < DATE_ADD(end_time, INTERVAL 10 MINUTE) )
                  AND ( DATE_ADD(:end_time, INTERVAL 10 MINUTE) > start_time )";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindValue(':start_time', $startTime);
        $stmt->bindValue(':end_time', $endTime);
        
        if ($excludeShowtimeId) {
            $stmt->bindValue(':exclude_id', $excludeShowtimeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count == 0;
    }
    /**
     * Tìm kiếm, lọc và sắp xếp Suất chiếu cho Admin
     */
    public function searchAdminShowtimes($keyword = '', $roomId = 'all', $sort = 'newest') {
        $db = Database::getInstance()->getPdo();
        
        // Dùng JOIN để lấy tên phim và tên phòng chiếu
        $sql = "SELECT showtimes.*, movies.title as movie_title, rooms.name as room_name 
                FROM showtimes 
                JOIN movies ON showtimes.movie_id = movies.id 
                JOIN rooms ON showtimes.room_id = rooms.id 
                WHERE 1=1 ";
        $params = [];

        // Lọc theo từ khóa (Tìm theo tên phim hoặc tên phòng)
        if (!empty($keyword)) {
            $sql .= " AND (movies.title LIKE :kw1 OR rooms.name LIKE :kw2) ";
            $params[':kw1'] = '%' . $keyword . '%';
            $params[':kw2'] = '%' . $keyword . '%';
        }

        // Lọc theo Phòng chiếu cụ thể
        if ($roomId !== 'all') {
            $sql .= " AND showtimes.room_id = :room_id ";
            $params[':room_id'] = $roomId;
        }

        // Sắp xếp
        switch ($sort) {
            case 'time_asc':
                $sql .= " ORDER BY showtimes.start_time ASC ";
                break;
            case 'time_desc':
                $sql .= " ORDER BY showtimes.start_time DESC ";
                break;
            case 'oldest':
                $sql .= " ORDER BY showtimes.id ASC ";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY showtimes.id DESC ";
                break;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa hàng loạt Suất chiếu
     */
    public function deleteMultipleShowtimes(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $db->prepare("DELETE FROM showtimes WHERE id IN ($placeholders)");
        return $stmt->execute($ids);
    }
    /**
     * Lấy tất cả suất chiếu sắp tới của một bộ phim cụ thể
     */
    public function getUpcomingShowtimesByMovieId($movieId) {
        $db = Database::getInstance()->getPdo();
        
        // Lấy các suất chiếu trong tương lai và kết nối với bảng rooms để lấy tên phòng
        $sql = "SELECT s.*, r.name as room_name 
                FROM showtimes s
                JOIN rooms r ON s.room_id = r.id
                WHERE s.movie_id = :movie_id AND s.start_time >= NOW()
                ORDER BY s.start_time ASC";
                
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}