<?php
/**
 * Admin Search Users
 * Tìm kiếm người dùng theo keyword, sắp xếp và lọc trạng thái
 */
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/users">Người dùng</a></li>
        <li class="breadcrumb-item active">Tìm kiếm</li>
    </ol>
</div>

<!-- Search & Filter Bar -->
<form class="admin-search-form" method="GET" action="<?= BASE_URL ?>admin/search">
    <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="q" class="search-input" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Tìm kiếm email hoặc tên...">
    </div>
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort ?? 'newest') ?>">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status ?? 'all') ?>">
    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
    <a href="<?= BASE_URL ?>admin/users" class="btn-reset">Xóa lọc</a>
</form>

<!-- Filter Bar: Sort & Status -->
<div class="admin-filter-bar">
    <div class="filter-group">
        <label class="filter-label">Sắp xếp:</label>
        <select class="filter-select" id="sort-select" onchange="applySearchFilter()">
            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="oldest" <?= ($sort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
            <option value="name_asc" <?= ($sort ?? 'newest') === 'name_asc' ? 'selected' : '' ?>>Tên A→Z</option>
            <option value="name_desc" <?= ($sort ?? 'newest') === 'name_desc' ? 'selected' : '' ?>>Tên Z→A</option>
            <option value="email_asc" <?= ($sort ?? 'newest') === 'email_asc' ? 'selected' : '' ?>>Email A→Z</option>
            <option value="email_desc" <?= ($sort ?? 'newest') === 'email_desc' ? 'selected' : '' ?>>Email Z→A</option>
        </select>
    </div>
    <div class="filter-group">
        <label class="filter-label">Trạng thái:</label>
        <select class="filter-select" id="status-select" onchange="applySearchFilter()">
            <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>Tất cả</option>
            <option value="active" <?= ($status ?? 'all') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
            <option value="inactive" <?= ($status ?? 'all') === 'inactive' ? 'selected' : '' ?>>Khóa</option>
        </select>
    </div>
</div>

<!-- Table Container -->
<div class="table-container">
    <!-- Info Bar -->
    <div class="info-bar">
        <strong>Kết quả: <?= htmlspecialchars($total_users ?? 0) ?> người dùng</strong>
        | Trang <?= htmlspecialchars($current_page ?? 1) ?>/<?= htmlspecialchars($total_pages ?? 1) ?>
    </div>

    <!-- Users Table -->
    <?php if (!empty($users)): ?>
        <?php
        $perPage = 10;
        $rowNumber = (($current_page ?? 1) - 1) * $perPage + 1;
        ?>
        <table class="table">
            <thead class="admin-table-header">
                <tr>
                    <th style="width: 5%">STT</th>
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
                        <td><?= htmlspecialchars((string)$rowNumber++) ?></td>
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
            <i class="fas fa-search" style="font-size: 48px; margin-bottom: 20px; display: block; color: #ddd;"></i>
            <p>Không tìm thấy người dùng nào với từ khóa: <strong><?= htmlspecialchars($keyword ?? '') ?></strong></p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if (($total_pages ?? 1) > 1 && !empty($users)): ?>
        <div style="padding: 20px; border-top: 1px solid #dee2e6;">
            <div class="pagination">
                <!-- Previous -->
                <?php if ($current_page > 1): ?>
                    <a href="<?= $base_url ?>&page=<?= $current_page - 1 ?>">
                        <i class="fas fa-chevron-left"></i> Trước
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                $start = max(1, $current_page - 2);
                $end = min($total_pages, $current_page + 2);

                if ($start > 1) {
                    echo '<a href="' . $base_url . '&page=1">1</a>';
                    if ($start > 2) echo '<span>...</span>';
                }

                for ($p = $start; $p <= $end; $p++) {
                    if ($p === $current_page) {
                        echo '<span class="active">' . $p . '</span>';
                    } else {
                        echo '<a href="' . $base_url . '&page=' . $p . '">' . $p . '</a>';
                    }
                }

                if ($end < $total_pages) {
                    if ($end < $total_pages - 1) echo '<span>...</span>';
                    echo '<a href="' . $base_url . '&page=' . $total_pages . '">' . $total_pages . '</a>';
                }
                ?>

                <!-- Next -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= $base_url ?>&page=<?= $current_page + 1 ?>">
                        Tiếp <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function applySearchFilter() {
    var sort = document.getElementById('sort-select').value;
    var status = document.getElementById('status-select').value;
    var keyword = '<?= htmlspecialchars($keyword ?? '', ENT_QUOTES) ?>';
    window.location.href = '<?= BASE_URL ?>admin/search?q=' + encodeURIComponent(keyword) + '&sort=' + encodeURIComponent(sort) + '&status=' + encodeURIComponent(status);
}
</script>