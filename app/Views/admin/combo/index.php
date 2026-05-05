<?php
/**
 * Admin Combo Index - Danh sách Combo
 * Content fragment for admin layout
 */
?>
<nav aria-label="breadcrumb" class="admin-breadcrumb mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Combo</li>
    </ol>
</nav>

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-hamburger"></i>Quản lý Bắp & Nước</h2>
    <a href="<?= BASE_URL ?>admin/combo/create" class="btn-add">
        <i class="fas fa-plus"></i> Thêm Combo Mới
    </a>
</div>

<form method="GET" action="<?= BASE_URL ?>admin/combo/index" class="admin-search-form">
    <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" class="search-input" placeholder="Tìm kiếm combo..." value="<?= htmlspecialchars($search ?? '') ?>">
    </div>
    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
    <a href="<?= BASE_URL ?>admin/combo/index" class="btn-reset"><i class="fas fa-times"></i> Xóa</a>
</form>

<div class="admin-filter-bar">
    <div class="filter-group">
        <span class="filter-label">Sắp xếp theo:</span>
        <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá: Thấp → Cao</option>
            <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá: Cao → Thấp</option>
            <option value="name_asc" <?= ($sort ?? '') === 'name_asc' ? 'selected' : '' ?>>Tên: A → Z</option>
            <option value="name_desc" <?= ($sort ?? '') === 'name_desc' ? 'selected' : '' ?>>Tên: Z → A</option>
        </select>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (!empty($combos)): ?>
        <?php foreach ($combos as $combo): ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <img src="<?= BASE_URL ?>public/uploads/combos/<?= htmlspecialchars($combo['image']) ?>" 
                class="card-img-top" 
                alt="<?= htmlspecialchars($combo['name']) ?>" 
                style="height: 200px; object-fit: cover;">                    <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($combo['name']) ?></h5>
                        <span class="text-danger fw-bold"><?= number_format($combo['price'], 0, ',', '.') ?>đ</span>
                    </div>
                    <p class="card-text text-muted small"><?= htmlspecialchars($combo['description']) ?></p>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex gap-2">
                    <a href="<?= BASE_URL ?>admin/combo/edit/<?= $combo['id'] ?>" class="btn btn-sm btn-outline-primary w-100">Sửa</a>
                    <a href="<?= BASE_URL ?>admin/combo/delete/<?= $combo['id'] ?>" 
                        class="btn btn-sm btn-outline-danger w-100" 
                        onclick="return confirm('Bạn có chắc chắn muốn xóa combo này?');">Xóa</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted">Chưa có combo nào. Hãy thêm combo đầu tiên!</p>
        </div>
    <?php endif; ?>
</div>