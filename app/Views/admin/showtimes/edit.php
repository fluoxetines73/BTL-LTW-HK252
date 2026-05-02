<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Suất Chiếu - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/showtime/index">Quản lý Suất chiếu</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sửa thông tin</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-edit me-2"></i>Sửa Suất Chiếu #<?= $showtime['id'] ?></h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/showtime/update/<?= $showtime['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Phim <span class="text-danger">*</span></label>
                            <select name="movie_id" class="form-select" required>
                                <?php foreach($movies as $movie): ?>
                                    <option value="<?= $movie['id'] ?>" <?= ($movie['id'] == $showtime['movie_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($movie['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Phòng Chiếu <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select" required>
                                <?php foreach($rooms as $room): ?>
                                    <option value="<?= $room['id'] ?>" <?= ($room['id'] == $showtime['room_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($room['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <!-- Format lại chuẩn ISO cho thẻ datetime-local -->
                            <?php $formattedTime = date('Y-m-d\TH:i', strtotime($showtime['start_time'])); ?>
                            <input type="datetime-local" name="start_time" class="form-control" value="<?= $formattedTime ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Giá vé cơ bản (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="base_price" class="form-control" value="<?= (int)$showtime['base_price'] ?>" min="0" step="1000" required>
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>admin/showtime/index" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Cập nhật Suất Chiếu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>