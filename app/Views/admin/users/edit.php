<?php
/**
 * Admin Edit User Form
 * Form chỉnh sửa thông tin ngườii dùng với upload avatar
 */
?>

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

<!-- Form Container -->
<div class="form-container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <small style="color: #999;">ID: <?= htmlspecialchars($user['id']) ?></small>
        </div>
        <a href="<?= BASE_URL ?>admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

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
                       placeholder="Nhập tên ngườii dùng" required>
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

<?php
$extraScripts = ($extraScripts ?? '') . <<<'SCRIPT'
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
SCRIPT;
