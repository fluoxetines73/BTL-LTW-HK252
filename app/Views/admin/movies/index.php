<?php
/**
 * Admin Movie Management - Listing with search/filter/sort
 */
?>

<!-- Breadcrumb -->
<nav class="admin-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Phim</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-film"></i> Danh Sách Phim</h1>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>admin/movie/create" class="btn-add">
            <i class="fas fa-plus-circle"></i> Thêm Phim Mới
        </a>
    </div>
</div>

<!-- Search Bar -->
<form method="GET" action="<?= BASE_URL ?>admin/movie/index" class="admin-search-form">
    <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" class="search-input" placeholder="Tìm theo tên phim hoặc đạo diễn..." value="<?= htmlspecialchars($keyword ?? '') ?>">
    </div>
    <?php if (!empty($status)): ?>
        <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
    <?php endif; ?>
    <?php if (($sort ?? 'newest') !== 'newest'): ?>
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <?php endif; ?>
    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
    <a href="<?= BASE_URL ?>admin/movie/index" class="btn-reset">Xóa lọc</a>
</form>

<!-- Filter Bar -->
<div class="admin-filter-bar">
    <div class="filter-group">
        <span class="filter-label">Trạng thái:</span>
        <select class="filter-select" onchange="applyFilter(this)">
            <option value="" <?= empty($status) ? 'selected' : '' ?>>Tất cả</option>
            <option value="now_showing" <?= ($status ?? '') === 'now_showing' ? 'selected' : '' ?>>Đang chiếu</option>
            <option value="coming_soon" <?= ($status ?? '') === 'coming_soon' ? 'selected' : '' ?>>Sắp chiếu</option>
            <option value="ended" <?= ($status ?? '') === 'ended' ? 'selected' : '' ?>>Đã kết thúc</option>
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Sắp xếp:</span>
        <select class="filter-select" onchange="applySort(this)">
            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= ($sort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
        </select>
    </div>
</div>

<!-- Movie Table -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="admin-table-header">
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
                            <td colspan="8" class="text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                                <?php if (!empty($keyword) || !empty($status)): ?>
                                    Không tìm thấy phim nào phù hợp với bộ lọc.
                                <?php else: ?>
                                    Chưa có bộ phim nào trong cơ sở dữ liệu.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$extraScripts = ($extraScripts ?? '') . <<<'SCRIPT'
<script>
function applyFilter(select) {
    const url = new URL(window.location.href);
    const status = select.value;
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function applySort(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', select.value);
    window.location.href = url.toString();
}
</script>
SCRIPT;