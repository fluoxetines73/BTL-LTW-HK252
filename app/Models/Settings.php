<?php
require_once ROOT . '/app/Models/Model.php';

class Settings extends Model {
    protected string $table = 'settings';

    public function getByKey(string $key, $default = null) {
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();

        return $result ? $result['setting_value'] : $default;
    }

    public function set(string $key, $value): bool {
        $sql = "INSERT INTO {$this->table} (setting_key, setting_value)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE setting_value = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$key, $value, $value]);
    }

    public function getFeaturedMovieId() {
        $featured_id = $this->getByKey('featured_movie_id');

        if ($featured_id) {
            $stmt = $this->db->prepare(
                "SELECT id FROM {$this->table} WHERE setting_key = 'featured_movie_id'
                 AND (SELECT status FROM movies WHERE id = ?) IN ('now_showing', 'coming_soon')"
            );
            $stmt->execute([(int)$featured_id]);
            if ($stmt->fetch()) {
                return (int)$featured_id;
            }
            $this->db->prepare("DELETE FROM {$this->table} WHERE setting_key = 'featured_movie_id'")
                ->execute();
        }

        $stmt = $this->db->prepare(
            "SELECT id FROM movies
             WHERE status IN ('now_showing', 'coming_soon')
             ORDER BY id LIMIT 1"
        );
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $this->set('featured_movie_id', $result['id']);
            return (int)$result['id'];
        }

        return null;
    }
}
