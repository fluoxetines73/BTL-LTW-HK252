<?php
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['success']);

$avatar = !empty($user['avatar']) ? (string)$user['avatar'] : 'uploads/avatars/default-avatar.svg';
$avatar = str_starts_with($avatar, 'public/') ? $avatar : ('public/' . ltrim($avatar, '/'));
?>

<section class="panel">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0"><?= htmlspecialchars($title ?? 'Chỉnh sửa Người dùng') ?></h1>
        <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars((string)$err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars((string)$success) ?></div>
    <?php endif; ?>

    <form id="editForm" method="post" enctype="multipart/form-data" class="card card-body" novalidate>
        <div class="row g-3">
            <div class="col-md-3 text-center">
                <img id="avatarPreview" src="<?= BASE_URL . htmlspecialchars($avatar) ?>" alt="Avatar" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                <div class="mt-2">
                    <label for="avatarInput" class="btn btn-outline-dark btn-sm">Chọn ảnh</label>
                    <input id="avatarInput" type="file" accept="image/*" style="display: none;">
                    <input id="avatarFileInput" type="file" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" style="display: none;">
                </div>
                <small class="text-muted d-block mt-2">JPG/PNG/WebP/GIF, tối đa 5MB</small>
            </div>

            <div class="col-md-9">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Tên</label>
                        <input class="form-control" type="text" id="name" name="name" value="<?= htmlspecialchars((string)($user['full_name'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" id="email" name="email" value="<?= htmlspecialchars((string)($user['email'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input class="form-control" type="tel" id="phone" name="phone" value="<?= htmlspecialchars((string)($user['phone'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="role">Vai trò</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="member" <?= (($user['role'] ?? '') === 'member') ? 'selected' : '' ?>>Member</option>
                            <option value="admin" <?= (($user['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= (($user['status'] ?? '') === 'active') ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= (($user['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Khóa</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày tạo</label>
                        <input class="form-control" value="<?= htmlspecialchars((string)($user['created_at'] ?? 'N/A')) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cập nhật lần cuối</label>
                        <input class="form-control" value="<?= htmlspecialchars((string)($user['updated_at'] ?? 'N/A')) ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/users">Hủy</a>
        </div>
    </form>
</section>

<script>
const avatarInput = document.getElementById('avatarInput');
const avatarFileInput = document.getElementById('avatarFileInput');
const avatarPreview = document.getElementById('avatarPreview');

avatarInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        alert('Vui lòng chọn ảnh JPG, PNG, WebP hoặc GIF.');
        this.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        alert('Tệp quá lớn. Tối đa 5MB.');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        avatarPreview.src = e.target.result;
    };
    reader.readAsDataURL(file);

    avatarFileInput.files = this.files;
});

const form = document.getElementById('editForm');
form.addEventListener('submit', function (e) {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');

    let valid = true;
    if (!name.value.trim() || name.value.trim().length < 2) valid = false;
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) valid = false;
    if (phone.value.trim() && !/^[0-9\-\+\s]{10,}$/.test(phone.value.trim())) valid = false;

    if (!valid) {
        e.preventDefault();
        alert('Vui lòng kiểm tra lại dữ liệu trước khi lưu.');
    }
});
</script>
