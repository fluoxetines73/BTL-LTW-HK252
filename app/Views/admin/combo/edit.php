<?php
/**
 * Admin Combo Edit - Chỉnh sửa Combo
 * Content fragment for admin layout
 */
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa Combo: <?= htmlspecialchars($combo['name']) ?></h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>admin/combo/update/<?= $combo['id'] ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên Combo</label>
                            <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($combo['name']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá tiền (VNĐ)</label>
                            <input type="number" class="form-control" name="price" required value="<?= (int)$combo['price'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thay đổi hình ảnh</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted italic">Để trống nếu muốn giữ ảnh cũ</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select class="form-select" name="is_active">
                                <option value="1" <?= $combo['is_active'] == 1 ? 'selected' : '' ?>>Đang mở bán</option>
                                <option value="0" <?= $combo['is_active'] == 0 ? 'selected' : '' ?>>Tạm ngưng</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label d-block fw-bold">Ảnh hiện tại:</label>
                            <img src="<?= BASE_URL ?>public/uploads/combos/<?= $combo['image'] ?>" 
                                 class="img-thumbnail" style="height: 150px;">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($combo['description']) ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>admin/combo/index" class="btn btn-secondary px-4">Hủy</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>