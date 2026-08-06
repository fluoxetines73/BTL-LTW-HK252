<div class="container-fluid py-4">
    <nav class="admin-breadcrumb mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Đơn hàng</li>
        </ol>
    </nav>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark fw-bold">Danh Sách Đơn Hàng</h2>
    </div>

    <div class="admin-filter-section mb-4 shadow-sm p-3 bg-white rounded border-start border-danger border-4">
        <form method="GET" action="<?= BASE_URL ?>admin/order/index" class="row g-2">
            <div class="col-lg-3 col-md-12">
                <label class="form-label small fw-bold">Tìm kiếm:</label>
                <input type="text" name="q" class="form-control" placeholder="Mã đơn, khách hàng..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label small fw-bold">Trạng thái vé:</label>
                <select name="status" class="form-select">
                    <option value="all">Mọi trạng thái</option>
                    <option value="pending" <?= ($status=='pending')?'selected':'' ?>>Chờ xử lý</option>
                    <option value="confirmed" <?= ($status=='confirmed')?'selected':'' ?>>Xác nhận</option>
                    <option value="completed" <?= ($status=='completed')?'selected':'' ?>>Hoàn tất</option>
                    <option value="cancelled" <?= ($status=='cancelled')?'selected':'' ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="form-label small fw-bold">Thanh toán:</label>
                <select name="payment_status" class="form-select">
                    <option value="all">Mọi thanh toán</option>
                    <option value="unpaid" <?= ($paymentStatus=='unpaid')?'selected':'' ?>>Chưa trả</option>
                    <option value="paid" <?= ($paymentStatus=='paid')?'selected':'' ?>>Đã trả</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label small fw-bold">Sắp xếp:</label>
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($sort=='newest')?'selected':'' ?>>Mới nhất</option>
                    <option value="price_desc" <?= ($sort=='price_desc')?'selected':'' ?>>Giá cao nhất</option>
                    <option value="price_asc" <?= ($sort=='price_asc')?'selected':'' ?>>Giá thấp nhất</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-1 align-items-end">
                <button type="submit" class="btn btn-danger w-100">Lọc</button>
                <a href="<?= BASE_URL ?>admin/order/index" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <form id="bulk-action-form" action="<?= BASE_URL ?>admin/order/deleteMultiple" method="POST">
        <input type="hidden" name="ids" id="selected-ids">
        <div id="bulk-bar" class="alert alert-dark mb-3 py-2" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span>Đã chọn <strong id="selected-count">0</strong> đơn hàng</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">Hủy / Xóa mục đã chọn</button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40"><input type="checkbox" class="form-check-input" onclick="toggleAll(this)"></th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th class="d-none d-lg-table-cell">Ngày mua</th>
                            <th>Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end">Xem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                        <tr class="<?= ($o['status'] === 'cancelled') ? 'opacity-50 bg-light text-muted' : '' ?>">
                            <td><input type="checkbox" class="form-check-input cb-item" value="<?= $o['id'] ?>" onclick="updateBulkBar()"></td>
                            <td class="fw-bold">#<?= $o['booking_code'] ?></td>
                            <td>
                                <div class="small fw-bold text-dark"><?= htmlspecialchars($o['full_name']) ?></div>
                                <div class="text-muted d-none d-md-block" style="font-size: 0.75rem;"><?= htmlspecialchars($o['email']) ?></div>
                            </td>
                            <td class="d-none d-lg-table-cell small"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($o['final_amount'], 0, ',', '.') ?>đ</td>
                            <td class="text-center">
                                <?php 
                                    $colors = [
                                        'pending'   => 'warning text-dark', 
                                        'confirmed' => 'primary', 
                                        'completed' => 'success', 
                                        'cancelled' => 'danger'
                                    ];
                                    $labels = [
                                        'pending'   => 'Chờ xử lý', 
                                        'confirmed' => 'Đã xác nhận', 
                                        'completed' => 'Hoàn tất', 
                                        'cancelled' => 'Đã hủy'
                                    ];
                                ?>
                                <span class="badge bg-<?= $colors[$o['status']] ?? 'secondary' ?> px-2 py-1">
                                    <?= $labels[$o['status']] ?? $o['status'] ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>admin/order/detail/<?= $o['id'] ?>" class="btn btn-sm btn-white border shadow-sm">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
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
                               href="?page=<?= $currentPage - 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" 
                               style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                            <li class="page-item <?= ($i==$currentPage) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 shadow-sm mx-1" 
                                   href="?page=<?= $i ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" 
                                   style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" 
                               href="?page=<?= $currentPage + 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" 
                               style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
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
    document.querySelectorAll('.cb-item').forEach(c => c.checked = source.checked); 
    updateBulkBar(); 
}

function updateBulkBar() {
    let count = document.querySelectorAll('.cb-item:checked').length;
    document.getElementById('selected-count').innerText = count;
    document.getElementById('bulk-bar').style.display = count > 0 ? 'block' : 'none';
}

function confirmBulkDelete() {
    let count = document.querySelectorAll('.cb-item:checked').length;
    if (confirm('Bạn có chắc muốn hủy/xóa ' + count + ' đơn hàng đã chọn? Hệ thống sẽ ưu tiên giữ lại lịch sử giao dịch bằng cách chuyển sang trạng thái "Đã hủy".')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(c => c.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>