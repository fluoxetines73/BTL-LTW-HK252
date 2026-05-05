<nav aria-label="breadcrumb" class="admin-breadcrumb mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Đơn Hàng</li>
    </ol>
</nav>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <div class="page-header">
            <h5 class="page-title"><i class="fas fa-receipt me-2"></i>Danh Sách Đơn Đặt Vé</h5>
        </div>
        
        <form method="GET" action="<?= BASE_URL ?>admin/order/index" class="admin-search-form">
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="q" class="search-input" 
                       placeholder="Tìm mã đơn hoặc tên khách hàng..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <?php if ($keyword !== '' || ($status ?? 'all') !== 'all'): ?>
                <a href="<?= BASE_URL ?>admin/order/index" class="btn-reset">
                    <i class="fas fa-times"></i> Reset
                </a>
            <?php endif; ?>
        </form>
        
        <div class="admin-filter-bar">
            <div class="filter-group">
                <span class="filter-label">Trạng thái:</span>
                <select name="status" class="filter-select" onchange="applyFilter(this)">
                    <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>Tất cả</option>
                    <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="confirmed" <?= ($status ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                    <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Sắp xếp:</span>
                <select name="sort" class="filter-select" onchange="applySort(this)">
                    <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                    <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá: Thấp → Cao</option>
                    <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá: Cao → Thấp</option>
                </select>
            </div>
        </div>
        
        <script>
        function applyFilter(select) {
            var url = new URL(window.location.href);
            url.searchParams.set('status', select.value);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
        
        function applySort(select) {
            var url = new URL(window.location.href);
            url.searchParams.set('sort', select.value);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
        </script>
    </div>
    
    <div class="card-body">
        <?php if (!empty($keyword) || (!empty($status) && $status !== 'all')): ?>
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Đang hiển thị kết quả <?= count($orders) ?> đơn hàng
                <?php if (!empty($keyword)): ?>
                    cho từ khóa "<?= htmlspecialchars($keyword) ?>"
                <?php endif; ?>
                <?php if (!empty($status) && $status !== 'all'): ?>
                    với trạng thái 
                    <?php 
                    $statusLabels = [
                        'pending' => 'Chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'completed' => 'Đã hoàn thành',
                        'cancelled' => 'Đã hủy'
                    ];
                    echo $statusLabels[$status] ?? $status;
                    ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="admin-table-header">
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
                            <td colspan="7" class="text-muted py-4">
                                <?php if (!empty($keyword) || (!empty($status) && $status !== 'all')): ?>
                                    Không tìm thấy đơn hàng nào phù hợp với tiêu chí tìm kiếm.
                                <?php else: ?>
                                    Chưa có đơn hàng nào trong hệ thống.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>