<?php
require_once APPROOT . '/Models/Model.php';

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Thêm hàm này để xử lý đăng ký
    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (name, email, password_hash, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['role'] ?? 'member'
        ]);
    }
}

class User extends Model {
	protected string $table = 'users';

	public function findByEmail(string $email): array|false {
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
		$stmt->execute([$email]);
		return $stmt->fetch();
	}
}
