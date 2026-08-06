<!-- Featured Movie Hero Section -->
<section class="hero-section mb-5">
	<?php if (!empty($featured_movie)): ?>
		<div class="container-fluid">
			<div class="row g-0 align-items-center hero-wrapper">
				<!-- Hero Image -->
				<div class="col-12 col-md-6 col-lg-7 hero-image-wrapper">
					<?php if (!empty($featured_movie['banner'])): ?>
						<img 
							src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($featured_movie['banner']) ?>" 
							alt="<?= htmlspecialchars($featured_movie['title']) ?>" 
							class="hero-image img-fluid w-100" 
							style="height: 400px; object-fit: cover; display: block;">
					<?php elseif (!empty($featured_movie['poster'])): ?>
						<img 
							src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($featured_movie['poster']) ?>" 
							alt="<?= htmlspecialchars($featured_movie['title']) ?>" 
							class="hero-image img-fluid w-100" 
							style="height: 400px; object-fit: cover; display: block;">
					<?php else: ?>
						<div class="hero-image bg-gradient d-flex align-items-center justify-content-center" style="height: 400px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
							<img src="<?= BASE_URL ?>public/images/logo/cgvlogo.svg" alt="CGV Cinema" style="max-width: 200px; opacity: 0.3;">
						</div>
					<?php endif; ?>
				</div>

				<!-- Hero Content -->
				<div class="col-12 col-md-6 col-lg-5 hero-content-wrapper p-4 p-md-5">
					<article class="hero-content">
						<!-- Title -->
						<h1 class="hero-title mb-3">
							<?= htmlspecialchars($featured_movie['title'] ?? 'Phim không có tiêu đề') ?>
						</h1>

						<!-- Meta Info: Age Rating & Release Date -->
						<div class="hero-meta mb-3">
							<?php if (!empty($featured_movie['age_rating'])): ?>
								<span class="hero-rating badge me-2">
									<?= htmlspecialchars($featured_movie['age_rating']) ?>
								</span>
							<?php endif; ?>
							<?php if (!empty($featured_movie['release_date'])): ?>
								<span class="hero-date text-muted">
									<i class="fas fa-calendar-alt me-1"></i>
									<?= date('d/m/Y', strtotime($featured_movie['release_date'])) ?>
								</span>
							<?php endif; ?>
						</div>

						<!-- Description -->
						<?php if (!empty($featured_movie['description'])): ?>
							<p class="hero-description mb-4">
								<?= htmlspecialchars(substr($featured_movie['description'], 0, 200)) ?>
								<?= strlen($featured_movie['description']) > 200 ? '...' : '' ?>
							</p>
						<?php endif; ?>

						<!-- Additional Info -->
						<div class="hero-details mb-4">
							<?php if (!empty($featured_movie['director'])): ?>
								<p class="hero-detail-item mb-2">
									<strong>Đạo diễn:</strong> <?= htmlspecialchars($featured_movie['director']) ?>
								</p>
							<?php endif; ?>
							<?php if (!empty($featured_movie['duration_min'])): ?>
								<p class="hero-detail-item mb-2">
									<strong>Thời lượng:</strong> <?= (int)$featured_movie['duration_min'] ?> phút
								</p>
							<?php endif; ?>
						</div>

						<!-- CTA Button -->
						<div class="hero-cta">
							<a 
								href="<?= BASE_URL ?>product/detail/<?= (int)$featured_movie['id'] ?>" 
								class="hero-cta-button btn btn-primary btn-lg w-100"
								role="button">
								<i class="fas fa-ticket-alt me-2"></i>Đặt vé ngay
							</a>
						</div>
					</article>
				</div>
			</div>
		</div>
	<?php else: ?>
		<!-- Fallback when no featured movie -->
		<div class="container-fluid">
			<div class="alert alert-info mt-4 mb-4" role="alert">
				<h4 class="alert-heading">Chưa có phim nổi bật</h4>
				<p>Vui lòng quay lại sau để xem phim được đề xuất.</p>
				<hr>
				<a href="<?= BASE_URL ?>movies" class="btn btn-primary btn-sm">Xem tất cả phim</a>
			</div>
		</div>
	<?php endif; ?>
</section>

<!-- Quick Search Band -->
<section class="homepage-search-band mb-5">
	<div class="container-fluid px-4 px-md-5">
		<div class="homepage-search-card">
			<div class="homepage-search-copy">
				<span class="homepage-search-kicker">Tìm kiếm thông tin</span>
				<h2 class="homepage-search-title">Tìm phim, tin tức và ưu đãi bạn quan tâm</h2>
				<p class="homepage-search-text mb-0">Mở nhanh bộ tìm kiếm để lọc theo chủ đề bạn cần.</p>
			</div>
			<button type="button" class="homepage-search-btn" data-bs-toggle="modal" data-bs-target="#homeSearchModal">
				<i class="fas fa-search me-2"></i>Tìm kiếm thông tin
			</button>
		</div>

		<!-- Carousel Container -->
		<?php if (!empty($recommendations) && is_array($recommendations) && count($recommendations) > 0): ?>
    <div class="row">
        <div class="col-12">
            <!-- Swiper Carousel -->
            <div class="swiper recommendations-carousel" data-swiper-id="recommendations">
                <!-- Slides wrapper -->
                <div class="swiper-wrapper">
                    <?php foreach ($recommendations as $movie): ?>
                        <div class="swiper-slide">
                            <div class="movie-card h-100">
                                <!-- Movie Poster -->
                                <div class="movie-card-image-wrapper position-relative overflow-hidden">
                                    <?php if (!empty($movie['poster'])): ?>
                                        <img
                                            src="<?= BASE_URL ?>public/uploads/movies/<?= htmlspecialchars($movie['poster']) ?>"
                                            alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown') ?>"
                                            class="movie-card-image img-fluid w-100"
                                            loading="lazy"
                                            style="height: 300px; object-fit: cover; display: block;">
                                    <?php else: ?>
                                        <div class="movie-card-image bg-gradient d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                                            <img src="<?= BASE_URL ?>public/images/logo/cgvlogo.svg" alt="CGV Cinema" style="max-width: 100px; opacity: 0.3;">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Movie Info -->
                                <div class="movie-card-content p-3">
                                    <!-- CHỖ SỬA 1: Title Link -->
                                    <h5 class="movie-card-title mb-2">
                                        <a
                                            href="<?= BASE_URL ?>product/detail/<?= (int)($movie['id'] ?? 0) ?>"
                                            class="text-decoration-none"
                                            title="<?= htmlspecialchars($movie['title'] ?? 'Unknown') ?>">
                                            <?= htmlspecialchars(strlen($movie['title'] ?? '') > 25 ? substr($movie['title'], 0, 25) . '...' : ($movie['title'] ?? 'Unknown')) ?>
                                        </a>
                                    </h5>

                                    <!-- Rating & Release Date -->
                                    <div class="movie-card-meta d-flex justify-content-between align-items-center">
                                        <?php if (!empty($movie['rating'])): ?>
                                            <span class="movie-rating badge bg-warning text-dark">
                                                <i class="fas fa-star me-1"></i><?= htmlspecialchars($movie['rating']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="movie-rating badge bg-secondary">
                                                <i class="fas fa-star me-1"></i>N/A
                                            </span>
                                        <?php endif; ?>

                                        <?php if (!empty($movie['release_date'])): ?>
                                            <small class="text-muted">
                                                <?= date('m/Y', strtotime($movie['release_date'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>

                                    <!-- CHỖ SỬA 2: CTA Button Link -->
                                    <div class="movie-card-cta mt-3">
                                        <a 
                                            href="<?= BASE_URL ?>product/detail/<?= (int)($movie['id'] ?? 0) ?>" 
                                            class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-ticket-alt me-1"></i>Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination dots -->
                <div class="swiper-pagination recommendations-pagination"></div>

                <!-- Navigation buttons -->
                <div class="swiper-button-prev recommendations-button-prev"></div>
                <div class="swiper-button-next recommendations-button-next"></div>
            </div>
        </div>
    </div>
<?php endif; ?>
	</div>
</section>

<div class="modal fade homepage-search-modal" id="homeSearchModal" tabindex="-1" aria-labelledby="homeSearchModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content homepage-search-modal-content">
			<div class="modal-header border-0 pb-0">
				<div>
					<p class="homepage-search-modal-kicker mb-1">Tìm kiếm thông tin</p>
					<h3 class="modal-title fs-4 mb-0" id="homeSearchModalLabel">Tra cứu nhanh trong hệ thống CGV</h3>
				</div>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-3">
				<form class="homepage-search-form homepage-search-form--modal" action="<?= BASE_URL ?>news" method="get" role="search">
					<input type="hidden" name="section" id="home-search-section" value="">
					<label class="visually-hidden" for="homepage-search-input">Tìm kiếm tin tức</label>
					<div class="homepage-search-input-wrap">
						<i class="fas fa-magnifying-glass homepage-search-icon"></i>
						<input id="homepage-search-input" type="search" name="q" class="homepage-search-input" placeholder="Nhập từ khóa: phim, khuyến mãi, sự kiện...">
						<button type="submit" class="homepage-search-btn-inline">
							<i class="fas fa-search"></i>
						</button>
					</div>
					<div class="homepage-search-chip-row" role="group" aria-label="Lọc nhanh theo chủ đề">
						<button type="button" class="homepage-search-chip is-active" data-section="">Tất cả</button>
						<button type="button" class="homepage-search-chip" data-section="tin-tuc">Tin tức</button>
						<button type="button" class="homepage-search-chip" data-section="khuyen-mai">Khuyến mãi</button>
						<button type="button" class="homepage-search-chip" data-section="phim-hay-thang">Phim hay tháng</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Unified Homepage Carousel (Recommendations + Ads + Coming Soon) -->
<section class="homepage-carousel-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="homepage-carousel-title h3 mb-0">Highlights</h2>
				<p class="text-muted small mt-1">Phim & Khuyến mãi nổi bật</p>
			</div>
		</div>

		<?php
		// Combine slides from recommendations, ads, coming soon into one array
		$slides = [];
		if (!empty($recommendations) && is_array($recommendations)) {
			foreach ($recommendations as $m) { $slides[] = ['type' => 'movie', 'data' => $m]; }
		}
		if (!empty($ads) && is_array($ads)) {
			foreach ($ads as $a) { $slides[] = ['type' => 'ad', 'data' => $a]; }
		}
		if (!empty($coming_soon) && is_array($coming_soon)) {
			foreach ($coming_soon as $c) { $slides[] = ['type' => 'movie', 'data' => $c]; }
		}
		?>

		<?php if (!empty($slides)): ?>
			<div class="row">
				<div class="col-12">
					<div class="swiper homepage-carousel" data-swiper-id="homepage">
						<div class="swiper-wrapper">
							<?php foreach ($slides as $slide): ?>
								<div class="swiper-slide">
									<?php if ($slide['type'] === 'ad'):
										$ad = $slide['data']; ?>
										<div class="ad-card h-100">
											<div class="ad-card-image-wrapper position-relative overflow-hidden">
												<?php if (!empty($ad['image'])): ?>
													<img src="<?= htmlspecialchars($ad['image']) ?>" alt="<?= htmlspecialchars($ad['title'] ?? 'Ad') ?>" class="ad-card-image img-fluid w-100" loading="lazy" style="height:280px; object-fit:cover; display:block;">
												<?php else: ?>
													<div class="ad-card-image bg-gradient d-flex align-items-center justify-content-center" style="height:280px; background: linear-gradient(135deg, #e71a0f 0%, #ff6b6b 100%);">
														<i class="fas fa-percentage" style="font-size:48px; opacity:0.4; color:white;"></i>
													</div>
												<?php endif; ?>
											</div>
											<div class="ad-card-content p-3">
												<h5 class="ad-card-title mb-2"><?= htmlspecialchars($ad['title'] ?? '') ?></h5>
												<?php if (!empty($ad['description'])): ?><p class="small text-muted"><?= htmlspecialchars(strlen($ad['description'])>80?substr($ad['description'],0,80).'...':$ad['description']) ?></p><?php endif; ?>
												<?php if (!empty($ad['link'])): ?>
													<a href="<?= $ad['link'] ?>" class="btn btn-sm btn-primary mt-2">Xem chi tiết</a>
												<?php endif; ?>
											</div>
										</div>
									<?php else:
										$m = $slide['data']; ?>
										<div class="movie-card h-100">
											<div class="movie-card-image-wrapper position-relative overflow-hidden">
												<?php if (!empty($m['poster']) || !empty($m['banner'])): ?>
													<?php $img = !empty($m['poster']) ? BASE_URL . 'public/uploads/movies/' . htmlspecialchars($m['poster']) : (BASE_URL . 'public/uploads/movies/' . htmlspecialchars($m['banner'])); ?>
													<img src="<?= $img ?>" alt="<?= htmlspecialchars($m['title'] ?? '') ?>" class="movie-card-image img-fluid w-100" loading="lazy" style="height:300px; object-fit:cover; display:block;">
												<?php else: ?>
													<div class="movie-card-image bg-gradient d-flex align-items-center justify-content-center" style="height:300px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
														<img src="<?= BASE_URL ?>public/images/logo/cgvlogo.svg" alt="CGV" style="max-width:100px; opacity:0.3;">
													</div>
												<?php endif; ?>
											</div>
											<div class="movie-card-content p-3">
												<h5 class="movie-card-title mb-2"><a href="<?= BASE_URL ?>product/detail/<?= (int)($m['id'] ?? 0) ?>" class="text-decoration-none"><?= htmlspecialchars(strlen($m['title'] ?? '')>25?substr($m['title'],0,25).'...':($m['title']??'')) ?></a></h5>
												<div class="movie-card-meta d-flex justify-content-between align-items-center">
													<?php if (!empty($m['rating'])): ?><span class="movie-rating badge bg-warning text-dark"><i class="fas fa-star me-1"></i><?= htmlspecialchars($m['rating']) ?></span><?php endif; ?>
													<?php if (!empty($m['release_date'])): ?><small class="text-muted"><?= date('m/Y', strtotime($m['release_date'])) ?></small><?php endif; ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>

						<!-- Pagination & Nav for unified carousel -->
						<div class="swiper-pagination homepage-pagination"></div>
						<div class="swiper-button-prev homepage-button-prev"></div>
						<div class="swiper-button-next homepage-button-next"></div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Newsletter Signup Section -->
<section class="newsletter-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<div class="row">
			<div class="col-12">
				<div class="newsletter-container p-5 rounded">
					<!-- Heading -->
					<h2 class="newsletter-title h2 mb-3">
						Subscribe to Our Newsletter
					</h2>

					<!-- Description -->
					<p class="newsletter-description text-muted mb-4">
						Stay updated with the latest movie releases, exclusive offers, and special promotions delivered directly to your inbox.
					</p>

					<!-- Newsletter Form -->
					<form id="newsletter-form" class="newsletter-form" novalidate>
						<div class="row g-2">
							<!-- Email Input -->
							<div class="col-12 col-md-8">
								<input 
									type="email" 
									name="email" 
									id="newsletter-email"
									class="form-control form-control-lg" 
									placeholder="Enter your email address"
									required>
								<small class="form-text text-muted d-block mt-2">
									We respect your privacy. Unsubscribe at any time.
								</small>
							</div>

							<!-- Submit Button -->
							<div class="col-12 col-md-4">
								<button 
									type="submit" 
									class="btn btn-primary btn-lg w-100 h-100"
									id="newsletter-submit">
									<i class="fas fa-paper-plane me-2"></i>Subscribe
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- News Preview Grid Section -->
<section class="news-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<!-- Section Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="news-title h3 mb-0">
					Latest News & Updates
				</h2>
				<p class="text-muted small mt-1">
					Stay informed with the latest news from CGV Cinema
				</p>
			</div>
		</div>

		<!-- News Grid -->
		<?php if (!empty($news) && is_array($news) && count($news) > 0): ?>
			<div class="row g-4">
				<?php foreach ($news as $item): ?>
					<div class="col-12 col-md-6 col-lg-3">
						<div class="news-card h-100">
							<!-- News Image -->
							<div class="news-card-image-wrapper position-relative overflow-hidden">
								<?php if (!empty($item['image'])): ?>
									<img 
										src="<?= htmlspecialchars($item['image']) ?>" 
										alt="<?= htmlspecialchars($item['title'] ?? 'News') ?>" 
										class="news-card-image img-fluid w-100" 
										loading="lazy"
										style="height: 200px; object-fit: cover; display: block;">
							<?php else: ?>
								<div class="news-card-image bg-gradient d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
									<i class="fas fa-newspaper" style="font-size: 36px; opacity: 0.4; color: white;"></i>
								</div>
							<?php endif; ?>
						</div>

						<!-- News Info -->
							<div class="news-card-content p-3">
								<!-- Category Badge -->
								<?php if (!empty($item['category'])): ?>
									<span class="badge text-white mb-2" style="background-color: var(--cgv-red);">
										<?= htmlspecialchars($item['category']) ?>
									</span>
								<?php endif; ?>

							<!-- Title -->
							<h5 class="news-card-title mb-2">
								<a 
									href="<?= BASE_URL ?>news/<?= urlencode($item['slug'] ?? '') ?>" 
									class="text-decoration-none"
									title="<?= htmlspecialchars($item['title'] ?? 'News') ?>">
										<?= htmlspecialchars(strlen($item['title'] ?? '') > 60 ? substr($item['title'], 0, 60) . '...' : ($item['title'] ?? 'News')) ?>
									</a>
								</h5>

								<!-- Excerpt -->
								<p class="news-card-excerpt text-muted small mb-3">
									<?= htmlspecialchars(strlen($item['content'] ?? '') > 100 ? substr($item['content'], 0, 100) . '...' : ($item['content'] ?? '')) ?>
								</p>

								<!-- Published Date -->
								<div class="news-card-meta">
									<small class="text-muted">
										<i class="fas fa-calendar-alt me-1"></i>
										<?php if (!empty($item['published_at'])): ?>
											<?= date('d/m/Y', strtotime($item['published_at'])) ?>
										<?php else: ?>
											N/A
										<?php endif; ?>
									</small>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- View All News Button -->
			<div class="row mt-5">
				<div class="col-12 text-center">
					<a 
						href="<?= BASE_URL ?>news" 
						class="btn btn-primary btn-lg">
						<i class="fas fa-newspaper me-2"></i>View All News
					</a>
				</div>
			</div>
		<?php else: ?>
			<!-- Fallback when no news -->
			<div class="row">
				<div class="col-12">
					<div class="alert alert-info" role="alert">
						<h5 class="alert-heading">Không có tin tức</h5>
						<p class="mb-0">Vui lòng quay lại sau để xem các tin tức mới nhất.</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>


