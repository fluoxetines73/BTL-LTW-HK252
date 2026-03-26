<section class="panel">
    <h1>Thong tin tai khoan</h1>

    <p><strong>Ho ten:</strong> <?= htmlspecialchars($user['full_name'] ?? '') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>So dien thoai:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></p>
    <p><strong>Avatar:</strong>
        <?php if (!empty($user['avatar'])): ?>
            <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
            <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Avatar" style="width:96px;height:96px;object-fit:cover;border-radius:50%;vertical-align:middle;">
        <?php else: ?>
            <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Avatar mac dinh" style="width:96px;height:96px;object-fit:cover;border-radius:50%;vertical-align:middle;">
        <?php endif; ?>
    </p>

    <p>
        <a class="btn" href="<?= BASE_URL ?>profile/edit">Chinh sua ho so</a>
        <a class="btn" href="<?= BASE_URL ?>profile/changePassword">Doi mat khau</a>
    </p>
</section>
