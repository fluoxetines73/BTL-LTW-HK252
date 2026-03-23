<section class="panel narrow">
    <h1>Doi mat khau</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <label>
            Mat khau hien tai
            <input type="password" name="current_password" required>
        </label>
        <label>
            Mat khau moi
            <input type="password" name="new_password" required minlength="6">
        </label>
        <label>
            Xac nhan mat khau moi
            <input type="password" name="new_password_confirmation" required minlength="6">
        </label>
        <button type="submit">Cap nhat mat khau</button>
    </form>
</section>
