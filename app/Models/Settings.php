<?php
require_once ROOT . '/app/Models/Model.php';

class Settings extends Model {
    protected string $table = 'settings';

    /**
     * Get setting value by key
     * 
     * @param string $key The setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    public function getByKey(string $key, $default = null) {
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        
        return $result ? $result['setting_value'] : $default;
    }

    /**
     * Set a setting value
     * 
     * Atomically inserts a new setting or updates an existing one.
     * Uses MySQL's INSERT ... ON DUPLICATE KEY UPDATE to avoid race conditions
     * and rowCount() unreliability when values don't change.
     * 
     * @param string $key The setting key
     * @param mixed $value The setting value
     * @return bool
     */
    public function set(string $key, $value): bool {
        $sql = "INSERT INTO {$this->table} (setting_key, setting_value) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE setting_value = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$key, $value, $value]);
    }

    /**
     * Get featured movie ID with fallback to first movie
     * 
     * Returns only movies with status 'now_showing' or 'coming_soon' to ensure
     * consistency with the homepage query. Filters both cached IDs and fallback queries.
     * 
     * @return int|null
     */
    public function getFeaturedMovieId() {
        // First try to get from settings
        $featured_id = $this->getByKey('featured_movie_id');
        
        if ($featured_id) {
            // Validate cached ID: ensure it's still a valid "showable" movie
            $stmt = $this->db->prepare(
                "SELECT id FROM {$this->table} WHERE setting_key = 'featured_movie_id'
                 AND (SELECT status FROM movies WHERE id = ?) IN ('now_showing', 'coming_soon')"
            );
            $stmt->execute([(int)$featured_id]);
            if ($stmt->fetch()) {
                return (int)$featured_id;
            }
            // Cached ID is invalid, clear it
            $this->db->prepare("DELETE FROM {$this->table} WHERE setting_key = 'featured_movie_id'")
                ->execute();
        }
        
        // Fallback: get first movie with valid status (now_showing or coming_soon)
        $stmt = $this->db->prepare(
            "SELECT id FROM movies 
             WHERE status IN ('now_showing', 'coming_soon')
             ORDER BY id LIMIT 1"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            // Cache the result in settings for next time
            $this->set('featured_movie_id', $result['id']);
            return (int)$result['id'];
        }
        
        return null;
    }
}
