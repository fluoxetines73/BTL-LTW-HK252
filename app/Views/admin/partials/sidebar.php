<?php
/**
 * Admin Sidebar Partial
 * Expects $activeSection variable from parent layout for active state highlighting.
 */
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <h3><i class="fas fa-cogs"></i> <span class="sidebar-text">Admin</span></h3>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>admin/admin_dashboard" class="<?= ($activeSection ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/users" class="<?= ($activeSection ?? '') === 'users' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> <span class="sidebar-text">Quản lý Người dùng</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/movie/index" class="<?= ($activeSection ?? '') === 'movie' ? 'active' : '' ?>">
                <i class="fas fa-film"></i> <span class="sidebar-text">Quản lý Phim</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/showtime/index" class="<?= ($activeSection ?? '') === 'showtime' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i> <span class="sidebar-text">Quản lý Suất chiếu</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/combo/index" class="<?= ($activeSection ?? '') === 'combo' ? 'active' : '' ?>">
                <i class="fas fa-hamburger"></i> <span class="sidebar-text">Quản lý Combo</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/news" class="<?= ($activeSection ?? '') === 'news' ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i> <span class="sidebar-text">Quản lý Tin tức</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/order/index" class="<?= ($activeSection ?? '') === 'order' ? 'active' : '' ?>">
                <i class="fas fa-receipt"></i> <span class="sidebar-text">Quản lý Đơn hàng</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/page/index" class="<?= ($activeSection ?? '') === 'page' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> <span class="sidebar-text">Quản lý Trang</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>admin/faq/index" class="<?= ($activeSection ?? '') === 'faq' ? 'active' : '' ?>">
                <i class="fas fa-question-circle"></i> <span class="sidebar-text">Quản lý FAQ</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>profile/edit" class="<?= ($activeSection ?? '') === 'profile' ? 'active' : '' ?>">
                <i class="fas fa-user-edit"></i> <span class="sidebar-text">Hồ sơ cá nhân</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');">
                <i class="fas fa-sign-out-alt"></i> <span class="sidebar-text">Đăng xuất</span>
            </a>
        </li>
    </ul>
</aside>
