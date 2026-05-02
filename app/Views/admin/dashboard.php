<?php
/**
 * Admin Dashboard - Trang chủ quản trị
 */
?>

<!-- Stats Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <h3>Tổng Ngườii dùng</h3>
        <div class="value"><?= htmlspecialchars((string)($stats['total_users'] ?? 0)) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-box"></i></div>
        <h3>Tất cả Sản phẩm</h3>
        <div class="value"><?= htmlspecialchars((string)($stats['total_products'] ?? 0)) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-newspaper"></i></div>
        <h3>Tất cả Tin tức</h3>
        <div class="value"><?= htmlspecialchars((string)($stats['total_news'] ?? 0)) ?></div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h3>Tài khoản Khóa</h3>
        <div class="value"><?= htmlspecialchars((string)($stats['locked_users'] ?? 0)) ?></div>
    </div>
</div>

<!-- Content Section -->
<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <h4>Chào mừng đến Admin Panel</h4>
    <p>Sử dụng menu bên trái để quản lý nội dung website.</p>
    <div style="text-align: center; padding: 40px 0; color: #999;">
        <p><i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 20px; display: block;"></i></p>
        <p>Vui lòng chọn một mục từ Menu Quản trị</p>
    </div>
</div>
