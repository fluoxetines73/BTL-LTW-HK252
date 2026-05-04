<div class="mb-3">
    <a href="<?= BASE_URL ?>admin/order/index" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user text-primary me-2"></i>Thông Tin Khách Hàng</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Họ và tên:</div>
                    <div class="col-sm-8 fw-bold text-dark"><?= htmlspecialchars($order['full_name']) ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Email:</div>
                    <div class="col-sm-8"><?= htmlspecialchars($order['email']) ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Số điện thoại:</div>
                    <div class="col-sm-8"><?= htmlspecialchars($order['phone'] ?? 'Không cung cấp') ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-muted">Ngày đặt vé:</div>
                    <div class="col-sm-8"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-ticket-alt text-warning me-2"></i>Chi Tiết Vé Xem Phim</h6>
            </div>
            <div class="card-body">
                <h5 class="text-primary fw-bold mb-3"><?= htmlspecialchars($order['movie_title']) ?></h5>
                <p class="mb-1"><i class="fas fa-clock me-2 text-muted"></i>Suất chiếu: <strong><?= date('H:i - d/m/Y', strtotime($order['start_time'])) ?></strong></p>
                <p class="mb-3"><i class="fas fa-door-open me-2 text-muted"></i>Phòng chiếu: <strong><?= htmlspecialchars($order['room_name']) ?></strong></p>
                
                <h6 class="fw-bold mt-4 mb-2 border-bottom pb-2">Danh sách ghế đã chọn:</h6>
                <?php if(!empty($tickets)): ?>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php foreach($tickets as $ticket): ?>
                            <span class="badge bg-dark fs-6 px-3 py-2 shadow-sm">
                                Ghế <?= htmlspecialchars($ticket['row_label']) ?><?= htmlspecialchars($ticket['col_number']) ?> 
                                <small class="fw-normal text-warning ms-1">(<?= htmlspecialchars($ticket['seat_type']) ?>)</small>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Không có dữ liệu ghế.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-hamburger text-success me-2"></i>Bắp Nước Mua Kèm</h6>
            </div>
            <div class="card-body">
                <?php if(!empty($combos)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($combos as $combo): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <img src="<?= BASE_URL ?>public/uploads/combos/<?= htmlspecialchars($combo['image']) ?>" alt="combo" class="rounded shadow-sm me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($combo['name']) ?></h6>
                                        <small class="text-muted">Số lượng: <strong><?= $combo['quantity'] ?></strong></small>
                                    </div>
                                </div>
                                <span class="fw-bold text-danger"><?= number_format($combo['price'] * $combo['quantity'], 0, ',', '.') ?>đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Khách hàng không mua kèm bắp nước.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-light mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-uppercase text-muted mb-3">Tổng Thanh Toán</h6>
                <h2 class="text-danger fw-bold mb-3"><?= number_format($order['final_amount'], 0, ',', '.') ?> <small class="fs-6">VNĐ</small></h2>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Mã đơn:</span>
                    <span class="fw-bold text-dark">#<?= htmlspecialchars($order['booking_code']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Trạng thái thanh toán:</span>
                    <?php if($order['payment_status'] == 'paid'): ?>
                        <span class="badge bg-success">Đã thanh toán</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-sliders-h text-info me-2"></i>Cập Nhật Trạng Thái</h6>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>admin/order/updateStatus/<?= $order['id'] ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-muted">Trạng thái xác nhận vé:</label>
                        <select name="status" class="form-select fw-bold">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Cập Nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>