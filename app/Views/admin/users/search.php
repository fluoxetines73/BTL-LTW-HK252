<section class="panel">

    <?php $sort = (string)($sort ?? 'id_asc'); ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0"><?= htmlspecialchars($title ?? 'Tìm kiếm Người dùng') ?></h1>
        <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-secondary">Quay lại danh sách</a>
    </div>

    <form class="row g-2 mb-3" method="get" action="<?= BASE_URL ?>admin/search">
        <div class="col-md-8">
            <input type="text" class="form-control" name="q" value="<?= htmlspecialchars((string)($keyword ?? '')) ?>" placeholder="Tìm kiếm email hoặc tên..." required>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <button class="btn btn-dark" type="submit">Tìm kiếm</button>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/users">Danh sách đầy đủ</a>
        </div>
    </form>

    <form class="row g-2 mb-3" method="get" action="<?= BASE_URL ?>admin/search">
        <input type="hidden" name="q" value="<?= htmlspecialchars((string)($keyword ?? '')) ?>">
        <div class="col-md-4">
            <label for="sort" class="form-label mb-1">Sắp xếp</label>
            <select id="sort" name="sort" class="form-select" onchange="this.form.submit()">
                <option value="id_asc" <?= $sort === 'id_asc' ? 'selected' : '' ?>>ID tăng dần (mặc định)</option>
                <option value="id_desc" <?= $sort === 'id_desc' ? 'selected' : '' ?>>ID giảm dần</option>
                <option value="created_desc" <?= $sort === 'created_desc' ? 'selected' : '' ?>>Mới tạo trước</option>
                <option value="created_asc" <?= $sort === 'created_asc' ? 'selected' : '' ?>>Cũ tạo trước</option>
            </select>
        </div>
    </form>

    <p class="text-muted">Từ khóa: <strong><?= htmlspecialchars((string)($keyword ?? '')) ?></strong> | Kết quả <?= (int)($total_users ?? 0) ?> người dùng</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Thông tin</th>
                    <th>Ngày tạo</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td>
                                <?php
                                $avatar = !empty($u['avatar']) ? (string)$u['avatar'] : 'uploads/avatars/default-avatar.svg';
                                $avatar = str_starts_with($avatar, 'public/') ? $avatar : ('public/' . ltrim($avatar, '/'));
                                ?>
                                <img src="<?= BASE_URL . htmlspecialchars($avatar) ?>" alt="Avatar" width="42" height="42" style="object-fit: cover; border-radius: 50%;">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars((string)($u['full_name'] ?? '')) ?></strong><br>
                                <small><?= htmlspecialchars((string)($u['email'] ?? '')) ?></small>
                            </td>
                            <td>
                                <?php
                                $createdAt = (string)($u['created_at'] ?? '');
                                if ($createdAt !== '' && strtotime($createdAt) !== false):
                                ?>
                                    <small><?= htmlspecialchars(date('d/m/Y H:i', strtotime($createdAt))) ?></small>
                                <?php else: ?>
                                    <small class="text-muted">N/A</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= ($u['role'] ?? '') === 'admin' ? 'text-bg-warning' : 'text-bg-secondary' ?>">
                                    <?= htmlspecialchars((string)($u['role'] ?? 'member')) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= ($u['status'] ?? '') === 'active' ? 'text-bg-success' : 'text-bg-danger' ?>">
                                    <?= ($u['status'] ?? '') === 'active' ? 'Hoạt động' : 'Khóa' ?>
                                </span>
                            </td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>admin/edit_user/<?= (int)$u['id'] ?>">Sửa</a>
                                <?php if (($u['status'] ?? '') === 'active'): ?>
                                    <a class="btn btn-sm btn-outline-warning" href="<?= BASE_URL ?>admin/lock_user/<?= (int)$u['id'] ?>" onclick="return confirm('Khóa tài khoản này?');">Khóa</a>
                                <?php else: ?>
                                    <a class="btn btn-sm btn-outline-success" href="<?= BASE_URL ?>admin/unlock_user/<?= (int)$u['id'] ?>">Mở khóa</a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>admin/reset_password/<?= (int)$u['id'] ?>" onclick="return confirm('Đặt lại mật khẩu?');">Reset pass</a>
                                <?php if ((int)($_SESSION['auth_user']['id'] ?? 0) !== (int)$u['id']): ?>
                                    <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>admin/delete_user/<?= (int)$u['id'] ?>" onclick="return confirm('Xóa người dùng này?');">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không tìm thấy người dùng theo từ khóa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (($total_pages ?? 1) > 1 && !empty($users)): ?>
        <nav aria-label="Pagination search users">
            <ul class="pagination">
                <?php if (($current_page ?? 1) > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?= htmlspecialchars((string)$base_url) ?>&page=<?= (int)$current_page - 1 ?>">Trước</a></li>
                <?php endif; ?>
                <?php for ($p = 1; $p <= (int)$total_pages; $p++): ?>
                    <li class="page-item <?= $p === (int)$current_page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= htmlspecialchars((string)$base_url) ?>&page=<?= $p ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <?php if (($current_page ?? 1) < ($total_pages ?? 1)): ?>
                    <li class="page-item"><a class="page-link" href="<?= htmlspecialchars((string)$base_url) ?>&page=<?= (int)$current_page + 1 ?>">Tiếp</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</section>
