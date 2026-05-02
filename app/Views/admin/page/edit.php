<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Trang - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #f4f6f9; }</style>
</head>
<body>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/page/index">Quản lý Trang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sửa Trang</li>
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
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa Trang: <?= htmlspecialchars($page['title']) ?></h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>admin/page/update/<?= $page['id'] ?>" method="POST">
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" required value="<?= htmlspecialchars($page['title']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="slug" id="slug" required value="<?= htmlspecialchars($page['slug']) ?>">
                                <small class="text-muted">Dùng cho URL: /page/slug</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="content" rows="15" required><?= htmlspecialchars($page['content']) ?></textarea>
                                <small class="text-muted">Có thể sử dụng HTML để định dạng nội dung</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Bản nháp</option>
                                    <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Đã đăng</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>admin/page/index" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">
                                <i class="fas fa-save me-1"></i> Lưu Thay Đổi
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});
</script>

</body>
</html>
