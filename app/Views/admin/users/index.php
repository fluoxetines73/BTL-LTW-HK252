<?php
/**
 * Admin Users List - Danh sách Người dùng
 * với phân trang (pagination) và các action
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Quản lý Người dùng') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .table-container {
            background: var(--cgv-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex: 1;
            max-width: 400px;
        }

        .search-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-form button {
            padding: 10px 20px;
            background: var(--cgv-red);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-form button:hover {
            background: #c91608;
        }

        .table {
            margin: 0;
            border: none;
        }

        .table thead {
            background: var(--cgv-dark);
            color: white;
        }

        .table th {
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            border-color: #dee2e6;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cgv-red);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }

        .badge.admin {
            background: var(--cgv-gold);
            color: var(--cgv-dark);
        }

        .badge.member {
            background: #e9ecef;
            color: #495057;
        }

        .badge.active {
            background: #4CAF50;
            color: white;
        }

        .badge.locked {
            background: #f44336;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #2196F3;
            color: white;
        }

        .btn-edit:hover {
            background: #1565c0;
        }

        .btn-lock {
            background: #ff9800;
            color: white;
        }

        .btn-lock:hover {
            background: #f57c00;
        }

        .btn-unlock {
            background: #4CAF50;
            color: white;
        }

        .btn-unlock:hover {
            background: #45a049;
        }

        .btn-reset {
            background: #673ab7;
            color: white;
        }

        .btn-reset:hover {
            background: #512da8;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #da190b;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            color: var(--cgv-dark);
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: var(--cgv-red);
            color: white;
            border-color: var(--cgv-red);
        }

        .pagination .active {
            background: var(--cgv-red);
            color: white;
            border-color: var(--cgv-red);
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .info-bar {
            padding: 15px 20px;
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
            border-radius: 4px;
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

            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .search-form {
                max-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-sm {
                width: 100%;
                text-align: center;
            }

            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 10px;
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
                    <a href="<?= BASE_URL ?>admin/admin_dashboard">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>admin/users" class="active">
                        <i class="fas fa-users"></i> Quản lý Người dùng
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>admin/products">
                        <i class="fas fa-box"></i> Quản lý Sản phẩm
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
                <h1><?= htmlspecialchars($title ?? 'Quản lý Người dùng') ?></h1>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="info-bar" style="background: #e8f5e9; border-color: #4CAF50;">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="info-bar" style="background: #ffebee; border-color: #f44336;">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Table Container -->
            <div class="table-container">
                <!-- Table Header with Search -->
                <div class="table-header">
                    <form class="search-form" method="GET" action="<?= BASE_URL ?>admin/search">
                        <input type="text" name="q" placeholder="Tìm kiếm email hoặc tên..." required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Info Bar -->
                <div class="info-bar">
                    <strong>Tổng: <?= htmlspecialchars($total_users ?? 0) ?> người dùng</strong>
                    | Trang <?= htmlspecialchars($current_page ?? 1) ?>/<?= htmlspecialchars($total_pages ?? 1) ?>
                </div>

                <!-- Users Table -->
                <?php if (!empty($users)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 15%">Avatar</th>
                                <th style="width: 25%">Tên / Email</th>
                                <th style="width: 15%">Vai trò</th>
                                <th style="width: 15%">Trạng thái</th>
                                <th style="width: 25%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td>
                                        <?php if (!empty($user['avatar'])): ?>
                                            <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                                            <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" 
                                                 alt="<?= htmlspecialchars($user['full_name'] ?? 'User') ?>" 
                                                 class="user-avatar">
                                        <?php else: ?>
                                            <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" 
                                                 alt="<?= htmlspecialchars($user['full_name'] ?? 'User') ?>" 
                                                 class="user-avatar">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($user['full_name'] ?? '') ?></strong><br>
                                        <small style="color: #999;"><?= htmlspecialchars($user['email']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $user['role'] === 'admin' ? 'admin' : 'member' ?>">
                                            <?= $user['role'] === 'admin' ? 'Admin' : 'Member' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $user['status'] === 'active' ? 'active' : 'locked' ?>">
                                            <?= $user['status'] === 'active' ? 'Hoạt động' : 'Khóa' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= BASE_URL ?>admin/edit_user/<?= htmlspecialchars($user['id']) ?>" 
                                               class="btn-sm btn-edit" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <?php if ($user['status'] === 'active'): ?>
                                                <a href="<?= BASE_URL ?>admin/lock_user/<?= htmlspecialchars($user['id']) ?>" 
                                                   class="btn-sm btn-lock" 
                                                   onclick="return confirm('Khóa tài khoản này?');" 
                                                   title="Khóa tài khoản">
                                                    <i class="fas fa-lock"></i> Khóa
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>admin/unlock_user/<?= htmlspecialchars($user['id']) ?>" 
                                                   class="btn-sm btn-unlock" 
                                                   title="Mở khóa tài khoản">
                                                    <i class="fas fa-unlock"></i> Mở khóa
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= BASE_URL ?>admin/reset_password/<?= htmlspecialchars($user['id']) ?>" 
                                               class="btn-sm btn-reset" 
                                               onclick="return confirm('Đặt lại mật khẩu của người dùng này?');" 
                                               title="Đặt lại mật khẩu">
                                                <i class="fas fa-key"></i> Đặt lại
                                            </a>
                                            <?php if ($_SESSION['auth_user']['id'] !== $user['id']): ?>
                                                <a href="<?= BASE_URL ?>admin/delete_user/<?= htmlspecialchars($user['id']) ?>" 
                                                   class="btn-sm btn-delete" 
                                                   onclick="return confirm('Xóa người dùng này? Hành động này không thể hoàn lại!');" 
                                                   title="Xóa người dùng">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 20px; display: block; color: #ddd;"></i>
                        <p>Không tìm thấy người dùng nào.</p>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if (($total_pages ?? 1) > 1): ?>
                    <div style="padding: 20px; border-top: 1px solid #dee2e6;">
                        <div class="pagination">
                            <!-- Previous -->
                            <?php if ($current_page > 1): ?>
                                <a href="<?= htmlspecialchars($base_url) ?>/<?= $current_page - 1 ?>">
                                    <i class="fas fa-chevron-left"></i> Trước
                                </a>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $current_page - 2);
                            $end = min($total_pages, $current_page + 2);

                            if ($start > 1) {
                                echo '<a href="' . htmlspecialchars($base_url) . '/1">1</a>';
                                if ($start > 2) echo '<span>...</span>';
                            }

                            for ($p = $start; $p <= $end; $p++) {
                                if ($p === $current_page) {
                                    echo '<span class="active">' . $p . '</span>';
                                } else {
                                    echo '<a href="' . htmlspecialchars($base_url) . '/' . $p . '">' . $p . '</a>';
                                }
                            }

                            if ($end < $total_pages) {
                                if ($end < $total_pages - 1) echo '<span>...</span>';
                                echo '<a href="' . htmlspecialchars($base_url) . '/' . $total_pages . '">' . $total_pages . '</a>';
                            }
                            ?>

                            <!-- Next -->
                            <?php if ($current_page < $total_pages): ?>
                                <a href="<?= htmlspecialchars($base_url) ?>/<?= $current_page + 1 ?>">
                                    Tiếp <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
