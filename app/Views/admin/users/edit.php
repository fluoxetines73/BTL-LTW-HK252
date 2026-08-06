<?php
/**
 * Admin Edit User Form
 * Form chỉnh sửa thông tin ngườii dùng với upload avatar
 */
?>

<style>
.user-edit-page {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.user-edit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.user-edit-title {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 700;
    color: #1f2937;
}

.avatar-section {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    align-items: start;
    margin-bottom: 22px;
}

.avatar-preview img {
    width: 220px;
    height: 220px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #d1d5db;
}

.avatar-upload h5 {
    margin-bottom: 10px;
    font-weight: 700;
}

.file-input-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 110px;
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    background: #f9fafb;
    color: #374151;
    cursor: pointer;
    transition: all .2s ease;
    padding: 10px;
    text-align: center;
}

.file-input-label:hover {
    border-color: #E71A0F;
    background: #fff5f4;
}

.file-input-label i {
    font-size: 20px;
    color: #E71A0F;
    margin-bottom: 6px;
}

.form-help {
    color: #6b7280;
    font-size: 0.88rem;
    margin-top: 10px;
    line-height: 1.55;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 14px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select {
    width: 100%;
    height: 42px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #E71A0F;
    box-shadow: 0 0 0 3px rgba(231, 26, 15, 0.12);
}

.form-group input.error,
.form-group select.error {
    border-color: #ef4444;
}

.button-group {
    display: flex;
    gap: 10px;
    margin-top: 14px;
}

.error-list {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 16px;
}

@media (max-width: 900px) {
    .avatar-section,
    .form-row {
        grid-template-columns: 1fr;
    }

    .avatar-preview img {
        width: 100%;
        max-width: 280px;
        height: auto;
    }
}
</style>

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
<div class="form-container user-edit-page">
    <div class="user-edit-header">
        <h2 class="user-edit-title"><i class="fas fa-user-edit"></i> Chỉnh sửa người dùng</h2>
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
                    value="<?= htmlspecialchars($user['address'] ?? '') ?>" 
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
