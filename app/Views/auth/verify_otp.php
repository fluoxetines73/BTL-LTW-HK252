<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Xác thực OTP</h1>
                    <p class="text-muted">Chúng tôi đã gửi mã OTP 6 chữ số đến email của bạn. Mã có hiệu lực trong 5 phút.</p>
                </div>

                <div class="card-body">
                    <?php if (!empty($flash)): ?>
                        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
                        <div class="alert <?= $isError ? 'alert-danger' : 'alert-success' ?>" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" novalidate>
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">

                        <div class="mb-3">
                            <label for="email_display" class="form-label">Email đăng ký</label>
                            <input
                                type="email"
                                id="email_display"
                                class="form-control"
                                value="<?= htmlspecialchars($email ?? '') ?>"
                                readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label for="otp" class="form-label">Mã OTP</label>
                            <input
                                type="text"
                                id="otp"
                                name="otp"
                                class="form-control"
                                required
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                placeholder="Nhập mã OTP gồm 6 chữ số"
                            >
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Xác thực và tạo tài khoản</button>
                            <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-dark">Quay lại đăng ký</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
