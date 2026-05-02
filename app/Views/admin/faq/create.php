<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm FAQ Mới - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #f4f6f9; }</style>
</head>
<body>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/faq/index">Quản lý FAQ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm FAQ Mới</li>
        </ol>
    </nav>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Thêm Câu Hỏi Mới</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/faq/store" method="POST">
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Câu hỏi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="question" required placeholder="Nhập câu hỏi">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select" name="category" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Chọn danh mục phù hợp cho câu hỏi</small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Thứ tự</label>
                                <input type="number" class="form-control" name="sort_order" value="0" min="0">
                                <small class="text-muted">Số nhỏ hiển thị trước</small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="active">Hiển thị</option>
                                    <option value="inactive">Ẩn</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Câu trả lời <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="answer" rows="8" required placeholder="Nhập câu trả lời chi tiết"></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>admin/faq/index" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fas fa-save me-1"></i> Lưu Câu Hỏi
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
