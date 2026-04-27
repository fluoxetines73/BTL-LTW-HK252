<?php
$latestNews = $latestNews ?? [];
$timelineItems = $timelineItems ?? [];
$timelineTitle = $timelineTitle ?? 'Timeline Tin Tức';
?>
<section class="news-page">
    <?php if (!empty($latestNews)): ?>
    <div id="newsTopSlider" class="carousel slide news-slider mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($latestNews as $index => $item): ?>
                <button type="button" data-bs-target="#newsTopSlider" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner rounded-4 overflow-hidden shadow-sm">
            <?php foreach ($latestNews as $index => $item): ?>
                <?php
                $imagePath = trim((string)($item['image'] ?? ''));
                if ($imagePath === '') {
                    $sliderImage = BASE_URL . 'public/images/about/about-6.png';
                } elseif (str_starts_with($imagePath, 'public/')) {
                    $sliderImage = BASE_URL . $imagePath;
                } elseif (str_starts_with($imagePath, 'uploads/')) {
                    $sliderImage = BASE_URL . 'public/' . ltrim($imagePath, '/');
                } elseif (preg_match('#^https?://#i', $imagePath) === 1) {
                    $sliderImage = $imagePath;
                } else {
                    $sliderImage = BASE_URL . 'public/' . ltrim($imagePath, '/');
                }
                ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars($sliderImage) ?>" class="d-block w-100 news-slider-image" alt="<?= htmlspecialchars((string)($item['title'] ?? 'Tin tức')) ?>">
                    <div class="carousel-caption d-md-block text-start news-slider-caption">
                        <?php if (!empty($item['highlight_title'])): ?>
                            <div class="news-highlight-title"><?= htmlspecialchars((string)$item['highlight_title']) ?></div>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars((string)($item['title'] ?? 'Tin tức')) ?></h3>
                        <p><?= htmlspecialchars(mb_strimwidth(strip_tags((string)($item['content'] ?? '')), 0, 170, '...')) ?></p>
                        <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>news/detail/<?= (int)($item['id'] ?? 0) ?>">Xem chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#newsTopSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newsTopSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <?php else: ?>
        <div class="alert alert-info mb-4">Chưa có dữ liệu tin tức để hiển thị slider.</div>
    <?php endif; ?>

    <div class="timeline-wrap">
        <h2 class="fw-bold mb-4"><?= htmlspecialchars((string)$timelineTitle) ?></h2>
        <div class="timeline-center-line"></div>

        <?php foreach ($timelineItems as $item): ?>
            <article class="timeline-row <?= !empty($item['is_even_day']) ? 'left-even' : 'right-odd' ?> reveal-on-scroll">
                <div class="timeline-content-box">
                    <span class="timeline-date"><?= htmlspecialchars((string)($item['display_date'] ?? '')) ?></span>
                    <?php if (!empty($item['highlight_title'])): ?>
                        <p class="timeline-highlight-title"><?= htmlspecialchars((string)$item['highlight_title']) ?></p>
                    <?php endif; ?>
                    <h4 class="timeline-title"><?= htmlspecialchars((string)($item['title'] ?? 'Tin tức')) ?></h4>
                    <p class="timeline-text"><?= htmlspecialchars(mb_strimwidth(strip_tags((string)($item['content'] ?? '')), 0, 210, '...')) ?></p>
                    <a href="<?= BASE_URL ?>news/detail/<?= (int)($item['id'] ?? 0) ?>" class="timeline-link">Đọc thêm</a>
                </div>
                <div class="timeline-image-box">
                    <img src="<?= htmlspecialchars((string)($item['image_url'] ?? '')) ?>" alt="<?= htmlspecialchars((string)($item['title'] ?? 'Tin tức')) ?>">
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
