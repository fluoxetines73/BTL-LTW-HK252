<?php
require_once ROOT . '/app/Models/Model.php';

class Movie extends Model {
    protected string $table = 'movies';

    /**
     * Lấy toàn bộ danh sách phim (kèm phân trang hoặc tìm kiếm nếu cần)
     */
    public function getAllMovies($keyword = '') {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        // Nếu có từ khóa tìm kiếm
        if (!empty($keyword)) {
            $sql .= " WHERE title LIKE :keyword OR director LIKE :keyword";
            $params[':keyword'] = "%{$keyword}%";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết 1 bộ phim
     */
    public function getMovieById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm một bộ phim mới
     */
    public function createMovie($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, slug, description, director, cast, duration_min, release_date, age_rating, status, poster) 
                VALUES 
                (:title, :slug, :description, :director, :cast, :duration_min, :release_date, :age_rating, :status, :poster)";
        
        $stmt = $this->db->prepare($sql);
        
        // Bạn có thể dùng vòng lặp để bind tự động, nhưng ở mức cơ bản, ta gán trực tiếp cho rõ ràng
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':director', $data['director']);
        $stmt->bindParam(':cast', $data['cast']);
        $stmt->bindParam(':duration_min', $data['duration_min']);
        $stmt->bindParam(':release_date', $data['release_date']);
        $stmt->bindParam(':age_rating', $data['age_rating']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':poster', $data['poster']); // Tên file ảnh đã upload

        return $stmt->execute();
    }

    /**
     * Cập nhật thông tin phim
     */
    public function updateMovie($id, $data) {
        // Cập nhật cơ bản không bao gồm poster để tránh rắc rối khi user không đổi ảnh
        $sql = "UPDATE {$this->table} SET 
                title = :title, slug = :slug, description = :description, director = :director, 
                cast = :cast, duration_min = :duration_min, release_date = :release_date, 
                age_rating = :age_rating, status = :status 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':director', $data['director']);
        $stmt->bindParam(':cast', $data['cast']);
        $stmt->bindParam(':duration_min', $data['duration_min']);
        $stmt->bindParam(':release_date', $data['release_date']);
        $stmt->bindParam(':age_rating', $data['age_rating']);
        $stmt->bindParam(':status', $data['status']);

        return $stmt->execute();
    }

    /**
     * Xóa một bộ phim
     */
    public function deleteMovie($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}