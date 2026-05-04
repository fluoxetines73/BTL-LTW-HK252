<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card cgv-card">
                <div class="card-header">
                    <h1 class="card-title mb-2">Thông tin tài khoản</h1>
                    <p class="text-muted">Quản lý thông tin cá nhân và bảo mật tài khoản của bạn.</p>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <?php if (!empty($user['avatar'])): ?>
                                <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                                <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Ảnh đại diện" class="rounded-circle mb-3 cgv-avatar" style="width: 120px; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Ảnh đại diện mặc định" class="rounded-circle mb-3 cgv-avatar" style="width: 120px; height: 120px; object-fit: cover;">
                            <?php endif; ?>
                            <p class="text-muted small text-uppercase">Ảnh đại diện</p>
                        </div>

                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="cgv-info-field">
                                        <small class="text-uppercase d-block mb-1">Họ và tên</small>
                                        <h5 class="mb-0"><?= htmlspecialchars($user['full_name'] ?? '') ?></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="cgv-info-field">
                                        <small class="text-uppercase d-block mb-1">Email</small>
                                        <p class="mb-0 text-break"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="cgv-info-field">
                                        <small class="text-uppercase d-block mb-1">Số điện thoại</small>
                                        <p class="mb-0"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <a href="<?= BASE_URL ?>profile/edit" class="btn btn-cgv-primary">Chỉnh sửa hồ sơ</a>
                                <a href="<?= BASE_URL ?>profile/changePassword" class="btn btn-cgv-outline">Đổi mật khẩu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
