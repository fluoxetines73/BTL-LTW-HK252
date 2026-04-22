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

<style>
.news-slider-image {
    height: 420px;
    object-fit: cover;
    filter: brightness(0.72);
}
.news-slider-caption {
    background: rgba(0, 0, 0, 0.45);
    padding: 16px;
    border-radius: 12px;
    max-width: 640px;
}
.timeline-wrap {
    position: relative;
    padding: 10px 0 40px;
}
.timeline-center-line {
    position: absolute;
    top: 64px;
    bottom: 0;
    left: 50%;
    width: 4px;
    transform: translateX(-50%);
    background: linear-gradient(to bottom, #E71A0F 0%, #ff695f 100%);
    border-radius: 99px;
}
.timeline-row {
    position: relative;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px;
    align-items: center;
    margin-bottom: 28px;
    opacity: 0;
    transform: translateY(24px);
    transition: all 0.55s ease;
}
.timeline-row.visible {
    opacity: 1;
    transform: translateY(0);
}
.timeline-row::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #E71A0F;
    border: 3px solid #fff;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 0 4px rgba(231, 26, 15, 0.18);
    z-index: 2;
}
.timeline-content-box,
.timeline-image-box {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}
.timeline-content-box {
    padding: 18px;
}
.timeline-date {
    display: inline-block;
    font-size: 13px;
    font-weight: 700;
    color: #E71A0F;
    margin-bottom: 8px;
}
.timeline-title {
    margin-bottom: 8px;
}
.timeline-text {
    color: #555;
    margin-bottom: 8px;
}
.timeline-link {
    color: #E71A0F;
    font-weight: 600;
    text-decoration: none;
}
.timeline-image-box img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.timeline-row.left-even .timeline-content-box {
    grid-column: 1;
}
.timeline-row.left-even .timeline-image-box {
    grid-column: 2;
}
.timeline-row.right-odd .timeline-content-box {
    grid-column: 2;
}
.timeline-row.right-odd .timeline-image-box {
    grid-column: 1;
}

@media (max-width: 992px) {
    .timeline-center-line {
        left: 18px;
        transform: none;
    }
    .timeline-row {
        grid-template-columns: 1fr;
        margin-left: 36px;
    }
    .timeline-row::before {
        left: 18px;
        top: 22px;
        transform: translate(-50%, 0);
    }
    .timeline-row.left-even .timeline-content-box,
    .timeline-row.left-even .timeline-image-box,
    .timeline-row.right-odd .timeline-content-box,
    .timeline-row.right-odd .timeline-image-box {
        grid-column: 1;
    }
    .news-slider-image {
        height: 280px;
    }
}
</style>

<script>
(function () {
    const revealItems = document.querySelectorAll('.reveal-on-scroll');
    if (!('IntersectionObserver' in window) || revealItems.length === 0) {
        revealItems.forEach((el) => el.classList.add('visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    revealItems.forEach((item) => observer.observe(item));
})();
</script>
