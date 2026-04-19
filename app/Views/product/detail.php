<style>
    /* CSS bọc riêng cho trang chi tiết, không ảnh hưởng layout chung */
    .movie-detail-wrapper { background-color: #f8f9fa; padding-bottom: 50px; }
    .movie-banner-dark {
        background: linear-gradient(to right, #141414, #2b2b2b);
        color: white; border-radius: 12px; padding: 30px; margin-top: 30px;
    }
    .poster-img {
        border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.5); width: 100%;
    }
    .combo-item {
        background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 12px; margin-bottom: 12px; transition: 0.2s;
    }
    .combo-item:hover { border-color: #dc3545; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .btn-checkout { background-color: #e50914; color: white; transition: 0.3s; }
    .btn-checkout:hover { background-color: #b20710; color: white; transform: scale(1.02); }
</style>

<div class="movie-detail-wrapper">
    <div class="container py-4">
        
        <a href="<?= BASE_URL ?>" class="text-decoration-none text-muted mb-3 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Trở về danh sách phim
        </a>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="movie-banner-dark shadow-lg">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <img src="<?= !empty($movie['poster']) ? BASE_URL . 'public/uploads/movies/' . $movie['poster'] : 'https://via.placeholder.com/300x450?text=Poster' ?>" class="poster-img" alt="Poster">
                        </div>
                        <div class="col-md-8">
                            <h1 class="fw-bold text-danger mb-2"><?= htmlspecialchars($movie['title']) ?></h1>
                            <p class="text-light mb-3"><i class="fas fa-calendar-alt text-info me-2"></i>Khởi chiếu: <?= date('d/m/Y', strtotime($movie['release_date'])) ?></p>
                            
                            <div class="mb-4">
                                <span class="badge bg-warning text-dark fs-6 me-2"><?= $movie['age_rating'] ?></span>
                                <span class="badge bg-secondary fs-6"><i class="fas fa-clock me-1"></i><?= $movie['duration_min'] ?> phút</span>
                            </div>
                            
                            <table class="table table-borderless text-light table-sm">
                                <tr>
                                    <td width="120" class="text-muted">Đạo diễn:</td>
                                    <td class="fw-bold"><?= htmlspecialchars($movie['director']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Diễn viên:</td>
                                    <td class="fw-bold"><?= htmlspecialchars($movie['cast']) ?></td>
                                </tr>
                            </table>

                            <hr class="border-secondary my-4">
                            <h5 class="text-info fw-bold mb-3"><i class="fas fa-align-left me-2"></i>Nội dung tóm tắt</h5>
                            <p class="text-light" style="line-height: 1.6; opacity: 0.9;">
                                <?= nl2br(htmlspecialchars($movie['description'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-danger text-white py-3 text-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2"></i>Giỏ Hàng Của Bạn</h5>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>product/checkout" method="POST">
                            <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                            <input type="hidden" name="ticket_price" value="100000"> 

                            <div class="mb-3">
                                <label class="fw-bold form-label text-dark">Ngày xem</label>
                                <input type="date" class="form-control" name="show_date" required min="<?= date('Y-m-d') ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label class="fw-bold form-label text-dark">Số lượng vé <span class="text-danger">(100.000đ/vé)</span></label>
                                <input type="number" class="form-control form-control-lg text-center fw-bold ticket-qty" name="ticket_qty" value="1" min="1" max="10" required>
                            </div>

                            <hr class="my-4">
                            
                            <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-hamburger text-warning me-2"></i>Thêm Combo Bắp Nước</h6>
                            
                            <div style="max-height: 280px; overflow-y: auto; padding-right: 5px;" class="mb-3">
                                <?php if (!empty($combos)): ?>
                                    <?php foreach ($combos as $combo): ?>
                                    <?php if($combo['is_active'] == 1): ?>
                                    <div class="combo-item d-flex align-items-center justify-content-between">
                                        <div style="width: 70%;">
                                            <div class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($combo['name']) ?>"><?= htmlspecialchars($combo['name']) ?></div>
                                            <div class="text-danger small fw-bold combo-price" data-price="<?= $combo['price'] ?>">
                                                <?= number_format($combo['price'], 0, ',', '.') ?>đ
                                            </div>
                                        </div>
                                        <div style="width: 25%;">
                                            <input type="number" name="combos[<?= $combo['id'] ?>]" class="form-control form-control-sm text-center combo-qty" value="0" min="0" max="5">
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted small">Hiện tại chưa có Combo bắp nước nào.</p>
                                <?php endif; ?>
                            </div>

                            <div class="bg-light p-3 rounded mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark fs-6">TỔNG TIỀN:</span>
                                    <span class="fw-bold fs-4 text-danger" id="totalPrice">100.000đ</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-checkout w-100 fw-bold btn-lg">
                                Xác Nhận Thanh Toán <i class="fas fa-check-circle ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ticketPrice = 100000; // Cố định 1 vé = 100k
        const ticketInput = document.querySelector('.ticket-qty');
        const comboInputs = document.querySelectorAll('.combo-qty');
        const totalDisplay = document.getElementById('totalPrice');

        function calculateTotal() {
            // Tiền vé
            let qty = parseInt(ticketInput.value);
            if (isNaN(qty) || qty < 1) qty = 0; // Tránh lỗi xóa trống số
            let total = qty * ticketPrice;
            
            // Tiền combo
            comboInputs.forEach(input => {
                let cQty = parseInt(input.value) || 0;
                let cPrice = parseInt(input.closest('.combo-item').querySelector('.combo-price').dataset.price);
                total += cQty * cPrice;
            });

            // Định dạng tiền VNĐ
            totalDisplay.innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        }

        // Bắt sự kiện mỗi khi bấm thay đổi số lượng
        ticketInput.addEventListener('input', calculateTotal);
        comboInputs.forEach(input => input.addEventListener('input', calculateTotal));
    });
</script>