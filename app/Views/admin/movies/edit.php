<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa: <span class="text-info"><?= htmlspecialchars($movie['title']) ?></span></h5>
        <span class="badge bg-light text-dark">ID: #<?= $movie['id'] ?></span>
    </div>
    
    <div class="card-body p-4">
        <form action="<?= BASE_URL ?>admin/movie/update/<?= $movie['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tên Phim <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required value="<?= htmlspecialchars($movie['title']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đường dẫn tĩnh (Slug) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" required value="<?= htmlspecialchars($movie['slug']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Đạo diễn</label>
                    <input type="text" class="form-control" name="director" value="<?= htmlspecialchars($movie['director']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Diễn viên chính</label>
                    <input type="text" class="form-control" name="cast" value="<?= htmlspecialchars($movie['cast']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Thời lượng (phút)</label>
                    <input type="number" class="form-control" name="duration_min" required value="<?= $movie['duration_min'] ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Ngày phát hành</label>
                    <input type="date" class="form-control" name="release_date" required value="<?= $movie['release_date'] ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Độ tuổi</label>
                    <select class="form-select" name="age_rating" required>
                        <option value="P" <?= $movie['age_rating'] == 'P' ? 'selected' : '' ?>>P - Mọi lứa tuổi</option>
                        <option value="C13" <?= $movie['age_rating'] == 'C13' ? 'selected' : '' ?>>C13 - Từ 13 tuổi</option>
                        <option value="C16" <?= $movie['age_rating'] == 'C16' ? 'selected' : '' ?>>C16 - Từ 16 tuổi</option>
                        <option value="C18" <?= $movie['age_rating'] == 'C18' ? 'selected' : '' ?>>C18 - Từ 18 tuổi</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Trạng thái chiếu</label>
                    <div class="d-flex gap-4 p-2 bg-light rounded border">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="s1" value="coming_soon" <?= $movie['status'] == 'coming_soon' ? 'checked' : '' ?>>
                            <label class="form-check-label text-warning fw-bold" for="s1">Sắp chiếu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="s2" value="now_showing" <?= $movie['status'] == 'now_showing' ? 'checked' : '' ?>>
                            <label class="form-check-label text-success fw-bold" for="s2">Đang chiếu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="s3" value="ended" <?= $movie['status'] == 'ended' ? 'checked' : '' ?>>
                            <label class="form-check-label text-danger fw-bold" for="s3">Đã kết thúc</label>
                        </div>
                    </div>
                </div>

                <!-- Trường chọn Thể loại phim (Dạng nút bấm) -->
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">Thể loại phim *</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $currentGenres = $currentGenres ?? []; 
                        
                        $allGenres = [
                            'hanh-dong' => 'Hành Động', 'hai' => 'Hài', 'khoa-hoc-vien-tuong' => 'Khoa Học Viễn Tưởng',
                            'tam-ly' => 'Tâm Lý', 'phieu-luu' => 'Phiêu Lưu', 'kinh-di' => 'Kinh Dị',
                            'hoat-hinh' => 'Hoạt Hình', 'tinh-cam' => 'Tình Cảm', 'gia-dinh' => 'Gia Đình',
                            'bi-an' => 'Bí Ẩn'
                        ];

                        foreach ($allGenres as $slug => $name):
                            $isChecked = in_array($slug, $currentGenres) ? 'checked' : '';
                        ?>
                            <input type="checkbox" class="btn-check" id="edit-genre-<?= $slug ?>" name="genres[]" value="<?= $slug ?>" autocomplete="off" <?= $isChecked ?>>
                            <label class="btn btn-outline-info rounded-pill" for="edit-genre-<?= $slug ?>"><?= $name ?></label>
                        <?php endforeach; ?>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-hand-pointer"></i> Nhấn trực tiếp vào các nút trên để thay đổi thể loại.
                    </small>
                </div>

                <!-- Khối Upload Hình Ảnh -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Poster Phim (Khổ dọc)</label>
                        <input type="file" name="poster" class="form-control" accept="image/*">
                        <?php if (!empty($movie['poster'])): ?>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Poster hiện tại:</small>
                                <img src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($movie['poster']) ?>" alt="Poster" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Banner Phim (Khổ ngang)</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        <?php if (!empty($movie['banner'])): ?>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Banner hiện tại:</small>
                                <img src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($movie['banner']) ?>" alt="Banner" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Mô tả nội dung</label>
                    <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($movie['description']) ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="<?= BASE_URL ?>admin/movie/index" class="btn btn-outline-secondary px-4">Hủy</a>
                <button type="submit" class="btn btn-warning px-4 fw-bold">
                    <i class="fas fa-check-circle me-1"></i> Cập Nhật Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>