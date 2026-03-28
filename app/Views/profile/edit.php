<section class="panel narrow">
    <h1>Chinh sua ho so</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-grid">
        <label>
            Ho ten
            <input type="text" name="name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
        </label>
        <label>
            So dien thoai
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </label>
        <label>
            Avatar
            <input type="file" name="avatar" accept="image/*">
        </label>
        <button type="submit">Luu thay doi</button>
    </form>
</section>
