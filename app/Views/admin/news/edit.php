<?php
$flash = $flash ?? null;
$article = $article ?? [];
$currentImage = trim((string)($article['image'] ?? ''));
if ($currentImage !== '') {
    if (str_starts_with($currentImage, 'public/')) {
        $currentImageUrl = BASE_URL . $currentImage;
    } elseif (str_starts_with($currentImage, 'uploads/')) {
        $currentImageUrl = BASE_URL . 'public/' . ltrim($currentImage, '/');
    } elseif (preg_match('#^https?://#i', $currentImage) === 1) {
        $currentImageUrl = $currentImage;
    } else {
        $currentImageUrl = BASE_URL . 'public/' . ltrim($currentImage, '/');
    }
} else {
    $currentImageUrl = BASE_URL . 'public/images/about/about-6.png';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sửa Tin Tức') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --cgv-red:#E71A0F; --cgv-dark:#1A1A2E; --cgv-white:#fff; --cgv-gold:#D4A843; }
        body { background:#f8f9fa; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; }
        .admin-container { display:flex; min-height:100vh; }
        .sidebar { width:250px; background:var(--cgv-dark); color:#fff; position:fixed; left:0; top:0; height:100vh; }
        .sidebar-logo { padding:20px; text-align:center; border-bottom:2px solid var(--cgv-red); margin-bottom:20px; }
        .sidebar-logo h3 { margin:0; color:var(--cgv-red); font-weight:700; }
        .sidebar-menu { list-style:none; margin:0; padding:0; }
        .sidebar-menu li { border-bottom:1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display:block; padding:15px 20px; color:#fff; text-decoration:none; border-left:3px solid transparent; }
        .sidebar-menu a:hover,.sidebar-menu a.active { background:var(--cgv-red); border-left-color:var(--cgv-gold); }
        .sidebar-menu i { margin-right:10px; width:20px; text-align:center; }

        .main-content { margin-left:250px; flex:1; padding:30px; }
        .panel { background:#fff; border-radius:8px; padding:24px; box-shadow:0 2px 4px rgba(0,0,0,.1); max-width:860px; }
        .panel h1 { margin-top:0; margin-bottom:16px; color:var(--cgv-dark); }
        .field { margin-bottom:14px; }
        .field label { display:block; font-weight:600; margin-bottom:6px; }
        .field input,.field textarea,.field select { width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; }
        .btn-submit { background:var(--cgv-red); color:#fff; border:none; padding:10px 16px; border-radius:8px; cursor:pointer; }
        .btn-back { display:inline-block; background:#e5e7eb; color:#111827; text-decoration:none; padding:10px 16px; border-radius:8px; }
        .flash { margin-bottom:12px; padding:10px 12px; border-radius:8px; }
        .flash.error { background:#ffebee; border:1px solid #ffcdd2; }
        .preview { margin-bottom:14px; }
        .preview img { width: 210px; height: 120px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; }

        @media (max-width:768px) {
            .sidebar { width:100%; position:relative; height:auto; }
            .main-content { margin-left:0; padding:15px; }
        }
    </style>
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-logo"><h3><i class="fas fa-cogs"></i> Admin</h3></div>
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
        <section class="panel">
            <h1>Sửa Tin Tức</h1>

            <?php if (!empty($flash)): ?>
                <div class="flash error"><?= htmlspecialchars((string)($flash['message'] ?? '')) ?></div>
            <?php endif; ?>

            <div class="preview">
                <strong>Ảnh hiện tại:</strong><br>
                <img src="<?= htmlspecialchars($currentImageUrl) ?>" alt="Ảnh tin hiện tại">
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="field">
                    <label for="title">Tiêu đề</label>
                    <input id="title" type="text" name="title" required minlength="4" value="<?= htmlspecialchars((string)($article['title'] ?? '')) ?>">
                </div>

                <div class="field">
                    <label for="category">Danh mục</label>
                    <select id="category" name="category">
                        <option value="tin-tuc" <?= (($article['category'] ?? '') === 'tin-tuc') ? 'selected' : '' ?>>Tin tức</option>
                        <option value="khuyen-mai" <?= (($article['category'] ?? '') === 'khuyen-mai') ? 'selected' : '' ?>>Khuyến mãi</option>
                        <option value="su-kien" <?= (($article['category'] ?? '') === 'su-kien') ? 'selected' : '' ?>>Sự kiện</option>
                    </select>
                </div>

                <div class="field">
                    <label for="content">Nội dung</label>
                    <textarea id="content" name="content" rows="8" required minlength="10"><?= htmlspecialchars((string)($article['content'] ?? '')) ?></textarea>
                </div>

                <div class="field">
                    <label for="image">Đổi ảnh tin tức (không bắt buộc)</label>
                    <input id="image" type="file" name="image" accept="image/*">
                </div>

                <div class="actions">
                    <button type="submit" class="btn-submit">Lưu thay đổi</button>
                    <a href="<?= BASE_URL ?>admin/news" class="btn-back">Quay lại</a>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
