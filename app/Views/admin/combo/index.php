<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="admin-breadcrumb mb-3">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Combo</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold">Danh Sách Combo</h2>
        <a href="<?= BASE_URL ?>admin/combo/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> <span class="d-none d-sm-inline">Thêm Combo</span>
        </a>
    </div>

    <div class="admin-filter-section mb-4 shadow-sm p-3 bg-white rounded border-start border-danger border-4">
        <form method="GET" action="<?= BASE_URL ?>admin/combo/index" class="row g-2 align-items-end">
            <div class="col-lg-4 col-md-12">
                <label class="form-label small fw-bold">Tìm kiếm:</label>
                <input type="text" name="q" class="form-control" placeholder="Tên combo..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold">Trạng thái:</label>
                <select name="status" class="form-select">
                    <option value="all">Tất cả</option>
                    <option value="active" <?= ($status ?? '') == 'active' ? 'selected' : '' ?>>Đang bán</option>
                    <option value="inactive" <?= ($status ?? '') == 'inactive' ? 'selected' : '' ?>>Ngừng bán</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold">Sắp xếp:</label>
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="price_asc" <?= ($sort ?? '') == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= ($sort ?? '') == 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-danger w-100">Lọc</button>
                <a href="<?= BASE_URL ?>admin/combo/index" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <form id="bulk-action-form" action="<?= BASE_URL ?>admin/combo/delete-multiple" method="POST">
        <input type="hidden" name="ids" id="selected-ids">
        <div id="bulk-bar" class="alert alert-dark mb-3 py-2 shadow-sm" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span>Đã chọn <strong id="selected-count">0</strong> combo</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">Xóa đã chọn</button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center"><input type="checkbox" class="form-check-input" onclick="toggleAll(this)"></th>
                            <th width="70">Ảnh</th>
                            <th>Tên Combo</th>
                            <th class="d-none d-md-table-cell">Mô tả</th>
                            <th>Giá</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($combos as $c): ?>
                        <tr>
                            <td class="text-center"><input type="checkbox" class="form-check-input cb-item" value="<?= $c['id'] ?>" onclick="updateBulkBar()"></td>
                            <td><img src="<?= BASE_URL . 'public/uploads/combos/' . ($c['image'] ?: 'default-combo.png') ?>" class="rounded" width="45" height="45" style="object-fit: cover;"></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($c['name']) ?></div>
                                <span class="badge bg-<?= $c['is_active'] ? 'success' : 'secondary' ?> d-md-none" style="font-size: 0.7rem;">
                                    <?= $c['is_active'] ? 'Active' : 'Off' ?>
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell small text-muted"><?= htmlspecialchars($c['description']) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($c['price'], 0, ',', '.') ?>đ</td>
                            <td class="text-end">
                                <div class="btn-group shadow-sm">
                                    <a href="<?= BASE_URL ?>admin/combo/edit/<?= $c['id'] ?>" class="btn btn-sm btn-white border"><i class="fas fa-edit text-primary"></i></a>
                                    <a href="<?= BASE_URL ?>admin/combo/delete/<?= $c['id'] ?>" class="btn btn-sm btn-white border text-danger" onclick="return confirm('Xóa?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-top py-3">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0 gap-1">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" href="?page=<?= $currentPage - 1 ?>&q=<?= $keyword ?>&status=<?= $status ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                            <li class="page-item <?= ($i==$currentPage) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 shadow-sm mx-1" href="?page=<?= $i ?>&q=<?= $keyword ?>&status=<?= $status ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" href="?page=<?= $currentPage + 1 ?>&q=<?= $keyword ?>&status=<?= $status ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
function toggleAll(s) { document.querySelectorAll('.cb-item').forEach(c => c.checked = s.checked); updateBulkBar(); }
function updateBulkBar() {
    let count = document.querySelectorAll('.cb-item:checked').length;
    document.getElementById('selected-count').innerText = count;
    document.getElementById('bulk-bar').style.display = count > 0 ? 'block' : 'none';
}
function confirmBulkDelete() {
    if (confirm('Xác nhận xóa hàng loạt?')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(c => c.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>