<?php
$active = $activeMenu ?? '';
?>

<nav class="mb-4" aria-label="Admin menu">
    <div class="d-flex flex-wrap gap-2">
        <a class="btn <?= $active === 'dashboard' ? 'btn-dark' : 'btn-outline-dark' ?>" href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a>
        <a class="btn <?= in_array($active, ['users', 'search', 'edit_user'], true) ? 'btn-dark' : 'btn-outline-dark' ?>" href="<?= BASE_URL ?>admin/users">Người dùng</a>
        <a class="btn <?= in_array($active, ['news', 'create_news', 'edit_news'], true) ? 'btn-dark' : 'btn-outline-dark' ?>" href="<?= BASE_URL ?>admin/news">Tin tức</a>
        <a class="btn <?= $active === 'reviews' ? 'btn-dark' : 'btn-outline-dark' ?>" href="<?= BASE_URL ?>admin/reviews">Bình luận/Đánh giá</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>profile/edit">Hồ sơ</a>
        <a class="btn btn-outline-danger" href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');">Đăng xuất</a>
    </div>
</nav>
