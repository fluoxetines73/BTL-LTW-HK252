<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Quản lý Tin tức') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cgv-red: #E71A0F;
            --cgv-dark: #1A1A2E;
            --cgv-white: #FFFFFF;
            --cgv-gold: #D4A843;
        }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: var(--cgv-dark); color: var(--cgv-white); padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; left: 0; top: 0; }
        .sidebar-logo { padding: 20px; text-align: center; border-bottom: 2px solid var(--cgv-red); margin-bottom: 20px; }
        .sidebar-logo h3 { margin: 0; color: var(--cgv-red); font-weight: bold; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display: block; padding: 15px 20px; color: var(--cgv-white); text-decoration: none; transition: all .3s ease; border-left: 3px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: var(--cgv-red); border-left-color: var(--cgv-gold); color: var(--cgv-white); }
        .sidebar-menu i { margin-right: 10px; width: 20px; text-align: center; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .admin-header { background: var(--cgv-white); padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,.1); display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .admin-header h1 { margin: 0; color: var(--cgv-dark); font-weight: bold; }
        .table-container { background: var(--cgv-white); border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1); overflow: hidden; }
        .table-header { padding: 16px 20px; border-bottom: 1px solid #dee2e6; }
        .info-bar { padding: 14px 20px; background: #e3f2fd; border-left: 4px solid #2196F3; margin: 16px 20px; border-radius: 4px; }
        .table { margin: 0; }
        .table thead { background: var(--cgv-dark); color: #fff; }
        .table thead th { color: #fff !important; }
        .table th, .table td { padding: 12px 10px; vertical-align: middle; }
        .news-thumb { width: 88px; height: 52px; object-fit: cover; border-radius: 6px; }
        .btn-add { background: var(--cgv-red); color: #fff; border: none; border-radius: 6px; padding: 10px 14px; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-add:hover { color: #fff; background: #ca170c; }
        .type-switcher { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
        .type-switcher a { text-decoration: none; padding: 9px 14px; border-radius: 999px; border: 1px solid #d1d5db; color: #374151; background: #fff; font-weight: 600; }
        .type-switcher a.active { background: var(--cgv-red); color: #fff; border-color: var(--cgv-red); }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 15px; }
            .admin-header { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>
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
                <a href="<?= BASE_URL ?>admin/news_monthly_movies" class="<?= ($newsCategory ?? '') === 'tin-tuc' ? 'active' : '' ?>">Quản lý phim hay tháng</a>
                <a href="<?= BASE_URL ?>admin/news" class="<?= empty($newsCategory) ? 'active' : '' ?>">Tất cả</a>
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
                    <strong>Danh sách bài viết đã đăng</strong>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:6%;">#</th>
                            <th style="width:14%;">Ảnh</th>
                            <th style="width:28%;">Tiêu đề</th>
                            <th style="width:14%;">Danh mục</th>
                            <th style="width:18%;">Ngày đăng</th>
                            <th style="width:12%;">Người đăng</th>
                            <th style="width:10%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($articles)): ?>
                            <tr><td colspan="7" class="text-center">Chưa có tin tức.</td></tr>
                        <?php else: ?>
                            <?php foreach ($articles as $idx => $article): ?>
                                <?php
                                $rawImage = trim((string)($article['image'] ?? ''));
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
                                    <td><?= (int)$idx + 1 ?></td>
                                    <td><img src="<?= htmlspecialchars($articleImage) ?>" alt="Ảnh tin" class="news-thumb"></td>
                                    <td><?= htmlspecialchars((string)($article['title'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string)($article['category'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string)($article['published_at'] ?? $article['created_at'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string)($article['author_name'] ?? 'Admin')) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>news/detail/<?= (int)($article['id'] ?? 0) ?>" style="color:#2563eb;text-decoration:none;">Xem</a>
                                        <span style="color:#cbd5e1;">|</span>
                                        <a href="<?= BASE_URL ?>admin/edit_news/<?= (int)($article['id'] ?? 0) ?>" style="color:#d97706;text-decoration:none;">Sửa</a>
                                        <span style="color:#cbd5e1;">|</span>
                                        <a href="<?= BASE_URL ?>admin/delete_news/<?= (int)($article['id'] ?? 0) ?>" style="color:#dc2626;text-decoration:none;" onclick="return confirm('Xóa tin này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
