<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php if (!empty($extraHead)) echo $extraHead; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin.css">
</head>
<body>

    <div class="admin-container">
        <?php include APPROOT . '/Views/admin/partials/sidebar.php'; ?>

        <main class="main-content">
            <div class="admin-header">
                <div class="d-flex align-items-center gap-2">
                    <button id="sidebar-toggle" class="sidebar-toggle btn btn-outline-secondary btn-sm">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="admin-header-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-primary btn-sm" target="_blank" title="Xem trang chủ">
                        <i class="fas fa-external-link-alt me-1"></i> Xem trang chủ
                    </a>
                    <div class="user-profile">
                        <span class="user-profile-name"><?= htmlspecialchars($_SESSION['auth_user']['name'] ?? 'Admin') ?></span>
                        <span class="user-profile-role">Admin</span>
                        <?php if (!empty($_SESSION['auth_user']['avatar'])): ?>
                            <?php $avatarPath = (string)$_SESSION['auth_user']['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                            <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Avatar" class="user-profile-avatar">
                        <?php else: ?>
                            <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Avatar" class="user-profile-avatar">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (isset($stats)): ?>
            <div class="admin-stats-bar">
                <!-- Users -->
                <div class="stat-card stat-card--users">
                    <div class="stat-card__icon"><i class="fas fa-users"></i></div>
                    <div class="stat-card__body">
                        <span class="stat-card__value"><?= htmlspecialchars((string)($stats['users'] ?? 0)) ?></span>
                        <span class="stat-card__label">Người dùng</span>
                    </div>
                </div>
                <!-- Movies -->
                <div class="stat-card stat-card--movies">
                    <div class="stat-card__icon"><i class="fas fa-film"></i></div>
                    <div class="stat-card__body">
                        <span class="stat-card__value"><?= htmlspecialchars((string)($stats['movies'] ?? 0)) ?></span>
                        <span class="stat-card__label">Phim</span>
                    </div>
                </div>
                <!-- Showtimes -->
                <div class="stat-card stat-card--showtimes">
                    <div class="stat-card__icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-card__body">
                        <span class="stat-card__value"><?= htmlspecialchars((string)($stats['showtimes'] ?? 0)) ?></span>
                        <span class="stat-card__label">Suất chiếu</span>
                    </div>
                </div>
                <!-- Combos -->
                <div class="stat-card stat-card--combos">
                    <div class="stat-card__icon"><i class="fas fa-box-open"></i></div>
                    <div class="stat-card__body">
                        <span class="stat-card__value"><?= htmlspecialchars((string)($stats['combos'] ?? 0)) ?></span>
                        <span class="stat-card__label">Combo</span>
                    </div>
                </div>
                <!-- News -->
                <div class="stat-card stat-card--news">
                    <div class="stat-card__icon"><i class="fas fa-newspaper"></i></div>
                    <div class="stat-card__body">
                        <span class="stat-card__value"><?= htmlspecialchars((string)($stats['news'] ?? 0)) ?></span>
                        <span class="stat-card__label">Tin tức</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php include APPROOT . '/Views/' . $content . '.php'; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/admin.js"></script>
    <?php if (!empty($extraScripts)) echo $extraScripts; ?>

</body>
</html>
