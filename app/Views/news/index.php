<section class="panel">
    <h1>Tin tuc</h1>
    <div class="cards">
        <?php foreach (($articles ?? []) as $article): ?>
            <article class="card">
                <h3><?= htmlspecialchars($article['title']) ?></h3>
                <p><?= htmlspecialchars($article['summary'] ?? 'Khong co tom tat.') ?></p>
                <a href="<?= BASE_URL ?>news/detail/<?= (int)$article['id'] ?>">Xem chi tiet</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
