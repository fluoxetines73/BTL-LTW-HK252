<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phim Mới - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #f4f6f9; }</style>
</head>
<body>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-film me-2"></i>Thêm Phim Mới</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/movie/store" method="POST" enctype="multipart/form-data">
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

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ảnh poster</label>
                                <input type="file" class="form-control" name="poster" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif">
                                <small class="text-muted">Hỗ trợ: JPG, JPEG, PNG, WebP, GIF (tối đa 5MB).</small>
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
        </div>
    </div>
</div>

</body>
</html>