<?php
require_once ROOT . '/app/Models/Model.php';

class Movie extends Model {
    protected string $table = 'movies';

    /**
     * Lấy toàn bộ danh sách phim (kèm phân trang hoặc tìm kiếm nếu cần)
     */
    public function getAllMovies($keyword = '') {
        // Sử dụng bí danh 'm' cho bảng phim hiện tại
        // Dùng LEFT JOIN để kết nối các bảng và GROUP_CONCAT để gộp tên thể loại
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genre_names 
                FROM {$this->table} m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id";
        
        $params = [];

        // Nếu có từ khóa tìm kiếm
        if (!empty($keyword)) {
            // Cần thêm bí danh 'm.' phía trước cột title và director để tránh lỗi "ambiguous" (trùng tên cột)
            $sql .= " WHERE m.title LIKE :keyword OR m.director LIKE :keyword";
            $params[':keyword'] = "%{$keyword}%";
        }

        // Rất quan trọng: Bắt buộc phải gom nhóm (GROUP BY) theo ID phim 
        // trước khi sắp xếp (ORDER BY) để hàm GROUP_CONCAT gộp đúng dữ liệu
        $sql .= " GROUP BY m.id ORDER BY m.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        // Trả về toàn bộ dữ liệu dưới dạng mảng (Tôi đã hoàn thiện nốt chữ 'r' đang viết dở của bạn)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết 1 bộ phim
     */
    /**
     * Lấy thông tin chi tiết 1 bộ phim kèm theo Tên thể loại
     */
    public function getMovieById($id) {
        // Dùng LEFT JOIN và GROUP_CONCAT để lấy chuỗi thể loại (vd: "Hành Động, Hài")
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genre_names 
                FROM {$this->table} m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                WHERE m.id = :id
                GROUP BY m.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
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
     * Thêm một bộ phim mới với cả poster và banner
     */
    public function createMovieWithImages($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, slug, description, director, cast, duration_min, release_date, age_rating, status, poster, banner) 
                VALUES 
                (:title, :slug, :description, :director, :cast, :duration_min, :release_date, :age_rating, :status, :poster, :banner)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':director', $data['director']);
        $stmt->bindParam(':cast', $data['cast']);
        $stmt->bindParam(':duration_min', $data['duration_min']);
        $stmt->bindParam(':release_date', $data['release_date']);
        $stmt->bindParam(':age_rating', $data['age_rating']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':poster', $data['poster']);
        $stmt->bindParam(':banner', $data['banner']);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
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
     * Cập nhật thông tin phim với cả poster và banner
     */
    public function updateMovieWithImages($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = :title, slug = :slug, description = :description, director = :director, 
                cast = :cast, duration_min = :duration_min, release_date = :release_date, 
                age_rating = :age_rating, status = :status, poster = :poster, banner = :banner 
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
        $stmt->bindParam(':poster', $data['poster']);
        $stmt->bindParam(':banner', $data['banner']);

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
    // Hàm lấy danh sách phim theo trạng thái (Đang chiếu / Sắp chiếu)
    public function getMoviesByStatus($status) {
        $stmt = $this->db->prepare("SELECT * FROM movies WHERE status = :status ORDER BY release_date DESC");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hàm tìm kiếm phim theo tên
    public function searchMovies($keyword) {
        $keyword = "%{$keyword}%";
        // Chỉ tìm những phim đang chiếu hoặc sắp chiếu
        $sql = "SELECT * FROM movies 
                WHERE (status = 'now_showing' OR status = 'coming_soon') 
                AND title LIKE :keyword 
                ORDER BY release_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getGenreSlugsByMovieId($movieId) {
        try {
            // Giả định bạn có bảng 'movie_genres' nối giữa 'movies' và 'genres'
            // Nếu cấu trúc DB của bạn khác, hãy điều chỉnh lại câu SQL này nhé
            $sql = "SELECT g.slug FROM genres g 
                    JOIN movie_genres mg ON g.id = mg.genre_id 
                    WHERE mg.movie_id = :movie_id";
            
            // Tùy thuộc vào cách bạn setup PDO trong core/Model.php
            // Có thể là $this->db->prepare($sql) hoặc Database::getInstance()->getPdo()->prepare()
            $stmt = $this->db->prepare($sql); 
            $stmt->execute(['movie_id' => $movieId]);
            
            // Lấy ra một mảng chỉ chứa giá trị của cột 'slug' (VD: ['hai', 'hanh-dong'])
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $result ? $result : [];
        } catch (PDOException $e) {
            // Trả về mảng rỗng nếu bảng chưa tồn tại để tránh sập trang web (Lỗi 500)
            return []; 
        }
    }
    /**
     * Đồng bộ thể loại phim
     * Xóa các thể loại cũ và chèn các thể loại mới được chọn
     */
    public function syncMovieGenres($movieId, $genreSlugs) {
        // 1. Xóa các thể loại cũ của phim này
        $stmtDelete = $this->db->prepare("DELETE FROM movie_genres WHERE movie_id = ?");
        $stmtDelete->execute([$movieId]);

        // Nếu không có thể loại nào được chọn thì dừng lại
        if (empty($genreSlugs)) return true;

        // 2. Lấy ID của các thể loại dựa trên slug
        // Tạo chuỗi dấu '?' để dùng cho toán tử IN
        $placeholders = implode(',', array_fill(0, count($genreSlugs), '?'));
        $stmtGetIds = $this->db->prepare("SELECT id FROM genres WHERE slug IN ($placeholders)");
        $stmtGetIds->execute($genreSlugs);
        $genreIds = $stmtGetIds->fetchAll(PDO::FETCH_COLUMN);

        // 3. Insert các thể loại mới vào bảng trung gian
        if (!empty($genreIds)) {
            $sqlInsert = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            foreach ($genreIds as $genreId) {
                $stmtInsert->execute([$movieId, $genreId]);
            }
        }
        return true;
    }
    /**
     * Lấy danh sách phim theo Trạng thái chiếu VÀ Slug Thể loại
     */
    /**
     * Lấy danh sách phim theo Trạng thái chiếu VÀ Mảng Thể loại (Hỗ trợ chọn nhiều)
     */
    public function getMoviesByStatusAndGenre($status, $genreSlugs) {
        // Nếu mảng rỗng, trả về tất cả phim của trạng thái đó
        if (empty($genreSlugs)) {
            return $this->getMoviesByStatus($status);
        }

        try {
            // Tạo chuỗi dấu '?' tương ứng với số lượng thể loại được chọn (VD: ?,?,?)
            $placeholders = implode(',', array_fill(0, count($genreSlugs), '?'));
            
            // Dùng toán tử IN để lấy phim thuộc bất kỳ thể loại nào trong danh sách
            $sql = "SELECT m.* 
                    FROM {$this->table} m
                    JOIN movie_genres mg ON m.id = mg.movie_id
                    JOIN genres g ON mg.genre_id = g.id
                    WHERE m.status = ? AND g.slug IN ($placeholders)
                    GROUP BY m.id
                    ORDER BY m.id DESC";

            $stmt = $this->db->prepare($sql);
            
            // Gộp mảng tham số: phần tử đầu tiên là $status, theo sau là các $genreSlugs
            $params = array_merge([$status], $genreSlugs);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}