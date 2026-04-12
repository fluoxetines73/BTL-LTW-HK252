<?php
/**
 * Admin Dashboard - Trang chủ quản trị
 * Sử dụng Srtdash template style
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cgv-red: #E71A0F;
            --cgv-dark: #1A1A2E;
            --cgv-white: #FFFFFF;
            --cgv-gray: #2D2D3F;
            --cgv-gold: #D4A843;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--cgv-dark);
            color: var(--cgv-white);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            left: 0;
            top: 0;
        }

        .sidebar-logo {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid var(--cgv-red);
            margin-bottom: 20px;
        }

        .sidebar-logo h3 {
            margin: 0;
            color: var(--cgv-red);
            font-weight: bold;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: var(--cgv-white);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--cgv-red);
            border-left-color: var(--cgv-gold);
            color: var(--cgv-white);
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
        }

        .admin-header {
            background: var(--cgv-white);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
            color: var(--cgv-dark);
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .logout-btn {
            background-color: var(--cgv-red);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c91608;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--cgv-white);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--cgv-red);
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: var(--cgv-gray);
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: var(--cgv-red);
        }

        .stat-card .icon {
            font-size: 40px;
            color: var(--cgv-red);
            opacity: 0.2;
            float: right;
            margin-top: -20px;
        }

        .alert {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .admin-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                overflow-x: auto;
            }

            .admin-header h1 {
                font-size: 20px;
            }

            .stat-card .value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <h3><i class="fas fa-cogs"></i> Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= BASE_URL ?>admin/admin_dashboard" class="active">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>admin/users">
                        <i class="fas fa-users"></i> Quản lý Người dùng
                    </a>
                </li>
               <li>
                    <a href="<?= BASE_URL ?>admin/movie/index">
                        <i class="fas fa-film"></i> Quản lý Phim
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>admin/combo/index">
                        <i class="fas fa-hamburger"></i> Quản lý Combo
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>admin/news">
                        <i class="fas fa-newspaper"></i> Quản lý Tin tức
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>profile/edit">
                        <i class="fas fa-user-edit"></i> Hồ sơ cá nhân
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="admin-header">
                <div>
                    <h1><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                </div>
                <div class="user-profile">
                    <div>
                        <strong><?= htmlspecialchars($_SESSION['auth_user']['name'] ?? 'Admin') ?></strong><br>
                        <small>Admin</small>
                    </div>
                    <?php if (!empty($_SESSION['auth_user']['avatar'])): ?>
                        <?php $avatarPath = (string)$_SESSION['auth_user']['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                        <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Avatar">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Avatar">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3>Tổng Người dùng</h3>
                    <div class="value">0</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <h3>Tất cả Sản phẩm</h3>
                    <div class="value">0</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                    <h3>Tất cả Tin tức</h3>
                    <div class="value">0</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <h3>Tài khoản Khóa</h3>
                    <div class="value">0</div>
                </div>
            </div>

            <!-- Content Section -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h4>Chào mừng đến Admin Panel</h4>
                <p>Sử dụng menu bên trái để quản lý nội dung website.</p>
                <div style="text-align: center; padding: 40px 0; color: #999;">
                    <p><i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 20px; display: block;"></i></p>
                    <p>Vui lòng chọn một mục từ Menu Quản trị</p>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
