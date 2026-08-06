<?php
class Showtime extends Model {
    protected string $table = 'showtimes';

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

    public function countShowtimes($keyword = '', $date = '') {
        $sql = "SELECT COUNT(*) FROM showtimes s 
                JOIN movies m ON s.movie_id = m.id 
                WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (m.title LIKE :kw1 OR s.id LIKE :kw2)";
            $params[':kw1'] = $params[':kw2'] = "%$keyword%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(s.start_time) = :date";
            $params[':date'] = $date;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function searchShowtimes($keyword = '', $date = '', $sort = 'newest', $limit = 10, $offset = 0) {
        $sql = "SELECT s.*, m.title as movie_title, r.name as room_name 
                FROM showtimes s
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms r ON s.room_id = r.id
                WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (m.title LIKE :kw1 OR s.id LIKE :kw2)";
            $params[':kw1'] = $params[':kw2'] = "%$keyword%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(s.start_time) = :date";
            $params[':date'] = $date;
        }

        switch ($sort) {
            case 'price_asc': $sql .= " ORDER BY s.base_price ASC, s.id ASC"; break;
            case 'price_desc': $sql .= " ORDER BY s.base_price DESC, s.id DESC"; break;
            case 'oldest': $sql .= " ORDER BY s.start_time ASC, s.id ASC"; break;
            default: $sql .= " ORDER BY s.start_time DESC, s.id DESC"; break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function updateShowtime($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET movie_id = :movie_id, 
                    room_id = :room_id, 
                    start_time = :start_time, 
                    end_time = :end_time, 
                    base_price = :base_price 
                WHERE id = :id";
                
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
    public function isRoomAvailable($roomId, $startTime, $endTime, $excludeShowtimeId = null): bool {
        $db = Database::getInstance()->getPdo();
        $sql = "SELECT COUNT(*) FROM showtimes 
                WHERE room_id = :room_id 
                AND status != 'cancelled' ";
        
        if ($excludeShowtimeId) {
            $sql .= " AND id != :exclude_id ";
        }

        $sql .= " AND ( :start_time < DATE_ADD(end_time, INTERVAL 10 MINUTE) )
                AND ( DATE_ADD(:end_time, INTERVAL 10 MINUTE) > start_time )";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindValue(':start_time', $startTime);
        $stmt->bindValue(':end_time', $endTime);
        if ($excludeShowtimeId) $stmt->bindValue(':exclude_id', $excludeShowtimeId, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchColumn() == 0;
    }
    public function isMovieAvailable($movieId, $startTime, $endTime, $excludeShowtimeId = null): bool {
        $db = Database::getInstance()->getPdo();
        $sql = "SELECT COUNT(*) FROM showtimes 
                WHERE movie_id = :movie_id 
                AND status != 'cancelled' ";
        
        if ($excludeShowtimeId) $sql .= " AND id != :exclude_id ";

        $sql .= " AND ( :start_time < DATE_ADD(end_time, INTERVAL 10 MINUTE) )
                AND ( DATE_ADD(:end_time, INTERVAL 10 MINUTE) > start_time )";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindValue(':start_time', $startTime);
        $stmt->bindValue(':end_time', $endTime);
        if ($excludeShowtimeId) $stmt->bindValue(':exclude_id', $excludeShowtimeId, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchColumn() == 0;
    }
    public function searchAdminShowtimes($keyword = '', $roomId = 'all', $sort = 'newest') {
        $db = Database::getInstance()->getPdo();
        
        $sql = "SELECT showtimes.*, movies.title as movie_title, rooms.name as room_name 
                FROM showtimes 
                JOIN movies ON showtimes.movie_id = movies.id 
                JOIN rooms ON showtimes.room_id = rooms.id 
                WHERE 1=1 ";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (movies.title LIKE :kw1 OR rooms.name LIKE :kw2) ";
            $params[':kw1'] = '%' . $keyword . '%';
            $params[':kw2'] = '%' . $keyword . '%';
        }

        if ($roomId !== 'all') {
            $sql .= " AND showtimes.room_id = :room_id ";
            $params[':room_id'] = $roomId;
        }

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

    public function deleteMultipleShowtimes(array $ids) {
        if (empty($ids)) return false;
        $db = Database::getInstance()->getPdo();
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $db->prepare("DELETE FROM showtimes WHERE id IN ($placeholders)");
        return $stmt->execute($ids);
    }
    public function getUpcomingShowtimesByMovieId($movieId) {
        $db = Database::getInstance()->getPdo();
        
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
    public function hasBookings($id) {
        $sql = "SELECT COUNT(*) FROM bookings WHERE showtime_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}