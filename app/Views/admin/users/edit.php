<?php
/**
 * Admin Edit User Form
 * Form chỉnh sửa thông tin người dùng với upload avatar
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Chỉnh sửa Người dùng') ?></title>
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

        .form-container {
            background: var(--cgv-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--cgv-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--cgv-red);
            box-shadow: 0 0 0 3px rgba(231, 26, 15, 0.1);
        }

        .form-group input.error,
        .form-group select.error {
            border-color: #f44336;
        }

        .avatar-section {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .avatar-preview {
            flex-shrink: 0;
        }

        .avatar-preview img {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid var(--cgv-red);
        }

        .avatar-upload {
            flex: 1;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
            width: 100%;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 15px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-wrapper:hover .file-input-label {
            background: #e9ecef;
            border-color: var(--cgv-red);
        }

        .file-input-label i {
            display: block;
            font-size: 32px;
            color: var(--cgv-red);
            margin-bottom: 10px;
        }

        .file-input-label strong {
            display: block;
            margin-bottom: 5px;
        }

        .file-input-label small {
            color: #999;
            display: block;
            margin-top: 5px;
        }

        .info-bar {
            padding: 15px 20px;
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .error-list {
            background: #ffebee;
            border-left-color: #f44336;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-list li {
            color: #f44336;
            margin: 5px 0;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--cgv-red);
            color: white;
        }

        .btn-primary:hover {
            background: #c91608;
        }

        .btn-secondary {
            background: #e9ecef;
            color: var(--cgv-dark);
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .form-help {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
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

            .form-container {
                padding: 20px;
            }

            .avatar-section {
                flex-direction: column;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
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
                <div>
                    <h1><?= htmlspecialchars($title ?? 'Chỉnh sửa Người dùng') ?></h1>
                    <small style="color: #999;">ID: <?= htmlspecialchars($user['id']) ?></small>
                </div>
                <a href="<?= BASE_URL ?>admin/users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="error-list">
                    <i class="fas fa-exclamation-circle"></i> Vui lòng sửa các lỗi sau:
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="info-bar">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Form Container -->
            <div class="form-container">
                <!-- Avatar Section -->
                <div class="avatar-section">
                    <div class="avatar-preview">
                        <img id="avatarPreview" 
                             src="<?php 
                                if (!empty($user['avatar'])) {
                                    $avatarPath = (string)$user['avatar'];
                                    if (!str_starts_with($avatarPath, 'public/')) {
                                        $avatarPath = 'public/' . ltrim($avatarPath, '/');
                                    }
                                    echo BASE_URL . htmlspecialchars($avatarPath);
                                } else {
                                    echo BASE_URL . 'public/uploads/avatars/default-avatar.svg';
                                }
                             ?>" 
                             alt="<?= htmlspecialchars($user['full_name'] ?? 'User') ?>">
                    </div>
                    <div class="avatar-upload">
                        <h5>Avatar</h5>
                        <div class="file-input-wrapper">
                            <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                            <label for="avatarInput" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <strong>Chọn tệp ảnh</strong>
                                <small>hoặc kéo thả ảnh vào đây</small>
                            </label>
                        </div>
                        <div class="form-help">
                            ✓ Định dạng: JPG, PNG, WebP, GIF<br>
                            ✓ Tối đa: 5MB<br>
                            ✓ Kích thước: 150x150px trở lên
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form id="editForm" method="POST" enctype="multipart/form-data" novalidate>
                    <!-- Hidden Avatar Upload Field -->
                    <input type="file" id="avatarFileInput" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif" 
                           style="display: none;">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Tên <span style="color: #f44336;">*</span></label>
                            <input type="text" id="name" name="name" 
                                value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" 
                                   placeholder="Nhập tên người dùng" required>
                            <div class="form-error" style="color: #f44336; font-size: 12px; display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span style="color: #f44336;">*</span></label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                                   placeholder="Nhập email" required>
                            <div class="form-error" style="color: #f44336; font-size: 12px; display: none;"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                   placeholder="Nhập số điện thoại">
                            <div class="form-help">Ví dụ: 0123456789 hoặc +84123456789</div>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" id="address" name="address" 
                                value="" 
                                   placeholder="Nhập địa chỉ">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="role">Vai trò <span style="color: #f44336;">*</span></label>
                            <select id="role" name="role" required>
                                <option value="">-- Chọn vai trò --</option>
                                <option value="member" <?= ($user['role'] === 'member' ? 'selected' : '') ?>>Thành viên (Member)</option>
                                <option value="admin" <?= ($user['role'] === 'admin' ? 'selected' : '') ?>>Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái <span style="color: #f44336;">*</span></label>
                            <select id="status" name="status" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="active" <?= ($user['status'] === 'active' ? 'selected' : '') ?>>Hoạt động</option>
                                <option value="inactive" <?= ($user['status'] === 'inactive' ? 'selected' : '') ?>>Khóa</option>
                            </select>
                        </div>
                    </div>

                    <!-- User Info (Read-only) -->
                    <div class="form-row" style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <div class="form-group">
                            <label>Ngày tạo</label>
                            <input type="text" value="<?= htmlspecialchars($user['created_at'] ?? 'N/A') ?>" 
                                   disabled readonly style="background: #f5f5f5; cursor: not-allowed;">
                        </div>

                        <div class="form-group">
                            <label>Cập nhật lần cuối</label>
                            <input type="text" value="<?= htmlspecialchars($user['updated_at'] ?? 'N/A') ?>" 
                                   disabled readonly style="background: #f5f5f5; cursor: not-allowed;">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <a href="<?= BASE_URL ?>admin/users" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Avatar preview handling
        const avatarInput = document.getElementById('avatarInput');
        const avatarFileInput = document.getElementById('avatarFileInput');
        const avatarPreview = document.getElementById('avatarPreview');

        avatarInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                // Validate file
                const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Vui lòng chọn ảnh (JPG, PNG, WebP, GIF).');
                    this.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('Tệp quá lớn. Tối đa 5MB.');
                    this.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);

                // Update file input for form submission
                avatarFileInput.files = this.files;
            }
        });

        // Drag and drop
        const fileLabel = document.querySelector('.file-input-label');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, () => {
                fileLabel.style.background = '#e9ecef';
                fileLabel.style.borderColor = '#E71A0F';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, () => {
                fileLabel.style.background = '#f8f9fa';
                fileLabel.style.borderColor = '#dee2e6';
            });
        });

        fileLabel.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            avatarInput.files = files;
            avatarInput.dispatchEvent(new Event('change'));
        });

        // Form validation
        const form = document.getElementById('editForm');
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate name
            const name = document.getElementById('name');
            if (!name.value.trim() || name.value.trim().length < 2) {
                name.classList.add('error');
                isValid = false;
            } else {
                name.classList.remove('error');
            }

            // Validate email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRegex.test(email.value)) {
                email.classList.add('error');
                isValid = false;
            } else {
                email.classList.remove('error');
            }

            // Validate phone if provided
            const phone = document.getElementById('phone');
            if (phone.value.trim() && !/^[0-9\-\+\s]{10,}$/.test(phone.value)) {
                phone.classList.add('error');
                isValid = false;
            } else {
                phone.classList.remove('error');
            }

            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng kiểm tra lại các trường có lỗi.');
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
