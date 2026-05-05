<?php
/**
 * Admin News Management
 */
?>

<!-- Breadcrumb -->
<nav class="admin-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Tin tức</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="page-header">
    <div class="page-actions">
        <a href="<?= BASE_URL ?>admin/create_news" class="btn-add">
            <i class="fas fa-plus-circle"></i> Đăng tin mới
        </a>
    </div>
</div>

<div class="type-switcher">
    <a href="<?= BASE_URL ?>admin/news_promotions" class="<?= ($newsCategory ?? '') === 'khuyen-mai' ? 'active' : '' ?>">Quản lý ưu đãi</a>
    <a href="<?= BASE_URL ?>admin/news_monthly_movies" class="<?= ($newsCategory ?? '') === 'phim-hay-thang' ? 'active' : '' ?>">Quản lý phim hay tháng</a>
    <a href="<?= BASE_URL ?>admin/news" class="<?= empty($newsCategory) ? 'active' : '' ?>">Tất cả</a>
</div>

<!-- Search Bar -->
<form method="GET" class="admin-search-form">
    <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" class="search-input" placeholder="Tiêu đề bài viết..." value="<?= htmlspecialchars($keyword ?? '') ?>">
    </div>
    <?php if (($sort ?? 'newest') !== 'newest'): ?>
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <?php endif; ?>
    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
    <a href="<?php
        // Preserve category when resetting filters
        $resetUrl = BASE_URL . 'admin/news';
        if (!empty($newsCategory)) {
            if ($newsCategory === 'khuyen-mai') {
                $resetUrl = BASE_URL . 'admin/news_promotions';
            } elseif ($newsCategory === 'phim-hay-thang') {
                $resetUrl = BASE_URL . 'admin/news_monthly_movies';
            }
        }
        echo $resetUrl;
    ?>" class="btn-reset"><i class="fas fa-times"></i> Xóa lọc</a>
</form>

<!-- Filter Bar -->
<div class="admin-filter-bar">
    <div class="filter-group">
        <label class="filter-label">Sắp xếp:</label>
        <select class="filter-select" onchange="applySort(this)">
            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= ($sort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
        </select>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <div class="d-flex justify-content-between align-items-center">
            <strong>Danh sách bài viết đã đăng</strong>
            <?php if (!empty($articles)): ?>
                <form method="POST" class="d-inline" id="bulk-delete-form">
                    <input type="hidden" name="action" value="delete_selected">
                    <input type="hidden" name="selected_ids" id="selected_ids" value="">
                    <button type="button" id="bulk-delete-btn" class="btn-action btn-delete" style="display:none;" onclick="deleteSelected();">Xóa đã chọn</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <table class="table">
        <thead class="admin-table-header">
            <tr>
                <th width="14%" class="text-center">
                    <label for="select-all" class="select-all-label">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this);">
                        <span>Chọn tất cả</span>
                    </label>
                </th>
                <th width="5%">#</th>
                <th width="12%">Ảnh</th>
                <th width="25%">Tiêu đề</th>
                <th width="12%">Danh mục</th>
                <th width="15%">Ngày đăng</th>
                <th width="10%">Ngườii đăng</th>
                <th width="8%" class="text-center">Slider</th>
                <th width="10%">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($articles)): ?>
                <tr><td colspan="9" class="text-center">Chưa có tin tức.</td></tr>
            <?php else: ?>
                <?php foreach ($articles as $idx => $article): ?>
                    <?php
                    $rawImage = trim((string)($article['image'] ?? ''));
                    $categoryValue = (string)($article['category'] ?? '');
                    $categoryLabelMap = [
                        'tin-tuc' => 'Tin tức',
                        'khuyen-mai' => 'Khuyến mãi',
                        'su-kien' => 'Sự kiện',
                        'phim-hay-thang' => 'Phim hay tháng',
                    ];
                    $categoryLabel = $categoryLabelMap[$categoryValue] ?? $categoryValue;
                    if ($rawImage === '') {
                        $articleImage = BASE_URL . 'public/images/about/about-6.png';
                    } elseif (str_starts_with($rawImage, 'public/')) {
                        $articleImage = BASE_URL . $rawImage;
                    } elseif (str_starts_with($rawImage, 'uploads/')) {
                        $articleImage = BASE_URL . 'public/' . ltrim($rawImage, '/');
                    } elseif (preg_match('#^https?://#i', $rawImage) === 1) {
                        $articleImage = $rawImage;
                    } else {
                        $articleImage = BASE_URL . 'public/' . ltrim($rawImage, '/');
                    }
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" class="select-item" value="<?= (int)($article['id'] ?? 0) ?>" onchange="updateSelectAll();"></td>
                        <td><?= (int)$idx + 1 ?></td>
                        <td><img src="<?= htmlspecialchars($articleImage) ?>" alt="Ảnh tin" class="news-thumb"></td>
                        <td><?= htmlspecialchars((string)($article['title'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($categoryLabel) ?></td>
                        <td><?= htmlspecialchars((string)($article['published_at'] ?? $article['created_at'] ?? '')) ?></td>
                        <td><?= htmlspecialchars((string)($article['author_name'] ?? 'Admin')) ?></td>
                        <td class="text-center"><?= ($article['featured'] ?? 0) ? '★ Có' : '- Không' ?></td>
                        <td>
                            <div class="news-action-buttons">
                                <a href="<?= BASE_URL ?>news/detail/<?= (int)($article['id'] ?? 0) ?>" class="btn-news-action btn-view">Xem</a>
                                <a href="<?= BASE_URL ?>admin/edit_news/<?= (int)($article['id'] ?? 0) ?>" class="btn-news-action btn-edit">Sửa</a>
                                <a href="<?= BASE_URL ?>admin/delete_news/<?= (int)($article['id'] ?? 0) ?>" class="btn-news-action btn-delete" onclick="return confirm('Xóa tin này?');">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$extraScripts = ($extraScripts ?? '') . <<<'SCRIPT'
<script>
function toggleSelectAll(checkbox) {
    document.querySelectorAll('.select-item').forEach(el => el.checked = checkbox.checked);
    updateBulkDeleteBtn();
}
function updateSelectAll() {
    const totalCheckboxes = document.querySelectorAll('.select-item').length;
    const checkedCheckboxes = document.querySelectorAll('.select-item:checked').length;
    document.getElementById('select-all').checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
    updateBulkDeleteBtn();
}
function updateBulkDeleteBtn() {
    const checkedCount = document.querySelectorAll('.select-item:checked').length;
    const btn = document.getElementById('bulk-delete-btn');
    if (btn) btn.style.display = checkedCount > 0 ? 'inline-block' : 'none';
}
function deleteSelected() {
    const selectedIds = Array.from(document.querySelectorAll('.select-item:checked')).map(el => el.value);
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một bài viết để xóa.');
        return;
    }
    if (!confirm('Xóa ' + selectedIds.length + ' bài viết? Hành động này không thể hoàn tác!')) {
        return;
    }
    document.getElementById('selected_ids').value = selectedIds.join(',');
    document.getElementById('bulk-delete-form').submit();
}
function applySort(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', select.value);
    window.location.href = url.toString();
}
</script>
SCRIPT;
