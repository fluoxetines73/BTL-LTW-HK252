<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Combo - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="fas fa-hamburger text-primary me-2"></i>Quản lý Bắp & Nước</h2>
        <a href="<?= BASE_URL ?>admin/combo/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Thêm Combo Mới
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (!empty($combos)): ?>
            <?php foreach ($combos as $combo): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <img src="<?= BASE_URL ?>public/uploads/combos/<?= htmlspecialchars($combo['image']) ?>" 
                    class="card-img-top" 
                    alt="<?= htmlspecialchars($combo['name']) ?>" 
                    style="height: 200px; object-fit: cover;">                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($combo['name']) ?></h5>
                            <span class="text-danger fw-bold"><?= number_format($combo['price'], 0, ',', '.') ?>đ</span>
                        </div>
                        <p class="card-text text-muted small"><?= htmlspecialchars($combo['description']) ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex gap-2">
                        <a href="<?= BASE_URL ?>admin/combo/edit/<?= $combo['id'] ?>" class="btn btn-sm btn-outline-primary w-100">Sửa</a>
                        <a href="<?= BASE_URL ?>admin/combo/delete/<?= $combo['id'] ?>" 
                            class="btn btn-sm btn-outline-danger w-100" 
                            onclick="return confirm('Bạn có chắc chắn muốn xóa combo này?');">Xóa</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Chưa có combo nào. Hãy thêm combo đầu tiên!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>