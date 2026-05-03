<section class="panel">
    <h1>Lien he</h1>
    <p>Form nay dang o muc demo de test route + validate co ban.</p>

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

        <label class="full">
            Noi dung
            <textarea name="message" rows="4" required></textarea>
        </label>

        <button type="submit">Gui lien he</button>
    </form>
</section>
