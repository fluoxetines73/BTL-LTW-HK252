<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h1 class="card-title mb-2">Đăng nhập</h1>
                    <p class="text-muted">Đăng nhập để tiếp tục sử dụng tài khoản của bạn.</p>
                </div>

                <div class="card-body">
                    <?php if (!empty($flash)): ?>
                        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
                        <div class="alert <?= $isError ? 'alert-danger' : 'alert-success' ?>" role="alert">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($authUser)): ?>
                        <div class="alert alert-success">
                            Bạn đang đăng nhập với email: <strong><?= htmlspecialchars($authUser['email']) ?></strong>
                        </div>
                        <div class="mt-3">
                            <a href="<?= BASE_URL ?>auth/logout" class="btn btn-dark">Đăng xuất</a>
                        </div>
                    <?php else: ?>
                        <form method="post" novalidate>
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
                                    autocomplete="current-password"
                                    placeholder="Nhập mật khẩu"
                                >
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">Đăng nhập</button>
                                <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-dark">Chưa có tài khoản? Đăng ký</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
