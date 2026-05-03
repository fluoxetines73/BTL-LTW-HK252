<?php
require_once 'app/Models/Model.php';

class AboutPageSettings extends Model {
    protected string $table = 'about_page_settings';

    /**
     * Get About page settings (single row)
     * @return array|null
     */
    public function getSettings(): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT 1");
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    /**
     * Update About page settings
     * @param array $data
     * @param int $userId
     * @return bool
     */
    public function updateSettings(array $data, int $userId): bool {
        // Build SQL dynamically based on what fields are provided
        $fields = [
            'hero_title',
            'hero_kicker',
            'intro_heading',
            'intro_paragraph_1',
            'intro_paragraph_2',
            'vision_title',
            'vision_icon',
            'vision_content',
            'mission_title',
            'mission_icon',
            'mission_content'
        ];
        
        $setParts = [];
        $params = [];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $setParts[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }
        
        // Always update these
        $setParts[] = "updated_by = :updated_by";
        $params['updated_by'] = $userId;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE id = 1";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update only the intro image path
     * @param string $imagePath
     * @return bool
     */
    public function updateIntroImage(string $imagePath): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET intro_image = :image WHERE id = 1");
        return $stmt->execute(['image' => $imagePath]);
    }
}
