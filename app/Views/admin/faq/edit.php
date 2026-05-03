<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/faq/index">Quản lý FAQ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sửa FAQ</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa Câu Hỏi</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/faq/update/<?= $faq['id'] ?>" method="POST">
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Câu hỏi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="question" required value="<?= htmlspecialchars($faq['question']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>" <?= $faq['category'] === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Thứ tự</label>
                            <input type="number" class="form-control" name="sort_order" value="<?= (int)$faq['sort_order'] ?>" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="active" <?= $faq['status'] === 'active' ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="inactive" <?= $faq['status'] === 'inactive' ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Câu trả lờii <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="answer" rows="8" required><?= htmlspecialchars($faq['answer']) ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>admin/faq/index" class="btn btn-secondary px-4">
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