<?php
/**
 * Admin Dashboard - Trang chủ quản trị
 */
?>

<!-- Welcome Section -->
<div class="welcome-section" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
    <h4 class="mb-3" style="color: #333; font-weight: 600;">
        <i class="fas fa-chart-line me-2" style="color: #e71a0f;"></i>
        Chào mừng đến Admin Panel
    </h4>
    <p class="mb-4" style="color: #666;">Sử dụng menu bên trái để quản lý nội dung website.</p>
    
    <div class="quick-actions">
        <h6 class="mb-3" style="color: #555; font-weight: 500;">Truy cập nhanh:</h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-users me-1"></i> Quản lý người dùng
            </a>
            <a href="<?= BASE_URL ?>admin/movie" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-film me-1"></i> Quản lý phim
            </a>
            <a href="<?= BASE_URL ?>admin/showtimes" class="btn btn-outline-info btn-sm">
                <i class="fas fa-clock me-1"></i> Quản lý suất chiếu
            </a>
            <a href="<?= BASE_URL ?>admin/combo" class="btn btn-outline-success btn-sm">
                <i class="fas fa-box-open me-1"></i> Quản lý combo
            </a>
            <a href="<?= BASE_URL ?>admin/news" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-newspaper me-1"></i> Quản lý tin tức
            </a>
        </div>
    </div>
</div>

<style>
/* Quick action buttons styling */
.quick-actions .btn {
    border-width: 2px;
    font-weight: 500;
    padding: 8px 16px;
    transition: all 0.2s ease;
}

.quick-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.quick-actions .btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}

.quick-actions .btn-outline-danger:hover {
    background-color: #e71a0f;
    border-color: #e71a0f;
}

.quick-actions .btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.quick-actions .btn-outline-success:hover {
    background-color: #28a745;
    border-color: #28a745;
}

.quick-actions .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}
</style>
