<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Suất chiếu</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-calendar-alt me-2"></i>Danh Sách Suất Chiếu</h5>
            <a href="<?= BASE_URL ?>admin/showtime/create" class="btn btn-success shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Thêm Suất Chiếu Mới
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="admin-table-header">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">Tên Phim</th>
                            <th width="15%">Phòng Chiếu</th>
                            <th width="25%">Giờ Bắt Đầu</th>
                            <th width="15%">Giá Cơ Bản</th>
                            <th width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($showtimes)): ?>
                            <?php foreach ($showtimes as $st): ?>
                            <tr>
                                <td><?= $st['id'] ?></td>
                                <td class="text-start fw-bold text-primary"><?= htmlspecialchars($st['movie_title']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($st['room_name']) ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($st['start_time'])) ?></td>
                                <td class="fw-bold text-success"><?= number_format($st['base_price']) ?>đ</td>
                            
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= BASE_URL ?>admin/showtime/edit/<?= $st['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="<?= BASE_URL ?>admin/showtime/delete/<?= $st['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa suất chiếu này không? Các dữ liệu vé liên quan có thể bị ảnh hưởng.');">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                                    Chưa có suất chiếu nào trong cơ sở dữ liệu.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>