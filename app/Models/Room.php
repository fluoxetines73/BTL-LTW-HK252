<?php
class Room extends Model {
    protected string $table = 'rooms';

    public function getAllRooms() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'active'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}