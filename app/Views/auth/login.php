<section class="panel narrow">
    <h1>Đăng nhập</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($authUser)): ?>
        <p>Bạn đang đăng nhập với email: <strong><?= htmlspecialchars($authUser['email']) ?></strong></p>
        <a class="btn" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a>
    <?php else: ?>
        <form method="post" class="form-grid">
            <fieldset class="form-fieldset">
                <legend>Thông tin đăng nhập</legend>

                <label class="form-label">
                    Email
                    <input type="email" name="email" required>
                </label>

                <label class="form-label">
                    Mật khẩu
                    <input type="password" name="password" required>
                </label>

                <button type="submit">Đăng nhập</button>
            </fieldset>
        </form>
    <?php endif; ?>

    <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>auth/register">Đăng ký</a></p>
</section>
