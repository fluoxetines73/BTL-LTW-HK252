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
    
    /* CSS cho sơ đồ ghế */
    .seat-map-container { overflow-x: auto; }
    .screen { letter-spacing: 5px; opacity: 0.8; }
    .seat { 
        width: 32px; height: 32px; 
        background-color: #fff; border: 1px solid #ccc; 
        cursor: pointer; transition: 0.2s; 
        font-size: 10px; display: flex; align-items: center; justify-content: center;
        color: transparent; /* Ẩn chữ đi cho đẹp, khi hover mới hiện */
    }
    .seat:hover { background-color: #e0e0e0; color: #333; }
    .seat.selected { background-color: #28a745; color: white; border-color: #28a745; }
    .seat.occupied { background-color: #dc3545; cursor: not-allowed; border-color: #dc3545; color: white; }
    
    .seat-sample { width: 20px; height: 20px; border: 1px solid #ccc; border-radius: 4px; }
    .seat-sample.available { background-color: #fff; }
    .seat-sample.selected { background-color: #28a745; border-color: #28a745; }
    .seat-sample.occupied { background-color: #dc3545; border-color: #dc3545; }
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
                            <img src="<?= !empty($movie['poster']) ? BASE_URL . htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/300x450?text=Poster' ?>" class="poster-img" alt="Poster">
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
                                <tr>
                                    <td class="text-muted">Thể loại:</td>
                                    <td class="fw-bold text-info">
                                        <?= !empty($movie['genre_names']) ? htmlspecialchars($movie['genre_names']) : 'Đang cập nhật' ?>
                                    </td>
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

            <!-- PHẦN GIỎ HÀNG BÊN PHẢI -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-danger text-white py-3 text-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2"></i>Giỏ Hàng Của Bạn</h5>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>product/checkout" method="POST">
                            <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                            <input type="hidden" name="ticket_price" value="100000"> 

                            <!-- 1. CHỌN NGÀY -->
                            <div class="mb-3">
                                <label class="fw-bold form-label text-dark">Ngày xem</label>
                                <input type="date" class="form-control" id="datePicker" name="show_date" required min="<?= date('Y-m-d') ?>">
                            </div>

                            <!-- 2. CHỌN SUẤT CHIẾU -->
                            <div class="mb-3" id="showtimeContainer" style="display: none;">
                                <label class="fw-bold form-label text-dark">Chọn suất chiếu</label>
                                <div id="showtimeButtons" class="d-flex flex-wrap gap-2">
                                    <!-- JS sẽ đổ dữ liệu vào đây -->
                                </div>
                                <input type="hidden" name="showtime_id" id="selectedShowtimeInput" value="" required>
                            </div>
                            
                            <!-- 3. CHỌN GHẾ NGỒI -->
                            <div class="mb-4" id="seatMapSection" style="opacity: 0.3; pointer-events: none;">
                                <label class="fw-bold form-label text-dark">Chọn ghế ngồi <span class="text-danger">(100.000đ/ghế)</span></label>
                                
                                <div class="seat-map-container bg-light p-3 rounded border text-center">
                                    <div class="screen bg-secondary text-white w-100 mb-4 py-1 rounded-pill small fw-bold">MÀN HÌNH</div>
                                    
                                    <div class="seats-grid d-flex flex-column align-items-center gap-2">
                                        <?php 
                                        $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
                                        foreach ($rows as $row): 
                                        ?>
                                            <div class="d-flex justify-content-center gap-2">
                                                <div class="fw-bold d-flex align-items-center justify-content-center text-dark" style="width: 20px;"><?= $row ?></div>
                                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                                    <!-- Chỉ in HTML, màu sắc sẽ do JS quyết định -->
                                                    <div class="seat rounded" data-seat="<?= $row . $i ?>" title="Ghế <?= $row . $i ?>"><?= $i ?></div>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="seat-legend d-flex justify-content-center gap-4 mt-4 small text-dark">
                                        <div class="d-flex align-items-center gap-1"><div class="seat-sample available"></div> Trống</div>
                                        <div class="d-flex align-items-center gap-1"><div class="seat-sample selected"></div> Đang chọn</div>
                                        <div class="d-flex align-items-center gap-1"><div class="seat-sample occupied"></div> Đã bán</div>
                                    </div>
                                </div>

                                <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="" required>
                                <input type="hidden" name="ticket_qty" id="ticketQtyInput" class="ticket-qty" value="0">
                                
                                <div class="mt-2 bg-white p-2 border rounded small">
                                    <strong class="text-dark">Ghế đã chọn: </strong> 
                                    <span id="selectedSeatsDisplay" class="text-danger fw-bold">Chưa chọn ghế nào</span>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <!-- 4. CHỌN COMBO -->
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
                                    <span class="fw-bold fs-4 text-danger" id="totalPrice">0đ</span>
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
    const movieId = <?= $movie['id'] ?>;
    const ticketPrice = 100000;
    let selectedSeatsArr = []; 

    const datePicker = document.getElementById('datePicker');
    const showtimeContainer = document.getElementById('showtimeContainer');
    const showtimeButtons = document.getElementById('showtimeButtons');
    const selectedShowtimeInput = document.getElementById('selectedShowtimeInput');
    const seatMapSection = document.getElementById('seatMapSection');
    const seats = document.querySelectorAll('.seat');

    // 1. Fetch Suất chiếu khi đổi Ngày
    datePicker.addEventListener('change', function() {
        const selectedDate = this.value;
        showtimeContainer.style.display = 'block';
        showtimeButtons.innerHTML = '<span class="text-muted small">Đang tải...</span>';
        
        // Reset ghế
        seatMapSection.style.opacity = '0.3';
        seatMapSection.style.pointerEvents = 'none';
        selectedShowtimeInput.value = '';
        selectedSeatsArr = [];
        updateSeatDisplay();

        fetch(`<?= BASE_URL ?>api/getShowtimes?movie_id=${movieId}&date=${selectedDate}`)
            .then(res => res.json())
            .then(data => {
                if(data.success && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(st => {
                        let time = new Date(st.start_time).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
                        html += `<button type="button" class="btn btn-outline-danger btn-sm st-btn" data-id="${st.id}">${time}</button>`;
                    });
                    showtimeButtons.innerHTML = html;
                    attachShowtimeEvents();
                } else {
                    showtimeButtons.innerHTML = '<span class="text-danger small">Không có suất chiếu.</span>';
                }
            });
    });

    // 2. Fetch Ghế khi đổi Suất chiếu
    function attachShowtimeEvents() {
        document.querySelectorAll('.st-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.st-btn').forEach(b => {
                    b.classList.remove('btn-danger', 'text-white');
                    b.classList.add('btn-outline-danger');
                });
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-danger', 'text-white');
                
                const showtimeId = this.dataset.id;
                selectedShowtimeInput.value = showtimeId;
                
                // Mở khóa bản đồ ghế
                seatMapSection.style.opacity = '1';
                seatMapSection.style.pointerEvents = 'auto';
                
                selectedSeatsArr = [];
                updateSeatDisplay();

                fetch(`<?= BASE_URL ?>api/getOccupiedSeats?showtime_id=${showtimeId}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            const occupiedSeats = data.data;
                            seats.forEach(seat => {
                                seat.classList.remove('selected', 'occupied');
                                if (occupiedSeats.includes(seat.dataset.seat)) {
                                    seat.classList.add('occupied');
                                }
                            });
                        }
                    });
            });
        });
    }

    // 3. Xử lý click ghế
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('occupied')) return;
            
            this.classList.toggle('selected');
            const seatId = this.dataset.seat;

            if (this.classList.contains('selected')) {
                selectedSeatsArr.push(seatId);
            } else {
                selectedSeatsArr = selectedSeatsArr.filter(id => id !== seatId);
            }
            updateSeatDisplay();
        });
    });

    // Hàm tính toán và cập nhật giao diện
    function updateSeatDisplay() {
        document.getElementById('selectedSeatsInput').value = selectedSeatsArr.join(',');
        document.getElementById('ticketQtyInput').value = selectedSeatsArr.length;
        document.getElementById('selectedSeatsDisplay').innerText = selectedSeatsArr.length > 0 ? selectedSeatsArr.join(', ') : 'Chưa chọn ghế nào';
        
        let total = selectedSeatsArr.length * ticketPrice;
        document.querySelectorAll('.combo-qty').forEach(input => {
            total += (parseInt(input.value) || 0) * parseInt(input.closest('.combo-item').querySelector('.combo-price').dataset.price);
        });
        document.getElementById('totalPrice').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    // Lắng nghe combo
    document.querySelectorAll('.combo-qty').forEach(input => input.addEventListener('input', updateSeatDisplay));
});
</script>