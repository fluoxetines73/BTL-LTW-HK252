<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 bg-dark text-white rounded-3">
                <div class="card-header bg-danger text-center py-3">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2"></i>Xác Nhận Đặt Vé</h3>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <!-- Thông tin Phim -->
                    <div class="row border-bottom border-secondary pb-4 mb-4">
                        <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                            <img src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" class="img-fluid rounded shadow" alt="Poster">
                        </div>
                        <div class="col-md-9">
                            <h2 class="text-danger fw-bold mb-3"><?= htmlspecialchars($movie['title']) ?></h2>
                            <p class="mb-2"><i class="far fa-calendar-alt text-info me-2"></i><strong>Ngày chiếu:</strong> <?= date('d/m/Y', strtotime($showtime['start_time'])) ?></p>
                            <p class="mb-2"><i class="far fa-clock text-info me-2"></i><strong>Giờ chiếu:</strong> <span class="badge bg-danger fs-6"><?= date('H:i', strtotime($showtime['start_time'])) ?></span></p>
                        </div>
                    </div>

                    <!-- Thông tin Ghế ngồi -->
                    <h5 class="text-info fw-bold mb-3">Chi tiết Ghế</h5>
                    <div class="bg-secondary bg-opacity-25 p-3 rounded mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng: <strong><?= $ticketQty ?> ghế</strong></span>
                            <span>Danh sách ghế: <strong class="text-warning"><?= htmlspecialchars($selectedSeats) ?></strong></span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold text-light border-top border-secondary pt-2 mt-2">
                            <span>Thành tiền vé:</span>
                            <span><?= number_format($ticketTotal, 0, ',', '.') ?> VNĐ</span>
                        </div>
                    </div>

                    <!-- Thông tin Combo Bắp Nước -->
                    <?php if (!empty($selectedCombos)): ?>
                    <h5 class="text-info fw-bold mb-3">Bắp nước (Combo)</h5>
                    <div class="bg-secondary bg-opacity-25 p-3 rounded mb-4">
                        <?php foreach ($selectedCombos as $c): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= htmlspecialchars($c['name']) ?> (x<?= $c['qty'] ?>)</span>
                                <span><?= number_format($c['subtotal'], 0, ',', '.') ?> VNĐ</span>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between fw-bold text-light border-top border-secondary pt-2 mt-2">
                            <span>Thành tiền Combo:</span>
                            <span><?= number_format($comboTotal, 0, ',', '.') ?> VNĐ</span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Tổng kết thanh toán -->
                    <div class="bg-light text-dark p-4 rounded-3 mb-4 text-center">
                        <h5 class="mb-2">TỔNG CỘNG CẦN THANH TOÁN</h5>
                        <h1 class="text-danger fw-bold mb-0"><?= number_format($grandTotal, 0, ',', '.') ?> VNĐ</h1>
                    </div>

                    <!-- Nút hành động -->
                    <form action="<?= BASE_URL ?>product/processPayment" method="POST">
                        <input type="hidden" name="showtime_id" value="<?= $showtime['id'] ?>">
                        <input type="hidden" name="selected_seats" value="<?= htmlspecialchars($selectedSeats) ?>">
                        <input type="hidden" name="grand_total" value="<?= $grandTotal ?>">
                        
                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-outline-light px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-danger px-5 py-2 fw-bold">
                                Tiến Hành Thanh Toán <i class="fas fa-credit-card ms-2"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>