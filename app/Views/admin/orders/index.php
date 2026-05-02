<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Đơn Hàng</li>
    </ol>
</nav>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-receipt me-2"></i>Danh Sách Đơn Đặt Vé</h5>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Tổng Tiền</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?= $order['booking_code'] ?></td>
                            <td class="text-start">
                                <span class="fw-bold"><?= htmlspecialchars($order['full_name']) ?></span><br>
                                <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="fw-bold text-danger"><?= number_format($order['final_amount'], 0, ',', '.') ?>đ</td>
                            <td>
                                <?php if($order['payment_status'] == 'paid'): ?>
                                    <span class="badge bg-success">Đã thanh toán</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $statusClass = 'bg-secondary';
                                    $statusText = 'Không xác định';
                                    switch($order['status']) {
                                        case 'pending': $statusClass = 'bg-warning text-dark'; $statusText = 'Chờ xác nhận'; break;
                                        case 'confirmed': $statusClass = 'bg-primary'; $statusText = 'Đã xác nhận'; break;
                                        case 'completed': $statusClass = 'bg-success'; $statusText = 'Đã hoàn thành'; break;
                                        case 'cancelled': $statusClass = 'bg-danger'; $statusText = 'Đã hủy'; break;
                                    }
                                ?>
                                <span class="badge <?= $statusClass ?> px-2 py-1"><?= $statusText ?></span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-info text-white shadow-sm">
                                    <i class="fas fa-eye"></i> Xem Chi Tiết
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-muted py-4">Chưa có đơn hàng nào trong hệ thống.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>