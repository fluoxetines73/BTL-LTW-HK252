<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phim - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; } /* Màu nền xám nhạt dịu mắt cho admin */
        .card { border-radius: 10px; } /* Bo góc mềm mại */
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Phim</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-video me-2"></i>Danh Sách Phim</h5>
            <a href="<?= BASE_URL ?>admin/movie/create" class="btn btn-success shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Thêm Phim Mới
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">Tên Phim</th>
                            <th width="15%">Đạo diễn</th>
                            <th>Thể loại</th> 
                            <th width="10%">Thời lượng</th>
                            <th width="15%">Ngày chiếu</th>
                            <th width="15%">Trạng thái</th>
                            <th width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movies)): ?>
                            <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td><?= $movie['id'] ?></td>
                                <td class="text-start fw-bold text-primary"><?= htmlspecialchars($movie['title']) ?></td>
                                <td><?= htmlspecialchars($movie['director']) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= !empty($movie['genre_names']) ? htmlspecialchars($movie['genre_names']) : 'Chưa có' ?>
                                    </span>
                                </td>
                                <td><span class="badge bg-secondary"><?= $movie['duration_min'] ?> phút</span></td>
                                <td><?= date('d/m/Y', strtotime($movie['release_date'])) ?></td>
                                <td>
                                    <?php if($movie['status'] == 'now_showing'): ?>
                                        <span class="badge bg-success px-3 py-2">Đang chiếu</span>
                                    <?php elseif($movie['status'] == 'coming_soon'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2">Sắp chiếu</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-3 py-2">Đã kết thúc</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= BASE_URL ?>admin/movie/edit/<?= $movie['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="<?= BASE_URL ?>admin/movie/delete/<?= $movie['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                                    Chưa có bộ phim nào trong cơ sở dữ liệu.
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