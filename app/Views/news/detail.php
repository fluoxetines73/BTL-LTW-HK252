<section class="panel">
    <a href="<?= BASE_URL ?>news">&larr; Quay lai danh sach tin</a>
    <h1><?= htmlspecialchars($article['title'] ?? 'Bai viet') ?></h1>
    <?php if (!empty($article['published_at'])): ?>
        <p><small><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string)$article['published_at']))) ?></small></p>
    <?php endif; ?>
    <?php if (!empty($articleImageUrl)): ?>
        <p>
            <img src="<?= htmlspecialchars((string)$articleImageUrl) ?>" alt="<?= htmlspecialchars((string)($article['title'] ?? 'Bai viet')) ?>" style="width: 100%; max-height: 420px; object-fit: cover; border-radius: 10px;">
        </p>
    <?php endif; ?>
    <hr>
    <p><?= htmlspecialchars($article['content'] ?? 'Dang cap nhat noi dung.') ?></p>
</section>
