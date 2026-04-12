<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Phim - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #f4f6f9; }</style>
</head>
<body>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa: <span class="text-info"><?= htmlspecialchars($movie['title']) ?></span></h5>
                    <span class="badge bg-light text-dark">ID: #<?= $movie['id'] ?></span>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/movie/update/<?= $movie['id'] ?>" method="POST">
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
        </div>
    </div>
</div>

</body>
</html>