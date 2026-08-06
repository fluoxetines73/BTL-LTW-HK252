<div class="container-fluid py-4">
    <nav class="admin-breadcrumb mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Phim</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark fw-bold">Danh Sách Phim</h2>
        <a href="<?= BASE_URL ?>admin/movie/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Thêm Phim Mới
        </a>
    </div>

    <div class="admin-filter-section mb-4 shadow-sm p-3 bg-white rounded border-start border-primary border-4">
        <form method="GET" action="<?= BASE_URL ?>admin/movie/index" class="row g-3">
            <div class="col-lg-4 col-md-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" 
                           placeholder="Tìm tên phim, đạo diễn..." 
                           value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <select name="status" class="form-select">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="now_showing" <?= ($status ?? '') == 'now_showing' ? 'selected' : '' ?>>Đang chiếu</option>
                    <option value="coming_soon" <?= ($status ?? '') == 'coming_soon' ? 'selected' : '' ?>>Sắp chiếu</option>
                    <option value="ended" <?= ($status ?? '') == 'ended' ? 'selected' : '' ?>>Đã kết thúc</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($sort ?? '') == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger w-100">Lọc</button>
                    <a href="<?= BASE_URL ?>admin/movie/index" class="btn btn-outline-secondary px-3"><i class="fas fa-undo"></i></a>
                </div>
            </div>
        </form>
    </div>

    <form id="bulk-action-form" action="<?= BASE_URL ?>admin/movie/delete-multiple" method="POST">
        <input type="hidden" name="ids" id="selected-ids">
        
        <div id="bulk-bar" class="alert alert-dark mb-3 py-2 shadow-sm" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span>Đã chọn <strong id="selected-count">0</strong> phim</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">
                    <i class="fas fa-trash-alt me-1"></i> Xóa các mục đã chọn
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">
                                <input type="checkbox" class="form-check-input" onclick="toggleAll(this)">
                            </th>
                            <th width="60">ID</th>
                            <th width="80">Poster</th>
                            <th>Tên Phim</th>
                            <th class="d-none d-md-table-cell">Thể loại</th>
                            <th class="d-none d-lg-table-cell">Thời lượng</th>
                            <th>Trạng thái</th>
                            <th width="120" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movies)): ?>
                            <tr><td colspan="8" class="text-center py-5 text-muted">Không tìm thấy phim nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($movies as $m): ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input cb-item" value="<?= $m['id'] ?>" onclick="updateBulkBar()">
                                    </td>
                                    <td><span class="text-muted small">#<?= $m['id'] ?></span></td>
                                    <td>
                                        <img src="<?= BASE_URL . 'public/uploads/movies/' . ($m['poster'] ?: 'default-poster.jpg') ?>" 
                                             class="rounded" width="45" height="60" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-truncate" style="max-width: 200px;"><?= htmlspecialchars($m['title']) ?></div>
                                        <div class="small text-muted d-md-none"><?= htmlspecialchars($m['duration_min']) ?>p</div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <small class="text-muted"><?= htmlspecialchars($m['genre_names'] ?? 'N/A') ?></small>
                                    </td>
                                    <td class="d-none d-lg-table-cell"><?= $m['duration_min'] ?>p</td>
                                    <td>
                                        <?php 
                                            $badges = ['now_showing' => 'success', 'coming_soon' => 'primary', 'ended' => 'secondary'];
                                            $labels = ['now_showing' => 'Đang chiếu', 'coming_soon' => 'Sắp chiếu', 'ended' => 'Đã kết thúc'];
                                            $s = $m['status'];
                                        ?>
                                        <span class="badge bg-<?= $badges[$s] ?? 'dark' ?>"><?= $labels[$s] ?? $s ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="<?= BASE_URL ?>admin/movie/edit/<?= $m['id'] ?>" class="btn btn-sm btn-white border shadow-sm"><i class="fas fa-edit text-primary"></i></a>
                                            <a href="<?= BASE_URL ?>admin/movie/delete/<?= $m['id'] ?>" 
                                               class="btn btn-sm btn-white border shadow-sm" 
                                               onclick="return confirm('Xác nhận xóa phim này?')">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="card-footer bg-white border-top py-2 py-md-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0 gap-1">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" 
                               href="?page=<?= $currentPage - 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&sort=<?= $sort ?>"
                               style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <?php 
                        // Hiển thị tối đa 3 trang xung quanh trang hiện tại để tránh tràn màn hình mobile
                        $start = max(1, $currentPage - 1);
                        $end = min($totalPages, $currentPage + 1);
                        
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 shadow-sm mx-1" 
                                   href="?page=<?= $i ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&sort=<?= $sort ?>"
                                   style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" 
                               href="?page=<?= $currentPage + 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&sort=<?= $sort ?>"
                               style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="text-center mt-2 d-md-none">
                    <small class="text-muted">Trang <?= $currentPage ?> / <?= $totalPages ?></small>
                </div>
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
    let bar = document.getElementById('bulk-bar');
    document.getElementById('selected-count').innerText = count;
    bar.style.display = count > 0 ? 'block' : 'none';
}
function confirmBulkDelete() {
    let count = document.querySelectorAll('.cb-item:checked').length;
    if (confirm('Xác nhận xóa hàng loạt ' + count + ' phim đã chọn?')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(cb => cb.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>