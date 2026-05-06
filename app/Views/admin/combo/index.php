<div class="container-fluid py-4">
    <!-- 1. Breadcrumb & Header -->
    <nav aria-label="breadcrumb" class="admin-breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Combo</li>
        </ol>
    </nav>

    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-hamburger me-2"></i>Quản lý Bắp & Nước</h2>
        <a href="<?= BASE_URL ?>admin/combo/create" class="btn-add">
            <i class="fas fa-plus"></i> Thêm Combo Mới
        </a>
    </div>

    <!-- 2. Thanh tìm kiếm và Lọc -->
    <form method="GET" action="<?= BASE_URL ?>admin/combo/index" class="admin-search-form mb-3">
        <div class="search-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="q" class="search-input" placeholder="Tìm kiếm combo..." value="<?= htmlspecialchars($keyword ?? '') ?>">
        </div>
        <div class="ms-2 d-flex gap-2">
            <select name="sort" class="filter-select form-select-sm" onchange="this.form.submit()">
                <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá thấp → cao</option>
                <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá cao → thấp</option>
                <option value="name_asc" <?= ($sort ?? '') === 'name_asc' ? 'selected' : '' ?>>Tên A-Z</option>
            </select>
            <button type="submit" class="btn-search">Tìm</button>
            <a href="<?= BASE_URL ?>admin/combo/index" class="btn-reset">Xóa lọc</a>
        </div>
    </form>

    <!-- 3. Form Xóa hàng loạt và Bảng dữ liệu -->
    <form method="POST" action="<?= BASE_URL ?>admin/combo/index" id="bulk-action-form">
        <input type="hidden" name="action" value="delete_selected">
        <input type="hidden" name="selected_ids" id="selected-ids" value="">

        <!-- Thanh công cụ Xóa hàng loạt (Duy Nhất) -->
        <div class="admin-bulk-bar mb-3 p-3 shadow-sm rounded bg-light border-start border-danger border-4" id="bulk-bar" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-danger">Đã chọn <span id="selected-count">0</span> combo</span>
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmBulkDelete()">Xóa các mục đã chọn</button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="admin-table-header bg-cgv-red text-white">
                        <tr>
                            <th class="text-center"><input type="checkbox" id="check-all" onclick="toggleAll(this)"></th>
                            <th>Hình</th>
                            <th>Tên Combo</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($combos)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy combo nào!</td></tr>
                        <?php else: ?>
                            <?php foreach ($combos as $c): ?>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="cb-item" value="<?= $c['id'] ?>" onclick="updateBulkBar()"></td>
                                    <td><img src="<?= BASE_URL ?>public/uploads/combos/<?= htmlspecialchars($c['image'] ?: 'default-combo.png') ?>" width="50" height="50" class="rounded shadow-sm" style="object-fit: cover;"></td>
                                    <td><div class="fw-bold"><?= htmlspecialchars($c['name']) ?></div><small class="text-muted"><?= htmlspecialchars($c['description'] ?? '') ?></small></td>
                                    <td class="text-danger fw-bold"><?= number_format($c['price'], 0, ',', '.') ?>đ</td>
                                    <td><span class="badge <?= $c['is_active'] ? 'bg-success' : 'bg-secondary' ?>"><?= $c['is_active'] ? 'Hoạt động' : 'Ngừng' ?></span></td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>admin/combo/edit/<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= BASE_URL ?>admin/combo/delete/<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa combo này?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript giữ nguyên logic của Duy Nhất -->
<script>
function toggleAll(source) {
    let checkboxes = document.querySelectorAll('.cb-item');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateBulkBar();
}
function updateBulkBar() {
    let selected = document.querySelectorAll('.cb-item:checked');
    let count = selected.length;
    let bulkBar = document.getElementById('bulk-bar');
    document.getElementById('selected-count').innerText = count;
    bulkBar.style.display = count > 0 ? 'block' : 'none';
}
function confirmBulkDelete() {
    let selected = document.querySelectorAll('.cb-item:checked');
    if (confirm('Bạn muốn xóa ' + selected.length + ' combo đã chọn?')) {
        let ids = Array.from(selected).map(cb => cb.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>