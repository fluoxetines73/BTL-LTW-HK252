<section class="panel">
    <a href="<?= BASE_URL ?>news">&larr; Quay lai danh sach tin</a>
    <h1><?= htmlspecialchars($article['title'] ?? 'Bai viet') ?></h1>
    <p><?= htmlspecialchars($article['summary'] ?? '') ?></p>
    <hr>
    <p><?= htmlspecialchars($article['content'] ?? 'Dang cap nhat noi dung.') ?></p>
</section>
