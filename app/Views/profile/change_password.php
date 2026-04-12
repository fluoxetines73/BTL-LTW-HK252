<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Đổi mật khẩu</h1>
                    <p class="text-muted">Tạo mật khẩu mới để bảo vệ tài khoản của bạn tốt hơn.</p>
                </div>

                <div class="card-body">
                    <?php if (!empty($flash)): ?>
                        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
                        <div class="alert <?= $isError ? 'alert-danger' : 'alert-success' ?>" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= BASE_URL ?>profile/updatePassword" novalidate>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="form-control"
                                required
                                autocomplete="current-password"
                                placeholder="Nhập mật khẩu hiện tại"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input
                                type="password"
                                id="new_password"
                                name="new_password"
                                class="form-control"
                                required
                                minlength="6"
                                autocomplete="new-password"
                                placeholder="Tối thiểu 6 ký tự"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <input
                                type="password"
                                id="new_password_confirmation"
                                name="new_password_confirmation"
                                class="form-control"
                                required
                                minlength="6"
                                autocomplete="new-password"
                                placeholder="Nhập lại mật khẩu mới"
                            >
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Cập nhật mật khẩu</button>
                            <a href="<?= BASE_URL ?>profile/index" class="btn btn-outline-dark">Quay lại hồ sơ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
