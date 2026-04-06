<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$stats = $stats ?? [
    'total_users' => 0,
    'total_movies' => 0,
    'total_news' => 0,
    'locked_accounts' => 0,
];
?>

<div class="admin-header-card">
    <div class="admin-header-title"><?= htmlspecialchars($title ?? 'Bảng điều khiển Admin') ?></div>
    <div class="admin-header-user">
        <div>
            <strong><?= htmlspecialchars((string)($_SESSION['auth_user']['name'] ?? 'Admin')) ?></strong><br>
            <small>Admin</small>
        </div>
        <?php if (!empty($_SESSION['auth_user']['avatar'])): ?>
            <?php $avatarPath = (string)$_SESSION['auth_user']['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
            <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Avatar" class="admin-header-avatar">
        <?php else: ?>
            <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Avatar" class="admin-header-avatar">
        <?php endif; ?>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars((string)$success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars((string)$error) ?></div>
<?php endif; ?>

<div class="stats-container">
    <div class="stat-card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <h3>Tổng Người dùng</h3>
        <div class="value"><?= (int)($stats['total_users'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-box"></i></div>
        <h3>Tất cả Sản phẩm</h3>
        <div class="value"><?= (int)($stats['total_movies'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-newspaper"></i></div>
        <h3>Tất cả Tin tức</h3>
        <div class="value"><?= (int)($stats['total_news'] ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h3>Tài khoản Khóa</h3>
        <div class="value"><?= (int)($stats['locked_accounts'] ?? 0) ?></div>
    </div>
</div>

<div class="admin-welcome-card">
    <h4>Chào mừng đến Admin Panel</h4>
    <p>Sử dụng menu bên trái để quản lý nội dung website.</p>
    <div class="admin-welcome-empty">
        <i class="fas fa-chart-line"></i>
        <p>Vui lòng chọn một mục từ Menu Quản trị</p>
    </div>
</div>
