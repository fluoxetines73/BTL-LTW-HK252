<div class="container-fluid py-4">
    <nav class="admin-breadcrumb mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Đơn hàng</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark fw-bold">Danh Sách Đơn Hàng</h2>
    </div>

    <div class="admin-filter-section mb-4 shadow-sm p-3 bg-white rounded border-start border-danger border-4">
        <form method="GET" action="<?= BASE_URL ?>admin/order/index" class="row g-2">
            <div class="col-lg-3 col-md-12">
                <input type="text" name="q" class="form-control" placeholder="Mã đơn, khách hàng..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="status" class="form-select">
                    <option value="all">Mọi trạng thái</option>
                    <option value="pending" <?= ($status=='pending')?'selected':'' ?>>Chờ xử lý</option>
                    <option value="confirmed" <?= ($status=='confirmed')?'selected':'' ?>>Xác nhận</option>
                    <option value="completed" <?= ($status=='completed')?'selected':'' ?>>Hoàn tất</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="payment_status" class="form-select">
                    <option value="all">Mọi thanh toán</option>
                    <option value="unpaid" <?= ($paymentStatus=='unpaid')?'selected':'' ?>>Chưa trả</option>
                    <option value="paid" <?= ($paymentStatus=='paid')?'selected':'' ?>>Đã trả</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <select name="sort" class="form-select">
                    <option value="newest" <?= ($sort=='newest')?'selected':'' ?>>Mới nhất</option>
                    <option value="price_desc" <?= ($sort=='price_desc')?'selected':'' ?>>Giá cao nhất</option>
                    <option value="price_asc" <?= ($sort=='price_asc')?'selected':'' ?>>Giá thấp nhất</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex gap-1">
                <button type="submit" class="btn btn-danger w-100">Lọc</button>
                <a href="<?= BASE_URL ?>admin/order/index" class="btn btn-outline-secondary"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <form id="bulk-action-form" action="<?= BASE_URL ?>admin/order/delete-multiple" method="POST">
        <input type="hidden" name="ids" id="selected-ids">
        <div id="bulk-bar" class="alert alert-dark mb-3 py-2" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <span>Đã chọn <strong id="selected-count">0</strong> đơn</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">Xóa mục đã chọn</button>
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
                            <th>Trạng thái</th>
                            <th class="text-end">Xem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input cb-item" value="<?= $o['id'] ?>" onclick="updateBulkBar()"></td>
                            <td class="fw-bold">#<?= $o['booking_code'] ?></td>
                            <td>
                                <div class="small fw-bold"><?= htmlspecialchars($o['full_name']) ?></div>
                                <div class="text-muted d-none d-md-block" style="font-size: 0.75rem;"><?= htmlspecialchars($o['email']) ?></div>
                            </td>
                            <td class="d-none d-lg-table-cell small"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($o['final_amount'], 0, ',', '.') ?>đ</td>
                            <td>
                                <?php 
                                    $c = ['pending'=>'warning', 'confirmed'=>'primary', 'completed'=>'success', 'cancelled'=>'danger'];
                                    $t = ['pending'=>'Chờ', 'confirmed'=>'XN', 'completed'=>'Xong', 'cancelled'=>'Hủy'];
                                ?>
                                <span class="badge bg-<?= $c[$o['status']] ?? 'secondary' ?>"><?= $t[$o['status']] ?? $o['status'] ?></span>
                            </td>
                            <td class="text-end">
                                <a href="<?= BASE_URL ?>admin/order/detail/<?= $o['id'] ?>" class="btn btn-sm btn-white border shadow-sm"><i class="fas fa-eye text-primary"></i></a>
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
                            <a class="page-link rounded-circle border-0 shadow-sm" href="?page=<?= $currentPage - 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                            <li class="page-item <?= ($i==$currentPage) ? 'active' : '' ?>">
                                <a class="page-link rounded-circle border-0 shadow-sm mx-1" href="?page=<?= $i ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-circle border-0 shadow-sm" href="?page=<?= $currentPage + 1 ?>&q=<?= urlencode($keyword) ?>&status=<?= $status ?>&payment_status=<?= $paymentStatus ?>&sort=<?= $sort ?>" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-chevron-right"></i></a>
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
    if (confirm('Xác nhận xóa hàng loạt ' + document.querySelectorAll('.cb-item:checked').length + ' đơn hàng?')) {
        let ids = Array.from(document.querySelectorAll('.cb-item:checked')).map(c => c.value);
        document.getElementById('selected-ids').value = ids.join(',');
        document.getElementById('bulk-action-form').submit();
    }
}
</script>