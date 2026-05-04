<div class="admin-page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0"><i class="fas fa-clock me-2"></i> <?= htmlspecialchars($title ?? 'Quản lý Suất chiếu') ?></h2>
    <a href="<?= BASE_URL ?>admin/showtime/create" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Thêm Suất Chiếu</a>
</div>

<!-- Thanh tìm kiếm và lọc -->
<form method="GET" action="<?= BASE_URL ?>admin/showtime/index" class="admin-filter-bar">
    <div class="admin-filter-search">
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="q" placeholder="Tìm tên phim, tên phòng..." value="<?= htmlspecialchars($keyword ?? '') ?>">
    </div>
    <div class="admin-filter-controls">
        <!-- Lọc theo Phòng chiếu -->
        <select name="room_id" class="form-select">
            <option value="all">Tất cả phòng chiếu</option>
            <?php foreach($rooms as $room): ?>
                <option value="<?= $room['id'] ?>" <?= (isset($_GET['room_id']) && $_GET['room_id'] == $room['id']) ? 'selected' : '' ?>>
                    <!-- Hiển thị: Tên Rạp - Tên Phòng (Ví dụ: CGV Vincom - Phòng 1) -->
                    <?= htmlspecialchars($room['cinema_name'] . ' - ' . $room['room_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <!-- Sắp xếp -->
        <select name="sort">
            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
            <option value="time_asc" <?= ($sort ?? '') === 'time_asc' ? 'selected' : '' ?>>Giờ chiếu: Tăng dần</option>
            <option value="time_desc" <?= ($sort ?? '') === 'time_desc' ? 'selected' : '' ?>>Giờ chiếu: Giảm dần</option>
        </select>
        
        <button type="submit" class="filter-btn filter-btn-primary">Lọc</button>
        <a href="<?= BASE_URL ?>admin/showtime/index" class="filter-btn filter-btn-secondary">Xóa lọc</a>
    </div>
</form>

<!-- Form xử lý xóa hàng loạt và Bảng dữ liệu -->
<form method="POST" action="<?= BASE_URL ?>admin/showtime/index" id="bulk-action-form">
    <input type="hidden" name="action" value="delete_selected">
    <input type="hidden" name="selected_ids" id="selected-ids" value="">

    <!-- Thanh công cụ Bulk Actions (Mặc định ẩn) -->
    <div class="admin-bulk-bar" id="bulk-bar">
        <span class="bulk-count-text">Đã chọn <strong id="selected-count">0</strong> suất chiếu</span>
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
                        <th>Tên Phim</th>
                        <th>Phòng chiếu</th>
                        <th>Thời gian</th>
                        <th>Giá vé gốc</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($showtimes)): ?>
                        <tr>
                            <td colspan="7" class="tbl-empty">
                                <i class="fas fa-clock"></i>
                                <p>Không tìm thấy suất chiếu nào phù hợp!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($showtimes as $st): ?>
                            <tr>
                                <td class="col-cb">
                                    <input type="checkbox" class="cb-item" value="<?= $st['id'] ?>" onclick="updateBulkBar()">
                                </td>
                                <td><?= $st['id'] ?></td>
                                <td><strong><?= htmlspecialchars($st['movie_title'] ?? 'N/A') ?></strong></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($st['room_name'] ?? 'N/A') ?></span></td>
                                <td>
                                    <!-- Định dạng hiển thị thời gian thân thiện -->
                                    <div class="fw-bold text-primary"><?= date('H:i', strtotime($st['start_time'])) ?> - <?= date('H:i', strtotime($st['end_time'])) ?></div>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($st['start_time'])) ?></small>
                                </td>
                                <td><span class="text-danger fw-bold"><?= number_format($st['base_price'], 0, ',', '.') ?> VNĐ</span></td>
                                <td class="text-center tbl-actions">
                                    <a href="<?= BASE_URL ?>admin/showtime/edit/<?= $st['id'] ?>" class="btn-tbl btn-tbl-edit"><i class="fas fa-edit"></i> Sửa</a>
                                    <a href="<?= BASE_URL ?>admin/showtime/delete/<?= $st['id'] ?>" class="btn-tbl btn-tbl-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này? Các vé đã đặt có thể bị ảnh hưởng.');"><i class="fas fa-trash"></i> Xóa</a>
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

    if (confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa ' + selected.length + ' suất chiếu đã chọn? Hành động này có thể làm lỗi các đơn đặt vé liên quan!')) {
        let ids = [];
        selected.forEach(cb => ids.push(cb.value));
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>