<section class="panel narrow">
    <h1>Dang nhap</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($authUser)): ?>
        <p>Ban dang dang nhap voi email: <strong><?= htmlspecialchars($authUser['email']) ?></strong></p>
        <a class="btn" href="<?= BASE_URL ?>auth/logout">Dang xuat</a>
    <?php else: ?>
        <form method="post" class="form-grid">
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Mat khau
                <input type="password" name="password" required>
            </label>
            <button type="submit">Dang nhap</button>
        </form>
    <?php endif; ?>

    <p>Chua co tai khoan? <a href="<?= BASE_URL ?>auth/register">Dang ky</a></p>
</section>
