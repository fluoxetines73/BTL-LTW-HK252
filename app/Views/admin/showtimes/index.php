<div class="container-fluid py-4">
    <nav class="admin-breadcrumb mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Suất chiếu</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold">Danh Sách Suất Chiếu</h2>
        <a href="<?= BASE_URL ?>admin/showtime/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> <span class="d-none d-sm-inline">Thêm Suất Chiếu</span>
        </a>
    </div>

    <div class="admin-filter-section mb-4 shadow-sm p-3 bg-white rounded border-start border-danger border-4">
        <form method="GET" action="<?= BASE_URL ?>admin/showtime/index" class="row g-2 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold">Tìm kiếm:</label>
                <input type="text" name="q" class="form-control" placeholder="Tên phim, ID..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold">Ngày chiếu:</label>
                <input type="date" name="date" class="form-control" value="<?= $dateFilter ?? '' ?>">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold">Sắp xếp:</label>
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($sort ?? '') == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                    <option value="price_asc" <?= ($sort ?? '') == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-danger flex-grow-1">Lọc</button>
                <a href="<?= BASE_URL ?>admin/showtime/index" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <form id="bulk-action-form" action="<?= BASE_URL ?>admin/showtime/delete-multiple" method="POST">
        <input type="hidden" name="ids" id="selected-ids">
        <div id="bulk-bar" class="alert alert-dark mb-3 py-2" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span>Đã chọn <strong id="selected-count">0</strong> mục</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">Xóa / Hủy hàng loạt</button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center"><input type="checkbox" class="form-check-input" onclick="toggleAll(this)"></th>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Phim / Phòng</th>
                            <th>Thời Gian</th>
                            <th class="d-none d-sm-table-cell">Giá Vé</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($showtimes as $s): ?>
                        <tr class="<?= ($s['status'] === 'cancelled') ? 'opacity-50 bg-light' : '' ?>">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input cb-item" value="<?= $s['id'] ?>" onclick="updateBulkBar()">
                            </td>
                            <td class="d-none d-md-table-cell text-muted">#<?= $s['id'] ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($s['movie_title']) ?></div>
                                <div class="small text-muted"><i class="fas fa-door-open me-1"></i><?= htmlspecialchars($s['room_name']) ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold text-primary"><?= date('d/m/Y', strtotime($s['start_time'])) ?></div>
                                <div class="small"><?= date('H:i', strtotime($s['start_time'])) ?> - <?= date('H:i', strtotime($s['end_time'])) ?></div>
                            </td>
                            <td class="d-none d-sm-table-cell text-danger fw-bold">
                                <?= number_format($s['base_price'], 0, ',', '.') ?>đ
                            </td>
                            <td class="text-center">
                                <?php if ($s['status'] === 'scheduled'): ?>
                                    <span class="badge bg-success border border-success px-2 py-1" style="--bs-bg-opacity: .1; color: #198754 !important;">
                                        <i class="fas fa-check-circle me-1"></i> Hoạt động
                                    </span>
                                <?php elseif ($s['status'] === 'cancelled'): ?>
                                    <span class="badge bg-danger border border-danger px-2 py-1" style="--bs-bg-opacity: .1; color: #dc3545 !important;">
                                        <i class="fas fa-times-circle me-1"></i> Đã hủy
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary border border-secondary px-2 py-1" style="--bs-bg-opacity: .1; color: #6c757d !important;">
                                        <i class="fas fa-clock me-1"></i> Đã xong
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group shadow-sm">
                                    <a href="<?= BASE_URL ?>admin/showtime/edit/<?= $s['id'] ?>" class="btn btn-sm btn-white border">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/showtime/delete/<?= $s['id'] ?>" 
                                       class="btn btn-sm btn-white border text-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn Xóa/Hủy suất chiếu này?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="card-footer bg-white border-top py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0 gap-1">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" 
                               href="?page=<?= $currentPage - 1 ?>&q=<?= urlencode($keyword) ?>&date=<?= $dateFilter ?>&sort=<?= $sort ?>"
                               style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 shadow-sm mx-1" 
                                   href="?page=<?= $i ?>&q=<?= urlencode($keyword) ?>&date=<?= $dateFilter ?>&sort=<?= $sort ?>"
                                   style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" 
                               href="?page=<?= $currentPage + 1 ?>&q=<?= urlencode($keyword) ?>&date=<?= $dateFilter ?>&sort=<?= $sort ?>"
                               style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
function toggleAll(source) { 
    document.querySelectorAll('.cb-item').forEach(cb => cb.checked = source.checked); 
    updateBulkBar(); 
}

function updateBulkBar() {
    let count = document.querySelectorAll('.cb-item:checked').length;
    document.getElementById('selected-count').innerText = count;
    document.getElementById('bulk-bar').style.display = count > 0 ? 'block' : 'none';
}

function confirmBulkDelete() {
    if (confirm('Xác nhận xử lý hàng loạt các suất chiếu đã chọn? Lưu ý: Các suất đã có vé sẽ được chuyển sang trạng thái Hủy thay vì xóa cứng.')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(cb => cb.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>