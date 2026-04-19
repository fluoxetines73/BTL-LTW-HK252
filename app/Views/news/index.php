<section class="news-page">
    <h1 class="mb-3">Tin tức điện ảnh</h1>

    <?php if (!empty($nowShowingMovies)): ?>
        <div class="movie-slider-wrap mb-4">
            <h2 class="section-title">Phim đang chiếu</h2>
            <div id="nowShowingCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($nowShowingMovies as $idx => $movie): ?>
                        <button type="button" data-bs-target="#nowShowingCarousel" data-bs-slide-to="<?= (int)$idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>" aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= (int)$idx + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner rounded-3 overflow-hidden">
                    <?php foreach ($nowShowingMovies as $idx => $movie): ?>
                        <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars((string)($movie['poster_url'] ?? BASE_URL . 'public/images/about/about-6.png')) ?>" class="d-block w-100 movie-slide-image" alt="<?= htmlspecialchars((string)($movie['title'] ?? 'Phim đang chiếu')) ?>">
                            <div class="carousel-caption text-start movie-slide-caption">
                                <span class="badge text-bg-danger mb-2">Đang chiếu</span>
                                <h3 class="mb-1"><?= htmlspecialchars((string)($movie['title'] ?? '')) ?></h3>
                                <p class="mb-0">Khởi chiếu: <?= htmlspecialchars((string)date('d/m/Y', strtotime((string)($movie['release_date'] ?? date('Y-m-d'))))) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#nowShowingCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#nowShowingCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <form method="get" action="<?= BASE_URL ?>news" class="mb-4 d-flex gap-2" style="max-width: 560px;">
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

    <div class="news-filter-bar mb-3">
        <?php foreach (($typeOptions ?? []) as $typeKey => $typeLabel): ?>
            <?php
            $isActive = ($selectedType ?? 'all') === $typeKey;
            $queryParams = ['type' => $typeKey];
            if (!empty($keyword)) {
                $queryParams['q'] = (string)$keyword;
            }
            $url = BASE_URL . 'news?' . http_build_query($queryParams);
            ?>
            <a href="<?= htmlspecialchars($url) ?>" class="news-filter-chip <?= $isActive ? 'active' : '' ?>">
                <?= htmlspecialchars((string)$typeLabel) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="news-timeline">
        <?php if (!empty($timelineByDate)): ?>
            <?php $timelineIndex = 0; ?>
            <?php foreach ($timelineByDate as $dateKey => $items): ?>
                <div class="timeline-day-label"><?= htmlspecialchars((string)date('d/m/Y', strtotime((string)$dateKey))) ?></div>
                <div class="timeline-day-content">
                    <?php foreach ($items as $article): ?>
                        <?php
                        $isLeft = ($timelineIndex % 2 === 0);
                        $timelineIndex++;

                        $imageUrl = (string)($article['timeline_image'] ?? BASE_URL . 'public/images/about/about-6.png');
                        ?>
                        <div class="timeline-row timeline-reveal <?= $isLeft ? 'is-left' : 'is-right' ?>">
                            <div class="timeline-col timeline-text-col">
                                <article class="timeline-item">
                                    <div class="timeline-item-meta">
                                        <span class="news-type-badge"><?= htmlspecialchars((string)($article['news_type'] ?? 'Tin điện ảnh')) ?></span>
                                        <span class="news-time"><?= htmlspecialchars((string)($article['display_datetime'] ?? '')) ?></span>
                                    </div>
                                    <h3 class="timeline-item-title"><?= htmlspecialchars((string)($article['title'] ?? '')) ?></h3>
                                    <p class="timeline-item-excerpt">
                                        <?= htmlspecialchars(mb_substr(strip_tags((string)($article['content'] ?? ($article['summary'] ?? ''))), 0, 180, 'UTF-8')) ?>...
                                    </p>
                                    <?php if (!empty($article['is_movie_item'])): ?>
                                        <a class="timeline-item-link" href="<?= htmlspecialchars((string)($article['timeline_link'] ?? BASE_URL . 'product/index')) ?>">Xem phim và đặt vé</a>
                                    <?php else: ?>
                                        <a class="timeline-item-link" href="<?= BASE_URL ?>news/detail/<?= (int)$article['id'] ?>">Xem chi tiết</a>
                                    <?php endif; ?>
                                </article>
                            </div>

                            <div class="timeline-center-col">
                                <span class="timeline-dot"></span>
                            </div>

                            <div class="timeline-col timeline-media-col">
                                <figure class="timeline-media-card mb-0">
                                    <img src="<?= htmlspecialchars((string)$imageUrl) ?>" alt="<?= htmlspecialchars((string)($article['title'] ?? 'Timeline image')) ?>">
                                </figure>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bài viết phù hợp.</p>
        <?php endif; ?>
    </div>

    <?php
    $pager = $timelinePagination ?? ['current_page' => 1, 'total_pages' => 1, 'from_month' => '', 'to_month' => ''];
    $queryBase = [];
    if (!empty($keyword)) {
        $queryBase['q'] = (string)$keyword;
    }
    if (!empty($selectedType) && $selectedType !== 'all') {
        $queryBase['type'] = (string)$selectedType;
    }
    ?>

    <?php if ((int)($pager['total_pages'] ?? 1) > 1): ?>
        <div class="timeline-pagination mt-4">
            <p class="text-muted mb-2">
                Hiển thị mốc thời gian từ <strong><?= htmlspecialchars((string)($pager['from_month'] ?? '')) ?></strong>
                đến <strong><?= htmlspecialchars((string)($pager['to_month'] ?? '')) ?></strong>
                (4 tháng/trang)
            </p>
            <nav aria-label="Phân trang timeline tin tức">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= (int)$pager['total_pages']; $i++): ?>
                        <?php
                        $query = $queryBase;
                        $query['timeline_page'] = $i;
                        $link = BASE_URL . 'news?' . http_build_query($query);
                        ?>
                        <li class="page-item <?= $i === (int)$pager['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= htmlspecialchars($link) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</section>
