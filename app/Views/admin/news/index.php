<?php
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<section class="panel">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0">Quản lý tin tức</h1>
        <a class="btn btn-primary" href="<?= BASE_URL ?>admin/create_news">Thêm bài viết</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="get" action="<?= BASE_URL ?>admin/news" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="q" value="<?= htmlspecialchars((string)($keyword ?? '')) ?>" class="form-control" placeholder="Tìm theo tiêu đề/nội dung/slug">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Tìm kiếm</button>
            <?php if (!empty($keyword)): ?>
                <a href="<?= BASE_URL ?>admin/news" class="btn btn-outline-secondary">Xóa lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Tiêu đề</th>
                    <th style="width: 140px;">Danh mục</th>
                    <th style="width: 140px;">Trạng thái</th>
                    <th style="width: 160px;">Tác giả</th>
                    <th style="width: 220px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $item): ?>
                        <tr>
                            <td><?= (int)$item['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars((string)$item['title']) ?></strong><br>
                                <small class="text-muted">Slug: <?= htmlspecialchars((string)$item['slug']) ?></small>
                            </td>
                            <td><?= htmlspecialchars((string)($categories[$item['category']] ?? $item['category'])) ?></td>
                            <td>
                                <?php if (($item['status'] ?? '') === 'published'): ?>
                                    <span class="badge text-bg-success">Published</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars((string)($item['author_name'] ?? '-')) ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>news/detail/<?= (int)$item['id'] ?>" target="_blank" rel="noopener">Xem</a>
                                <a class="btn btn-sm btn-warning" href="<?= BASE_URL ?>admin/edit_news/<?= (int)$item['id'] ?>">Sửa</a>
                                <a class="btn btn-sm btn-danger" href="<?= BASE_URL ?>admin/delete_news/<?= (int)$item['id'] ?>" onclick="return confirm('Xóa bài viết này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Chưa có bài viết nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a class="btn btn-outline-dark" href="<?= BASE_URL ?>admin/reviews">Quản lý bình luận/đánh giá</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/admin_dashboard">Về dashboard</a>
    </div>
</section>
