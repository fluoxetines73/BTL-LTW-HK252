<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Chỉnh sửa hồ sơ</h1>
                    <p class="text-muted">Cập nhật thông tin cá nhân và ảnh đại diện của bạn.</p>
                </div>

                <div class="card-body">
                    <?php if (!empty($flash)): ?>
                        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
                        <div class="alert <?= $isError ? 'alert-danger' : 'alert-success' ?>" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                                placeholder="Ví dụ: Nguyễn Văn A"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                placeholder="Ví dụ: 0901234567"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Ảnh đại diện</label>

                            <?php if (!empty($user['avatar'])): ?>
                                <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                                <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Ảnh đại diện hiện tại" class="rounded mb-3" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Ảnh đại diện mặc định" class="rounded mb-3" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                            <?php endif; ?>

                            <input
                                type="file"
                                name="avatar"
                                accept="image/*"
                                class="form-control"
                            >
                            <small class="text-muted d-block mt-2">Định dạng hỗ trợ: JPG, PNG, WEBP. Kích thước tối đa theo cấu hình hệ thống.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                            <a href="<?= BASE_URL ?>profile/index" class="btn btn-outline-dark">Quay lại hồ sơ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
