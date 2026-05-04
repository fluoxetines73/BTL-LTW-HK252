<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/showtime/index">Quản lý Suất chiếu</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm Suất Chiếu Mới</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/showtime/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Phim <span class="text-danger">*</span></label>
                            <select name="movie_id" class="form-select" required>
                                <option value="" disabled selected>-- Vui lòng chọn phim --</option>
                                <?php foreach($movies as $movie): ?>
                                    <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Phòng Chiếu <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <option value="" disabled selected>-- Vui lòng chọn phòng --</option>
                                <?php foreach($rooms as $room): ?>
                                    <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Giá vé cơ bản (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="base_price" class="form-control" value="100000" min="0" step="1000" required>
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>admin/showtime/index" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-1"></i> Lưu Suất Chiếu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>