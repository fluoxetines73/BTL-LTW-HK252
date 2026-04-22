<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<section class="panel">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0">Quản lý bình luận/đánh giá bài viết</h1>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/news">Quản lý tin tức</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="get" action="<?= BASE_URL ?>admin/reviews" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="<?= htmlspecialchars((string)($keyword ?? '')) ?>" class="form-control" placeholder="Tìm theo bài viết, người dùng, nội dung">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= ($statusFilter ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="rejected" <?= ($statusFilter ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Lọc</button>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/reviews">Đặt lại</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Bài viết</th>
                    <th style="width: 160px;">Người dùng</th>
                    <th style="width: 90px;">Sao</th>
                    <th>Nội dung</th>
                    <th style="width: 120px;">Trạng thái</th>
                    <th style="width: 260px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars((string)$item['news_title']) ?></strong><br>
                                <a href="<?= BASE_URL ?>news/detail/<?= (int)$item['news_id'] ?>" target="_blank" rel="noopener">Xem bài</a>
                            </td>
                            <td><?= htmlspecialchars((string)$item['full_name']) ?></td>
                            <td><?= (int)$item['rating'] ?>/5</td>
                            <td><?= nl2br(htmlspecialchars((string)$item['comment'])) ?></td>
                            <td>
                                <?php if (($item['status'] ?? '') === 'approved'): ?>
                                    <span class="badge text-bg-success">Approved</span>
                                <?php elseif (($item['status'] ?? '') === 'rejected'): ?>
                                    <span class="badge text-bg-danger">Rejected</span>
                                <?php else: ?>
                                    <span class="badge text-bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-success" href="<?= BASE_URL ?>admin/review_status/<?= (int)$item['id'] ?>/approved">Duyệt</a>
                                <a class="btn btn-sm btn-warning" href="<?= BASE_URL ?>admin/review_status/<?= (int)$item['id'] ?>/rejected">Từ chối</a>
                                <a class="btn btn-sm btn-secondary" href="<?= BASE_URL ?>admin/review_status/<?= (int)$item['id'] ?>/pending">Đặt pending</a>
                                <a class="btn btn-sm btn-danger" href="<?= BASE_URL ?>admin/delete_review/<?= (int)$item['id'] ?>" onclick="return confirm('Xóa bình luận/đánh giá này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có bình luận/đánh giá nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
