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
		$sql = "INSERT INTO {$this->table} (full_name, email, password, phone, avatar, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
			$data['name'],
            $data['email'],
			$data['password_hash'],
            $data['phone'] ?? null,
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
		$stmt = $this->db->prepare("UPDATE {$this->table} SET full_name = ?, phone = ?, avatar = COALESCE(?, avatar), updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([
			$data['name'],
			$data['phone'] ?? null,
			$data['avatar'] ?? null,
			$id,
		]);
	}

	/**
	 * Cập nhật toàn bộ thông tin người dùng (dùng cho Admin)
	 */
	public function updateUserFull(int $id, array $data): bool {
		$stmt = $this->db->prepare("UPDATE {$this->table} SET 
			full_name = ?, 
			email = ?, 
			phone = ?, 
			avatar = COALESCE(?, avatar),
			role = ?,
			status = ?,
			updated_at = CURRENT_TIMESTAMP 
			WHERE id = ?");
		return $stmt->execute([
			$data['name'],
			$data['email'],
			$data['phone'] ?? null,
			$data['avatar'] ?? null,
			$data['role'] ?? 'member',
			$data['status'] ?? 'active',
			$id,
		]);
	}

	public function updatePassword(int $id, string $passwordHash): bool {
		$stmt = $this->db->prepare("UPDATE {$this->table} SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([$passwordHash, $id]);
	}

	// ===== ADMIN MANAGEMENT METHODS =====

	/**
	 * Lấy danh sách tất cả người dùng (không phân trang)
	 */
	public function getAllUsers(): array {
		$stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
		return $stmt->fetchAll();
	}

	/**
	 * Lấy danh sách người dùng với phân trang
	 * @param int $page Trang hiện tại (bắt đầu từ 1)
	 * @param int $perPage Số lượng bản ghi trên mỗi trang
	 * @return array ['users' => array, 'total' => int, 'pages' => int, 'current_page' => int]
	 */
	public function getUsersPaginated(int $page = 1, int $perPage = 10): array {
		$page = max(1, $page);
		$offset = ($page - 1) * $perPage;

		// Lấy tổng số bản ghi
		$stmtCount = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
		$total = (int)$stmtCount->fetchColumn();
		$pages = ceil($total / $perPage);

		// Lấy dữ liệu cho trang hiện tại
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?");
		$stmt->execute([$perPage, $offset]);
		$users = $stmt->fetchAll();

		return [
			'users' => $users,
			'total' => $total,
			'pages' => $pages,
			'current_page' => $page,
		];
	}

	/**
	 * Tìm kiếm người dùng theo email hoặc tên
	 */
	public function search(string $keyword, int $page = 1, int $perPage = 10): array {
		$page = max(1, $page);
		$offset = ($page - 1) * $perPage;
		$searchTerm = '%' . $keyword . '%';

		// Lấy tổng số bản ghi
		$stmtCount = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email LIKE ? OR full_name LIKE ?");
		$stmtCount->execute([$searchTerm, $searchTerm]);
		$total = (int)$stmtCount->fetchColumn();
		$pages = ceil($total / $perPage);

		// Lấy dữ liệu cho trang hiện tại
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email LIKE ? OR full_name LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
		$stmt->execute([$searchTerm, $searchTerm, $perPage, $offset]);
		$users = $stmt->fetchAll();

		return [
			'users' => $users,
			'total' => $total,
			'pages' => $pages,
			'current_page' => $page,
			'keyword' => $keyword,
		];
	}

	/**
	 * Khóa hoặc mở khóa người dùng
	 */
	public function updateStatus(int $id, string $status): bool {
		if (!in_array($status, ['active', 'inactive'], true)) {
			return false;
		}
		$stmt = $this->db->prepare("UPDATE {$this->table} SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([$status, $id]);
	}

	/**
	 * Xóa người dùng
	 */
	public function deleteUser(int $id): bool {
		// Kiểm tra xem không phải là admin duy nhất
		$adminCount = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE role = 'admin'");
		if ((int)$adminCount->fetchColumn() === 1) {
			$user = $this->findById($id);
			if ($user && $user['role'] === 'admin') {
				// Không cho phép xóa admin duy nhất
				return false;
			}
		}

		// Xóa người dùng
		$stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
		return $stmt->execute([$id]);
	}

	/**
	 * Đặt lại mật khẩu ngẫu nhiên cho người dùng
	 */
	public function resetPasswordToRandom(int $id): ?string {
		$tempPassword = bin2hex(random_bytes(4)); // Mật khẩu tạm 8 ký tự
		$passwordHash = password_hash($tempPassword, PASSWORD_BCRYPT);

		$stmt = $this->db->prepare("UPDATE {$this->table} SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		if ($stmt->execute([$passwordHash, $id])) {
			return $tempPassword;
		}
		return null;
	}

	/**
	 * Cập nhật avatar người dùng
	 */
	public function updateAvatar(int $id, string $avatarPath): bool {
		$stmt = $this->db->prepare("UPDATE {$this->table} SET avatar = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
		return $stmt->execute([$avatarPath, $id]);
	}
}
