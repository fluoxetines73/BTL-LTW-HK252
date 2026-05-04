<?php
/**
 * Admin Combo Create - Thêm Combo mới
 * Content fragment for admin layout
 */
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-hamburger me-2"></i>Thêm Bắp Nước (Combo) Mới</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/combo/store" method="POST" enctype="multipart/form-data">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên Combo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="vd: Combo Couple">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="price" required placeholder="vd: 130000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hình ảnh Combo</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Định dạng hỗ trợ: JPG, PNG. (Có thể để trống)</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" name="is_active" required>
                                <option value="1">Đang mở bán</option>
                                <option value="0">Tạm ngưng</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="vd: Bao gồm 1 Bắp (L) + 2 Nước (M)..."></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>admin/combo/index" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Lưu Combo
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>