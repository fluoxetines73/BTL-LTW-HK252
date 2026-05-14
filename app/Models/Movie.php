<?php
require_once ROOT . '/app/Models/Model.php';

class Movie extends Model {
    protected string $table = 'movies';

    public function getAllMovies($keyword = '') {
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genre_names 
                FROM {$this->table} m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id";
        
        $params = [];

        if (!empty($keyword)) {
            $sql .= " WHERE m.title LIKE :keyword_title OR m.director LIKE :keyword_director";
            $params[':keyword_title'] = "%{$keyword}%";
            $params[':keyword_director'] = "%{$keyword}%";
        }

        $sql .= " GROUP BY m.id ORDER BY m.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết 1 bộ phim kèm thể loại
     */
    public function getMovieById($id) {
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

    public function createMovie($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, slug, description, director, cast, duration_min, release_date, age_rating, status, poster) 
                VALUES 
                (:title, :slug, :description, :director, :cast, :duration_min, :release_date, :age_rating, :status, :poster)";
        
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

        return $stmt->execute();
    }

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

    public function updateMovie($id, $data) {
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

    public function deleteMovie($id) {
        $db = Database::getInstance()->getPdo();
        try {
            $db->beginTransaction();

            $stmt1 = $db->prepare("DELETE FROM showtimes WHERE movie_id = :id");
            $stmt1->execute([':id' => $id]);

            $stmt2 = $db->prepare("DELETE FROM movies WHERE id = :id");
            $stmt2->execute([':id' => $id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public function getMoviesByStatus($status) {
        $stmt = $this->db->prepare("SELECT * FROM movies WHERE status = :status ORDER BY release_date DESC");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchMovies($keyword) {
        $keyword = "%{$keyword}%";
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
        $sql = "SELECT g.slug FROM genres g 
                JOIN movie_genres mg ON g.id = mg.genre_id 
                WHERE mg.movie_id = :movie_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['movie_id' => $movieId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function syncMovieGenres($movieId, $genreSlugs) {
        $stmtDelete = $this->db->prepare("DELETE FROM movie_genres WHERE movie_id = ?");
        $stmtDelete->execute([$movieId]);

        if (empty($genreSlugs)) return true;

        $placeholders = implode(',', array_fill(0, count($genreSlugs), '?'));
        $stmtGetIds = $this->db->prepare("SELECT id FROM genres WHERE slug IN ($placeholders)");
        $stmtGetIds->execute($genreSlugs);
        $genreIds = $stmtGetIds->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($genreIds)) {
            $sqlInsert = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            foreach ($genreIds as $genreId) {
                $stmtInsert->execute([$movieId, $genreId]);
            }
        }
        return true;
    }

    public function getMoviesByStatusAndGenre($status, $genreSlugs) {
        if (empty($genreSlugs)) {
            return $this->getMoviesByStatus($status);
        }

        $placeholders = implode(',', array_fill(0, count($genreSlugs), '?'));
        $sql = "SELECT m.* 
                FROM {$this->table} m
                JOIN movie_genres mg ON m.id = mg.movie_id
                JOIN genres g ON mg.genre_id = g.id
                WHERE m.status = ? AND g.slug IN ($placeholders)
                GROUP BY m.id
                ORDER BY m.id DESC";

        $stmt = $this->db->prepare($sql);
        $params = array_merge([$status], $genreSlugs);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAdminMovies($keyword = '', $status = 'all', $sort = 'newest', $limit = 10, $offset = 0) {
        $sql = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genre_names 
                FROM movies m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (m.title LIKE :q1 OR m.director LIKE :q2)";
            $params[':q1'] = $params[':q2'] = "%$keyword%";
        }

        if ($status !== 'all') {
            $sql .= " AND m.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " GROUP BY m.id";

        if ($sort === 'oldest') {
            $sql .= " ORDER BY m.created_at ASC, m.id ASC";
        } else {
            $sql .= " ORDER BY m.created_at DESC, m.id DESC";
        }
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteMultipleMovies(array $ids) {
        if (empty($ids)) return false;
        
        $db = Database::getInstance()->getPdo();
        try {
            $db->beginTransaction();

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sqlCheck = "SELECT COUNT(*) FROM bookings b
                         JOIN showtimes st ON b.showtime_id = st.id
                         WHERE st.movie_id IN ($placeholders)";
            
            $stmtCheck = $db->prepare($sqlCheck);
            $stmtCheck->execute($ids);
            $totalBookings = $stmtCheck->fetchColumn();

            if ($totalBookings > 0) {
                
                $db->rollBack();
                
                error_log("Bulk Delete Aborted: Phát hiện $totalBookings đơn hàng liên quan đến danh sách phim đang cố xóa.");
                return false; 
            }

            $sqlShowtimes = "DELETE FROM showtimes WHERE movie_id IN ($placeholders)";
            $stmt1 = $db->prepare($sqlShowtimes);
            $stmt1->execute($ids);

            $sqlMovies = "DELETE FROM movies WHERE id IN ($placeholders)";
            $stmt2 = $db->prepare($sqlMovies);
            $stmt2->execute($ids);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Bulk Delete Error: " . $e->getMessage());
            return false;
        }
    }

    public function countAdminMovies($keyword = '', $status = 'all') {
        $sql = "SELECT COUNT(*) FROM movies WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (title LIKE :q1 OR director LIKE :q2)";
            $params[':q1'] = $params[':q2'] = "%$keyword%";
        }
        if ($status !== 'all') {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function isSlugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM movies WHERE slug = :slug";
        $params = [':slug' => $slug];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    public function hasBookings($movieId) {
        $sql = "SELECT COUNT(*) FROM bookings b
                JOIN showtimes st ON b.showtime_id = st.id
                WHERE st.movie_id = :movie_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':movie_id' => $movieId]);
        
        return $stmt->fetchColumn() > 0; 
    }
    public function getClientMovies($status, $genreSlugs = [], $keyword = '') {
        $sql = "SELECT m.* FROM {$this->table} m ";
        
        if (!empty($genreSlugs)) {
            $sql .= " JOIN movie_genres mg ON m.id = mg.movie_id 
                      JOIN genres g ON mg.genre_id = g.id ";
        }
        
        // Sửa :status thành ?
        $sql .= " WHERE m.status = ? ";

        if (!empty($genreSlugs)) {
            $inQuery = implode(',', array_fill(0, count($genreSlugs), '?'));
            $sql .= " AND g.slug IN ($inQuery) ";
        }

        if (!empty($keyword)) {
            $sql .= " AND (m.title LIKE ? OR m.director LIKE ?) ";
        }

        $sql .= " GROUP BY m.id ORDER BY m.release_date DESC";

        $stmt = $this->db->prepare($sql);
        
        $bindIndex = 1;
        // Bind status
        $stmt->bindValue($bindIndex++, $status);
        
        // Bind genres
        if (!empty($genreSlugs)) {
            foreach ($genreSlugs as $slug) {
                $stmt->bindValue($bindIndex++, $slug);
            }
        }
        
        // Bind keyword
        if (!empty($keyword)) {
            $stmt->bindValue($bindIndex++, "%{$keyword}%");
            $stmt->bindValue($bindIndex++, "%{$keyword}%");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}