<section class="panel">
    <h1>Thong tin tai khoan</h1>

    <p><strong>Ho ten:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>So dien thoai:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></p>
    <p><strong>Dia chi:</strong> <?= htmlspecialchars($user['address'] ?? '') ?></p>
    <p><strong>Avatar:</strong>
        <?php if (!empty($user['avatar'])): ?>
            <img src="<?= BASE_URL . htmlspecialchars($user['avatar']) ?>" alt="Avatar" style="width:96px;height:96px;object-fit:cover;border-radius:50%;vertical-align:middle;">
        <?php else: ?>
            Chua cap nhat
        <?php endif; ?>
    </p>

    <p>
        <a class="btn" href="<?= BASE_URL ?>profile/edit">Chinh sua ho so</a>
        <a class="btn" href="<?= BASE_URL ?>profile/changePassword">Doi mat khau</a>
    </p>
</section>
