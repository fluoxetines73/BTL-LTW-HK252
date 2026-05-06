<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav class="admin-breadcrumb mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Phim</li>
        </ol>
    </nav>

<!-- Breadcrumb -->
<nav class="admin-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Phim</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="page-header">
    <div class="page-actions">
        <a href="<?= BASE_URL ?>admin/movie/create" class="btn-add">
            <i class="fas fa-plus-circle me-1"></i> Thêm Phim Mới
        </a>
    </div>

    <!-- Thanh tìm kiếm & Lọc (Gộp Duy Nhất + Bạn mình) -->
    <div class="admin-filter-section mb-3 shadow-sm p-3 bg-white rounded">
        <form method="GET" action="<?= BASE_URL ?>admin/movie/index" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="search-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" class="search-input w-100" placeholder="Tìm tên phim, đạo diễn..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="filter-select form-select-sm w-100" onchange="this.form.submit()">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="now_showing" <?= ($status ?? '') === 'now_showing' ? 'selected' : '' ?>>Đang chiếu</option>
                    <option value="coming_soon" <?= ($status ?? '') === 'coming_soon' ? 'selected' : '' ?>>Sắp chiếu</option>
                    <option value="ended" <?= ($status ?? '') === 'ended' ? 'selected' : '' ?>>Ngừng chiếu</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="filter-select form-select-sm w-100" onchange="this.form.submit()">
                    <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-danger px-3">Lọc</button>
                <a href="<?= BASE_URL ?>admin/movie/index" class="btn btn-sm btn-outline-secondary">Xóa</a>
            </div>
        </form>
    </div>

    <!-- Form Xóa hàng loạt -->
    <form method="POST" action="<?= BASE_URL ?>admin/movie/index" id="bulk-action-form">
        <input type="hidden" name="action" value="delete_selected">
        <input type="hidden" name="selected_ids" id="selected-ids" value="">

        <div class="admin-bulk-bar mb-3 p-2 bg-light border rounded shadow-sm" id="bulk-bar" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small fw-bold">Chọn <span id="selected-count">0</span> phim</span>
                <button type="button" class="btn btn-danger btn-sm py-0" onclick="confirmBulkDelete()">Xóa</button>
            </div>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="admin-table-header bg-cgv-red text-white">
                        <tr>
                            <th class="text-center"><input type="checkbox" id="check-all" onclick="toggleAll(this)"></th>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Tên Phim</th>
                            <th>Thời lượng</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movies)): ?>
                            <tr><td colspan="7" class="text-center py-5">Không có phim phù hợp!</td></tr>
                        <?php else: ?>
                            <?php foreach ($movies as $m): ?>
                                <tr>
                                    <td class="text-center"><input type="checkbox" class="cb-item" value="<?= $m['id'] ?>" onclick="updateBulkBar()"></td>
                                    <td class="small text-muted"><?= $m['id'] ?></td>
                                    <td><img src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($m['poster'] ?: 'default_poster.jpg') ?>" width="45" height="65" class="rounded shadow-sm" style="object-fit: cover;"></td>
                                    <td class="fw-bold"><?= htmlspecialchars($m['title']) ?></td>
                                    <td><i class="far fa-clock me-1 text-muted"></i><?= $m['duration_min'] ?>p</td>
                                    <td>
                                        <?php 
                                            $badgeClass = ['now_showing' => 'bg-danger', 'coming_soon' => 'bg-warning text-dark', 'ended' => 'bg-secondary'][$m['status']] ?? 'bg-info';
                                            $statusName = ['now_showing' => 'Đang chiếu', 'coming_soon' => 'Sắp chiếu', 'ended' => 'Dừng chiếu'][$m['status']] ?? $m['status'];
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $statusName ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <a href="<?= BASE_URL ?>admin/movie/edit/<?= $m['id'] ?>" class="btn btn-sm btn-light border-end"><i class="fas fa-edit text-primary"></i></a>
                                            <a href="<?= BASE_URL ?>admin/movie/delete/<?= $m['id'] ?>" class="btn btn-sm btn-light" onclick="return confirm('Xóa phim này?');"><i class="fas fa-trash text-danger"></i></a>
                                        </div>
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

<!-- JavaScript tương tự như Combo -->
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
    if (confirm('Xác nhận xóa ' + count + ' phim đã chọn?')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(cb => cb.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>