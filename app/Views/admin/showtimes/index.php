<div class="container-fluid py-4">
    <!-- 1. Breadcrumb (Theme mới) -->
    <div class="admin-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Suất chiếu</li>
        </ol>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-actions">
            <a href="<?= BASE_URL ?>admin/showtime/create" class="btn-add">
                <i class="fas fa-plus-circle me-1"></i> Thêm Suất Chiếu Mới
            </a>
        </div>
    </div>

    <!-- 3. Bộ lọc tổng hợp (Kết hợp cả 2 bên) -->
    <form method="GET" action="<?= BASE_URL ?>admin/showtime/index" class="admin-search-filter-card shadow-sm mb-4">
        <div class="row g-3 p-3 align-items-end">
            <!-- Tìm kiếm từ khóa -->
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tìm kiếm:</label>
                <div class="search-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" class="search-input" placeholder="Tên phim, rạp..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
            </div>
            <!-- Lọc theo phòng (Duy Nhất) -->
            <div class="col-md-3">
                <label class="form-label small fw-bold">Phòng chiếu:</label>
                <select name="room_id" class="filter-select form-select">
                    <option value="all">Tất cả phòng chiếu</option>
                    <?php foreach($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>" <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($room['cinema_name'] . ' - ' . $room['room_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Lọc theo ngày (Bạn mình) -->
            <div class="col-md-2">
                <label class="form-label small fw-bold">Ngày chiếu:</label>
                <input type="date" name="date" class="filter-select form-control" value="<?= htmlspecialchars($dateFilter ?? '') ?>">
            </div>
            <!-- Sắp xếp -->
            <div class="col-md-2">
                <label class="form-label small fw-bold">Sắp xếp:</label>
                <select name="sort" class="filter-select form-select">
                    <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                    <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                </select>
            </div>
            <!-- Nút bấm -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-search flex-grow-1"><i class="fas fa-filter"></i> Lọc</button>
                <a href="<?= BASE_URL ?>admin/showtime/index" class="btn-reset"><i class="fas fa-times"></i></a>
            </div>
        </div>
    </form>

    <!-- 4. Form Xóa hàng loạt & Bảng dữ liệu -->
    <form method="POST" action="<?= BASE_URL ?>admin/showtime/index" id="bulk-action-form">
        <input type="hidden" name="action" value="delete_selected">
        <input type="hidden" name="selected_ids" id="selected-ids" value="">

        <!-- Thanh công cụ Bulk Actions (Mặc định ẩn) -->
        <div class="admin-bulk-bar mb-3 p-3 shadow-sm" id="bulk-bar" style="display: none; background: #fff5f5; border-left: 4px solid #e71a0f;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="bulk-count-text text-cgv-red fw-bold">
                    <i class="fas fa-check-square me-2"></i> Đã chọn <span id="selected-count">0</span> suất chiếu
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmBulkDelete()">
                        <i class="fas fa-trash-alt me-1"></i> Xóa các mục đã chọn
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">Hủy</button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-header bg-cgv-red text-white">
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <input type="checkbox" id="check-all" class="form-check-input" onclick="toggleAll(this)">
                                </th>
                                <th class="text-center">ID</th>
                                <th>Phim</th>
                                <th>Phòng & Rạp</th>
                                <th class="text-center">Thời gian chiếu</th>
                                <th class="text-center">Giá vé</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($showtimes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                                        Không tìm thấy suất chiếu nào phù hợp!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($showtimes as $st): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="cb-item form-check-input" value="<?= $st['id'] ?>" onclick="updateBulkBar()">
                                        </td>
                                        <td class="text-center text-muted"><?= $st['id'] ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($st['movie_title'] ?? 'N/A') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-light text-dark border">
                                                <i class="fas fa-film me-1 text-cgv-red"></i> <?= htmlspecialchars($st['room_name'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold text-primary"><?= date('H:i', strtotime($st['start_time'])) ?> - <?= date('H:i', strtotime($st['end_time'])) ?></div>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($st['start_time'])) ?></small>
                                        </td>
                                        <td class="text-center fw-bold text-cgv-red">
                                            <?= number_format($st['base_price'], 0, ',', '.') ?>đ
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>admin/showtime/edit/<?= $st['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                <a href="<?= BASE_URL ?>admin/showtime/delete/<?= $st['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa suất chiếu này?');"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript giữ nguyên từ bản HEAD của Duy Nhất -->
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
    
    if (count === 0) document.getElementById('check-all').checked = false;
}

function clearSelection() {
    document.querySelectorAll('.cb-item').forEach(cb => cb.checked = false);
    document.getElementById('check-all').checked = false;
    updateBulkBar();
}

function confirmBulkDelete() {
    let selected = document.querySelectorAll('.cb-item:checked');
    if (selected.length === 0) return;

    if (confirm('CẢNH BÁO: Bạn có chắc muốn xóa ' + selected.length + ' suất chiếu đã chọn?')) {
        let ids = Array.from(selected).map(cb => cb.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>