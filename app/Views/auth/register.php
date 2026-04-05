<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Đăng ký</h1>
                    <p class="text-muted">Tạo tài khoản mới để trải nghiệm đầy đủ tính năng của hệ thống.</p>
                </div>

                <div class="card-body">
                    <?php if (!empty($flash)): ?>
                        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
                        <div class="alert <?= $isError ? 'alert-danger' : 'alert-success' ?>" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                placeholder="Ví dụ: Nguyễn Văn A"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                placeholder="name@example.com"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                                minlength="6"
                                autocomplete="new-password"
                                placeholder="Tối thiểu 6 ký tự"
                            >
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Tạo tài khoản</button>
                            <a href="<?= BASE_URL ?>auth/login" class="btn btn-outline-dark">Đã có tài khoản? Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
