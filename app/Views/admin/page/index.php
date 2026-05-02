<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Trang - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Trang</li>
        </ol>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="fas fa-file-alt text-primary me-2"></i>Quản lý Trang</h2>
        <a href="<?= BASE_URL ?>admin/page/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Thêm Trang Mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Tiêu đề</th>
                            <th>Slug</th>
                            <th class="text-center" style="width: 120px;">Trạng thái</th>
                            <th class="text-center" style="width: 150px;">Ngày tạo</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pages)): ?>
                            <?php foreach ($pages as $page): ?>
                            <tr>
                                <td class="text-center"><?= (int)$page['id'] ?></td>
                                <td><?= htmlspecialchars($page['title']) ?></td>
                                <td><code><?= htmlspecialchars($page['slug']) ?></code></td>
                                <td class="text-center">
                                    <?php if ($page['status'] === 'published'): ?>
                                        <span class="badge bg-success">Đã đăng</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Bản nháp</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($page['created_at'])) ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>admin/page/edit/<?= $page['id'] ?>" class="btn btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>admin/page/delete/<?= $page['id'] ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa trang này?');"
                                           title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>Chưa có trang nào. Hãy thêm trang đầu tiên!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
