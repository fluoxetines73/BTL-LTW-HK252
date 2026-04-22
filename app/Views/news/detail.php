<section class="panel">
    <a href="<?= BASE_URL ?>news">&larr; Quay lai danh sach tin</a>
    <h1><?= htmlspecialchars($article['title'] ?? 'Bai viet') ?></h1>
    <p class="text-muted">
        Danh mục: <?= htmlspecialchars((string)($article['category'] ?? 'Tin tức')) ?>
        <?php if (!empty($article['author_name'])): ?>
            | Tác giả: <?= htmlspecialchars((string)$article['author_name']) ?>
        <?php endif; ?>
    </p>
    <hr>
    <p><?= nl2br(htmlspecialchars($article['content'] ?? 'Dang cap nhat noi dung.')) ?></p>

    <hr>
    <h2>Đánh giá bài viết</h2>
    <p>
        Điểm trung bình: <strong><?= htmlspecialchars((string)($reviewSummary['avg_rating'] ?? 0)) ?>/5</strong>
        (<?= (int)($reviewSummary['total_reviews'] ?? 0) ?> đánh giá)
    </p>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= ($flash['type'] ?? 'info') === 'success' ? 'success' : 'danger' ?>">
            <?= htmlspecialchars((string)($flash['message'] ?? '')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($authUser)): ?>
        <form method="post" action="<?= BASE_URL ?>news/detail/<?= (int)($article['id'] ?? 0) ?>" class="mb-4" style="max-width: 720px;">
            <div class="mb-3">
                <label class="form-label" for="rating">Số sao</label>
                <select class="form-select" id="rating" name="rating" required>
                    <option value="">Chọn số sao</option>
                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <option value="<?= $star ?>"><?= $star ?> sao</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="comment">Bình luận</label>
                <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Nhập nhận xét của bạn" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
    <?php else: ?>
        <p><a href="<?= BASE_URL ?>auth/login">Đăng nhập</a> để gửi bình luận/đánh giá.</p>
    <?php endif; ?>

    <div>
        <h3>Ý kiến từ người dùng</h3>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <article class="border rounded p-3 mb-3">
                    <p class="mb-1"><strong><?= htmlspecialchars((string)($review['full_name'] ?? 'Người dùng')) ?></strong></p>
                    <p class="mb-1">Đánh giá: <?= (int)($review['rating'] ?? 0) ?>/5</p>
                    <p class="mb-0"><?= nl2br(htmlspecialchars((string)($review['comment'] ?? ''))) ?></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có đánh giá đã duyệt cho bài viết này.</p>
        <?php endif; ?>
    </div>
</section>
