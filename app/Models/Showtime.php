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
}