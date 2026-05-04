<div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0"><i class="fas fa-box-open me-2"></i> <?= htmlspecialchars($title ?? 'Quản lý Combo') ?></h2>
    <a href="<?= BASE_URL ?>admin/combo/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Thêm Combo</a>
</div>

<!-- Thanh tìm kiếm và lọc -->
<form method="GET" action="<?= BASE_URL ?>admin/combo/index" class="admin-filter-bar">
    <div class="admin-filter-search">
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="q" placeholder="Tìm kiếm tên, mô tả..." value="<?= htmlspecialchars($keyword ?? '') ?>">
    </div>
    <div class="admin-filter-controls">
        <select name="status">
            <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>Tất cả trạng thái</option>
            <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
            <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Ngừng hoạt động</option>
        </select>
        <select name="sort">
            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
            <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        </select>
        <button type="submit" class="filter-btn filter-btn-primary">Lọc</button>
        <a href="<?= BASE_URL ?>admin/combo/index" class="filter-btn filter-btn-secondary">Xóa lọc</a>
    </div>
</form>

<!-- Form xử lý xóa hàng loạt và Bảng dữ liệu -->
<form method="POST" action="<?= BASE_URL ?>admin/combo/index" id="bulk-action-form">
    <input type="hidden" name="action" value="delete_selected">
    <input type="hidden" name="selected_ids" id="selected-ids" value="">

    <!-- Thanh công cụ Bulk Actions (Mặc định ẩn) -->
    <div class="admin-bulk-bar" id="bulk-bar">
        <span class="bulk-count-text">Đã chọn <strong id="selected-count">0</strong> combo</span>
        <button type="button" class="btn-bulk-delete" onclick="confirmBulkDelete()">
            <i class="fas fa-trash-alt"></i> Xóa các mục đã chọn
        </button>
        <button type="button" class="btn-bulk-cancel" onclick="clearSelection()">Hủy</button>
    </div>

    <div class="admin-table-wrapper">
        <div class="admin-table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="col-cb"><input type="checkbox" id="check-all" onclick="toggleAll(this)"></th>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên Combo</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($combos)): ?>
                        <tr>
                            <td colspan="7" class="tbl-empty">
                                <i class="fas fa-box-open"></i>
                                <p>Không tìm thấy combo nào phù hợp!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($combos as $c): ?>
                            <tr>
                                <td class="col-cb">
                                    <input type="checkbox" class="cb-item" value="<?= $c['id'] ?>" onclick="updateBulkBar()">
                                </td>
                                <td><?= $c['id'] ?></td>
                                <td>
                                    <?php $img = !empty($c['image']) ? $c['image'] : 'default-combo.png'; ?>
                                    <img src="<?= BASE_URL ?>public/uploads/combos/<?= htmlspecialchars($img) ?>" alt="Combo" width="50" class="rounded" style="object-fit: cover;">
                                </td>
                                <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                                <td><span class="text-danger fw-bold"><?= number_format($c['price'], 0, ',', '.') ?> VNĐ</span></td>
                                <td>
                                    <?php if ($c['is_active']): ?>
                                        <span class="badge-st badge-active">Đang hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge-st badge-inactive">Ngừng hoạt động</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center tbl-actions">
                                    <a href="<?= BASE_URL ?>admin/combo/edit/<?= $c['id'] ?>" class="btn-tbl btn-tbl-edit"><i class="fas fa-edit"></i> Sửa</a>
                                    <a href="<?= BASE_URL ?>admin/combo/delete/<?= $c['id'] ?>" class="btn-tbl btn-tbl-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa combo này?');"><i class="fas fa-trash"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<!-- Mã JavaScript xử lý Checkbox -->
<script>
function toggleAll(source) {
    let checkboxes = document.querySelectorAll('.cb-item');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
    updateBulkBar();
}

function updateBulkBar() {
    let selected = document.querySelectorAll('.cb-item:checked');
    let count = selected.length;
    let bulkBar = document.getElementById('bulk-bar');
    
    document.getElementById('selected-count').innerText = count;

    if (count > 0) {
        bulkBar.classList.add('show');
    } else {
        bulkBar.classList.remove('show');
        document.getElementById('check-all').checked = false;
    }
}

function clearSelection() {
    let checkboxes = document.querySelectorAll('.cb-item');
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
    document.getElementById('check-all').checked = false;
    updateBulkBar();
}

function confirmBulkDelete() {
    let selected = document.querySelectorAll('.cb-item:checked');
    if (selected.length === 0) return;

    if (confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa ' + selected.length + ' combo đã chọn? Hành động này không thể hoàn tác!')) {
        let ids = [];
        selected.forEach(cb => ids.push(cb.value));
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>