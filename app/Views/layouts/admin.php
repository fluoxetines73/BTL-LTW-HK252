<?php
$authUser = $_SESSION['auth_user'] ?? null;
$path = trim((string)($_GET['url'] ?? ''), '/');
$segment1 = explode('/', $path)[1] ?? '';
$segment0 = explode('/', $path)[0] ?? '';

$active = 'dashboard';
if ($segment1 === 'users' || $segment1 === 'search' || $segment1 === 'edit_user' || $segment1 === 'lock_user' || $segment1 === 'unlock_user' || $segment1 === 'delete_user' || $segment1 === 'reset_password') {
    $active = 'users';
} elseif ($segment1 === 'news' || $segment1 === 'create_news' || $segment1 === 'edit_news' || $segment1 === 'delete_news') {
    $active = 'news';
} elseif ($segment1 === 'reviews' || $segment1 === 'review_status' || $segment1 === 'delete_review') {
    $active = 'reviews';
} elseif ($segment1 === 'admin_dashboard') {
    $active = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/admin-srtdash.css">
    <?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
<body class="admin-dashboard">
    <div class="admin-shell">
        <aside class="admin-sidebar" aria-label="Admin sidebar navigation">
            <div class="admin-brand"><i class="fas fa-cogs"></i> Admin</div>
            <nav class="admin-nav">
                <a href="<?= BASE_URL ?>admin/admin_dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="<?= BASE_URL ?>admin/users" class="<?= $active === 'users' ? 'active' : '' ?>"><i class="fas fa-users"></i> Quản lý Người dùng</a>
                <a href="<?= BASE_URL ?>admin/news" class="<?= $active === 'news' ? 'active' : '' ?>"><i class="fas fa-newspaper"></i> Quản lý Tin tức</a>
                <a href="<?= BASE_URL ?>admin/reviews" class="<?= $active === 'reviews' ? 'active' : '' ?>"><i class="fas fa-comments"></i> Bình luận/đánh giá</a>
                <a href="<?= BASE_URL ?>profile/edit"><i class="fas fa-user-edit"></i> Hồ sơ cá nhân</a>
                <a href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <strong><?= htmlspecialchars($title ?? 'Admin') ?></strong>
                </div>
                <div class="text-muted">
                    <?= htmlspecialchars((string)($authUser['name'] ?? 'Admin')) ?>
                </div>
            </header>

            <main class="admin-content">
                <?php include APPROOT . '/Views/' . $content . '.php'; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/app.js"></script>
    <?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>
