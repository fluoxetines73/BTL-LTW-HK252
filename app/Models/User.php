<?php
require_once APPROOT . '/Models/Model.php';

class User extends Model {
	protected string $table = 'users';

	public function findByEmail(string $email): array|false {
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
		$stmt->execute([$email]);
		return $stmt->fetch();
	}
}
