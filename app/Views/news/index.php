<section class="panel">
    <h1>Tin tuc</h1>
    <form method="get" action="<?= BASE_URL ?>news" class="mb-3 d-flex gap-2" style="max-width: 560px;">
        <input
            type="text"
            name="q"
            value="<?= htmlspecialchars((string)($keyword ?? '')) ?>"
            class="form-control"
            placeholder="Tìm kiếm bài viết/tin tức..."
        >
        <button type="submit" class="btn btn-primary">Tìm</button>
        <?php if (!empty($keyword)): ?>
            <a href="<?= BASE_URL ?>news" class="btn btn-outline-secondary">Xóa lọc</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($keyword)): ?>
        <p class="text-muted">Kết quả cho từ khóa: <strong><?= htmlspecialchars((string)$keyword) ?></strong></p>
    <?php endif; ?>

    <div class="cards">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <article class="card">
                    <h3><?= htmlspecialchars((string)($article['title'] ?? '')) ?></h3>
                    <p>
                        <?= htmlspecialchars(mb_substr(strip_tags((string)($article['content'] ?? ($article['summary'] ?? ''))), 0, 180, 'UTF-8')) ?>...
                    </p>
                    <a href="<?= BASE_URL ?>news/detail/<?= (int)$article['id'] ?>">Xem chi tiet</a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bài viết phù hợp.</p>
        <?php endif; ?>
    </div>
</section>
