<?php
/**
 * Admin Users List - Danh sách Ngườii dùng
 * với phân trang (pagination) và các action
 */
?>

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
        <strong>Tổng: <?= htmlspecialchars($total_users ?? 0) ?> ngườii dùng</strong>
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
                                   onclick="return confirm('Đặt lại mật khẩu của ngườii dùng này?');" 
                                   title="Đặt lại mật khẩu">
                                    <i class="fas fa-key"></i> Đặt lại
                                </a>
                                <?php if ($_SESSION['auth_user']['id'] !== $user['id']): ?>
                                    <a href="<?= BASE_URL ?>admin/delete_user/<?= htmlspecialchars($user['id']) ?>" 
                                       class="btn-sm btn-delete" 
                                       onclick="return confirm('Xóa ngườii dùng này? Hành động này không thể hoàn lại!');" 
                                       title="Xóa ngườii dùng">
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
            <p>Không tìm thấy ngườii dùng nào.</p>
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
