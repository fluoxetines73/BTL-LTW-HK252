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
     * @param string $key The setting key
     * @param mixed $value The setting value
     * @return bool
     */
    public function set(string $key, $value): bool {
        // Try to update first
        $stmt = $this->db->prepare("UPDATE {$this->table} SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$value, $key]);
        
        // If no rows affected, insert new row
        if ($stmt->rowCount() === 0) {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
        
        return true;
    }

    /**
     * Get featured movie ID with fallback to first movie
     * 
     * @return int|null
     */
    public function getFeaturedMovieId() {
        // First try to get from settings
        $featured_id = $this->getByKey('featured_movie_id');
        
        if ($featured_id) {
            return (int)$featured_id;
        }
        
        // Fallback: get first movie ID
        $stmt = $this->db->query("SELECT id FROM movies ORDER BY id LIMIT 1");
        $result = $stmt->fetch();
        
        if ($result) {
            // Cache the result in settings for next time
            $this->set('featured_movie_id', $result['id']);
            return (int)$result['id'];
        }
        
        return null;
    }
}
