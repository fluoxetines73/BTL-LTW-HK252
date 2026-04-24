    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <h3><i class="fas fa-cogs"></i> Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="<?= BASE_URL ?>admin/users"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
                <li><a href="<?= BASE_URL ?>admin/products"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
                <li><a href="<?= BASE_URL ?>admin/news" class="active"><i class="fas fa-newspaper"></i> Quản lý Tin tức</a></li>
                <li><a href="<?= BASE_URL ?>profile/edit"><i class="fas fa-user-edit"></i> Hồ sơ cá nhân</a></li>
                <li><a href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="admin-header">
                <h1><?= htmlspecialchars($title ?? 'Quản lý Tin tức') ?></h1>
                <a href="<?= BASE_URL ?>admin/create_news" class="btn-add"><i class="fas fa-plus" style="margin-right:8px;"></i>Đăng tin mới</a>
            </div>

            <div class="type-switcher">
                <a href="<?= BASE_URL ?>admin/news_promotions" class="<?= ($newsCategory ?? '') === 'khuyen-mai' ? 'active' : '' ?>">Quản lý ưu đãi</a>
                <a href="<?= BASE_URL ?>admin/news_monthly_movies" class="<?= ($newsCategory ?? '') === 'phim-hay-thang' ? 'active' : '' ?>">Quản lý phim hay tháng</a>
                <a href="<?= BASE_URL ?>admin/news" class="<?= empty($newsCategory) ? 'active' : '' ?>">Tất cả</a>
            </div>

            <div style="background:#fff;padding:20px;border-radius:8px;margin-bottom:20px;box-shadow:0 2px 4px rgba(0,0,0,.1);">
                <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                    <div style="flex:1;min-width:200px;">
                        <label style="display:block;font-weight:600;margin-bottom:6px;">Tìm kiếm</label>
                        <input type="text" name="q" placeholder="Tiêu đề bài viết..." value="<?= htmlspecialchars($keyword ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
                    </div>
                    <div>
                        <label style="display:block;font-weight:600;margin-bottom:6px;">Sắp xếp</label>
                        <select name="sort" style="padding:8px;border:1px solid #d1d5db;border-radius:6px;">
                            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="oldest" <?= ($sort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                        </select>
                    </div>
                    <button type="submit" style="background:#E71A0F;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:600;">Tìm</button>
                    <a href="<?= BASE_URL ?>admin/news" style="background:#6b7280;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;font-weight:600;">Xóa lọc</a>
                </form>
            </div>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="info-bar" style="background:#e8f5e9;border-left-color:#4CAF50;"><?= htmlspecialchars((string)$_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="info-bar" style="background:#ffebee;border-left-color:#f44336;"><?= htmlspecialchars((string)$_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="table-container">
                <div class="table-header">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <strong>Danh sách bài viết đã đăng</strong>
                        <?php if (!empty($articles)): ?>
                            <form method="POST" style="display:inline;" id="bulk-delete-form">
                                <input type="hidden" name="action" value="delete_selected">
                                <input type="hidden" name="selected_ids" id="selected_ids" value="">
                                <button type="button" id="bulk-delete-btn" style="background:#dc2626;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;font-weight:600;display:none;" onclick="deleteSelected();">Xóa đã chọn</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:14%;text-align:center;">
                                <label for="select-all" class="select-all-label">
                                    <input type="checkbox" id="select-all" onchange="toggleSelectAll(this);">
                                    <span>Chọn tất cả</span>
                                </label>
                            </th>
                            <th style="width:5%;">#</th>
                            <th style="width:12%;">Ảnh</th>
                            <th style="width:25%;">Tiêu đề</th>
                            <th style="width:12%;">Danh mục</th>
                            <th style="width:15%;">Ngày đăng</th>
                            <th style="width:10%;">Người đăng</th>
                            <th style="width:8%;text-align:center;">Slider</th>
                            <th style="width:10%;">Thao tác</th>
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
                                    <td style="text-align:center;"><input type="checkbox" class="select-item" value="<?= (int)($article['id'] ?? 0) ?>" onchange="updateSelectAll();"></td>
                                    <td><?= (int)$idx + 1 ?></td>
                                    <td><img src="<?= htmlspecialchars($articleImage) ?>" alt="Ảnh tin" class="news-thumb"></td>
                                    <td><?= htmlspecialchars((string)($article['title'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($categoryLabel) ?></td>
                                    <td><?= htmlspecialchars((string)($article['published_at'] ?? $article['created_at'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string)($article['author_name'] ?? 'Admin')) ?></td>
                                    <td style="text-align:center;"><?= ($article['featured'] ?? 0) ? '★ Có' : '- Không' ?></td>
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
            </script>
        </main>
    </div>
