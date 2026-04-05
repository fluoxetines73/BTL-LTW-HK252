<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Thông tin tài khoản</h1>
                    <p class="text-muted">Quản lý thông tin cá nhân và bảo mật tài khoản của bạn.</p>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <?php if (!empty($user['avatar'])): ?>
                                <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                                <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Ảnh đại diện" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Ảnh đại diện mặc định" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <?php endif; ?>
                            <p class="text-muted small text-uppercase">Ảnh đại diện</p>
                        </div>

                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted text-uppercase d-block mb-1">Họ và tên</small>
                                        <h5 class="mb-0"><?= htmlspecialchars($user['full_name'] ?? '') ?></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted text-uppercase d-block mb-1">Email</small>
                                        <p class="mb-0 text-break"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <small class="text-muted text-uppercase d-block mb-1">Số điện thoại</small>
                                        <p class="mb-0"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <a href="<?= BASE_URL ?>profile/edit" class="btn btn-success">Chỉnh sửa hồ sơ</a>
                                <a href="<?= BASE_URL ?>profile/changePassword" class="btn btn-outline-dark">Đổi mật khẩu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
