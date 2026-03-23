<?php
require_once APPROOT . '/Models/Model.php';

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (name, email, password_hash, phone, address, avatar, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['avatar'] ?? null,
            $data['role'] ?? 'member',
            $data['status'] ?? 'active',
        ]);
    }

	public function findById(int $id): array|false {
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
		$stmt->execute([$id]);
		return $stmt->fetch();
	}

	public function updateProfile(int $id, array $data): bool {
		$stmt = $this->db->prepare("UPDATE {$this->table} SET name = ?, phone = ?, address = ?, avatar = COALESCE(?, avatar), updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([
			$data['name'],
			$data['phone'] ?? null,
			$data['address'] ?? null,
			$data['avatar'] ?? null,
			$id,
		]);
	}

	public function updatePassword(int $id, string $passwordHash): bool {
		$stmt = $this->db->prepare("UPDATE {$this->table} SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([$passwordHash, $id]);
	}
}
