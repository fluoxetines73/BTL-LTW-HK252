<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0"><i class="fas fa-film me-2"></i>Thêm Phim Mới</h5>
    </div>
    
    <div class="card-body p-4">
        <form action="<?= BASE_URL ?>admin/movie/store" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tên Phim <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required placeholder="Nhập tên phim...">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đường dẫn tĩnh (Slug) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" required placeholder="vd: avengers-endgame">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đạo diễn</label>
                    <input type="text" class="form-control" name="director" placeholder="Tên đạo diễn...">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Diễn viên chính</label>
                    <input type="text" class="form-control" name="cast" placeholder="vd: Robert Downey Jr., Chris Evans">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Thời lượng (phút) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="duration_min" required value="120">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Ngày phát hành <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="release_date" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Độ tuổi <span class="text-danger">*</span></label>
                    <select class="form-select" name="age_rating" required>
                        <option value="P">P - Mọi lứa tuổi</option>
                        <option value="C13">C13 - Từ 13 tuổi</option>
                        <option value="C16">C16 - Từ 16 tuổi</option>
                        <option value="C18">C18 - Từ 18 tuổi</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Trạng thái chiếu <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="coming_soon" checked>
                            <label class="form-check-label text-warning fw-bold" for="status1">Sắp chiếu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="now_showing">
                            <label class="form-check-label text-success fw-bold" for="status2">Đang chiếu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status3" value="ended">
                            <label class="form-check-label text-danger fw-bold" for="status3">Đã kết thúc</label>
                        </div>
                    </div>
                </div>
                <!-- Trường chọn Thể loại phim (Dạng nút bấm tròn có thể chọn nhiều) -->
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">Thể loại phim *</label>
                    
                    <!-- d-flex flex-wrap gap-2 giúp các nút tự động nằm dàn ngang và xuống dòng khi hết chỗ -->
                    <div class="d-flex flex-wrap gap-2">
                        
                        <!-- Thể loại: Hành Động -->
                        <input type="checkbox" class="btn-check" id="genre-hanh-dong" name="genres[]" value="hanh-dong" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-hanh-dong">Hành Động</label>

                        <!-- Thể loại: Hài -->
                        <input type="checkbox" class="btn-check" id="genre-hai" name="genres[]" value="hai" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-hai">Hài</label>

                        <!-- Thể loại: Khoa Học Viễn Tưởng -->
                        <input type="checkbox" class="btn-check" id="genre-khoa-hoc" name="genres[]" value="khoa-hoc-vien-tuong" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-khoa-hoc">Khoa Học Viễn Tưởng</label>

                        <!-- Thể loại: Tâm Lý -->
                        <input type="checkbox" class="btn-check" id="genre-tam-ly" name="genres[]" value="tam-ly" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-tam-ly">Tâm Lý</label>

                        <!-- Thể loại: Phiêu Lưu -->
                        <input type="checkbox" class="btn-check" id="genre-phieu-luu" name="genres[]" value="phieu-luu" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-phieu-luu">Phiêu Lưu</label>

                        <!-- Thể loại: Kinh Dị -->
                        <input type="checkbox" class="btn-check" id="genre-kinh-di" name="genres[]" value="kinh-di" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-kinh-di">Kinh Dị</label>

                        <!-- Thể loại: Hoạt Hình -->
                        <input type="checkbox" class="btn-check" id="genre-hoat-hinh" name="genres[]" value="hoat-hinh" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-hoat-hinh">Hoạt Hình</label>

                        <!-- Thể loại: Tình Cảm -->
                        <input type="checkbox" class="btn-check" id="genre-tinh-cam" name="genres[]" value="tinh-cam" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-tinh-cam">Tình Cảm</label>

                        <!-- Thể loại: Gia Đình -->
                        <input type="checkbox" class="btn-check" id="genre-gia-dinh" name="genres[]" value="gia-dinh" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-gia-dinh">Gia Đình</label>

                        <!-- Thể loại: Bí Ẩn -->
                        <input type="checkbox" class="btn-check" id="genre-bi-an" name="genres[]" value="bi-an" autocomplete="off">
                        <label class="btn btn-outline-info rounded-pill" for="genre-bi-an">Bí Ẩn</label>

                    </div>
                    
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-hand-pointer"></i> Nhấn trực tiếp vào các nút trên để chọn hoặc bỏ chọn thể loại.
                    </small>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Mô tả nội dung</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Viết mô tả tóm tắt nội dung phim..."></textarea>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>admin/movie/index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Lưu Phim
                </button>
            </div>
        </form>
    </div>
</div>