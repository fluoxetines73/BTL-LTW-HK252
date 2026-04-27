<?php
$article = $article ?? [];
$title = (string)($article['title'] ?? 'Bài viết');
$highlightTitle = trim((string)($article['highlight_title'] ?? ''));
$summaryContent = trim((string)($article['content'] ?? ''));
$detailContent = trim((string)($article['detail_content'] ?? ''));
if ($detailContent === '') {
    $detailContent = $summaryContent;
}
$articleDetailHtml = (string)($articleDetailHtml ?? nl2br(htmlspecialchars($detailContent !== '' ? $detailContent : 'Đang cập nhật nội dung chi tiết.')));
?>

<section class="news-detail-page">
    <a class="news-detail-back" href="<?= BASE_URL ?>news">&larr; Quay lại danh sách tin</a>

    <?php if ($highlightTitle !== ''): ?>
        <p class="news-detail-highlight-title"><?= htmlspecialchars($highlightTitle) ?></p>
    <?php endif; ?>

    <h1 class="news-detail-main-title"><?= htmlspecialchars($title) ?></h1>

    <?php if (!empty($article['published_at'])): ?>
        <p class="news-detail-meta"><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string)$article['published_at']))) ?></p>
    <?php endif; ?>

    <div class="news-detail-content-grid">
        <?php if (!empty($articleImageUrl)): ?>
            <figure class="news-detail-figure">
                <img src="<?= htmlspecialchars((string)$articleImageUrl) ?>" alt="<?= htmlspecialchars($title) ?>">
            </figure>
        <?php endif; ?>

        <article class="news-detail-article-body">
            <?= $articleDetailHtml ?>
        </article>
    </div>
</section>
