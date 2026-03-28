<section class="panel narrow">
    <h1>Dang ky</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>
            Ho ten
            <input type="text" name="name" required>
        </label>
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Mat khau
            <input type="password" name="password" required>
        </label>
        <button type="submit">Tao tai khoan</button>
    </form>

    <p>Da co tai khoan? <a href="<?= BASE_URL ?>auth/login">Dang nhap</a></p>
</section>
