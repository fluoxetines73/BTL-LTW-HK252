<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <div class="admin-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý Suất chiếu</li>
        </ol>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h5 class="page-title"><i class="fas fa-calendar-alt"></i> Danh Sách Suất Chiếu</h5>
        <div class="page-actions">
            <a href="<?= BASE_URL ?>admin/showtime/create" class="btn-add">
                <i class="fas fa-plus-circle"></i> Thêm Suất Chiếu Mới
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <form method="GET" action="<?= BASE_URL ?>admin/showtime/index" class="admin-search-form">
        <div class="search-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="q" class="search-input" placeholder="Tìm theo tên phim..." value="<?= htmlspecialchars($keyword ?? '') ?>">
        </div>
        <button type="submit" class="btn-search"><i class="fas fa-search me-1"></i> Tìm</button>
        <a href="<?= BASE_URL ?>admin/showtime/index" class="btn-reset"><i class="fas fa-times me-1"></i> Xóa lọc</a>
    </form>

    <!-- Filter & Sort Bar -->
    <form method="GET" action="<?= BASE_URL ?>admin/showtime/index" class="admin-filter-bar">
        <?php if (!empty($keyword)): ?>
            <input type="hidden" name="q" value="<?= htmlspecialchars($keyword) ?>">
        <?php endif; ?>
        <div class="filter-group">
            <label class="filter-label">Lọc theo ngày:</label>
            <input type="date" name="date" class="filter-select" value="<?= htmlspecialchars($dateFilter ?? '') ?>">
        </div>
        <div class="filter-group">
            <label class="filter-label">Sắp xếp:</label>
            <select name="sort" class="filter-select">
                <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                <option value="oldest" <?= ($sort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                <option value="price_asc" <?= ($sort ?? '') === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                <option value="price_desc" <?= ($sort ?? '') === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
            </select>
        </div>
        <button type="submit" class="btn-search"><i class="fas fa-filter me-1"></i> Lọc</button>
        <a href="<?= BASE_URL ?>admin/showtime/index" class="btn-reset"><i class="fas fa-times me-1"></i> Xóa lọc</a>
    </form>

    <div class="card shadow-sm border-0">
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
                                    <?php if (!empty($keyword) || !empty($dateFilter)): ?>
                                        Không tìm thấy suất chiếu phù hợp với bộ lọc.
                                    <?php else: ?>
                                        Chưa có suất chiếu nào trong cơ sở dữ liệu.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>