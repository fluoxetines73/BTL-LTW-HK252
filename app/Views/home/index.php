<!-- Featured Movie Hero Section -->
<section class="hero-section mb-5">
	<?php if (!empty($featured_movie)): ?>
		<div class="container-fluid">
			<div class="row g-0 align-items-center hero-wrapper">
				<!-- Hero Image -->
				<div class="col-12 col-md-6 col-lg-7 hero-image-wrapper">
					<?php if (!empty($featured_movie['banner'])): ?>
						<img 
							src="<?= BASE_URL . htmlspecialchars($featured_movie['banner']) ?>" 
							alt="<?= htmlspecialchars($featured_movie['title']) ?>" 
							class="hero-image img-fluid w-100" 
							style="height: 400px; object-fit: cover; display: block;">
					<?php elseif (!empty($featured_movie['poster'])): ?>
						<img 
							src="<?= BASE_URL . htmlspecialchars($featured_movie['poster']) ?>" 
							alt="<?= htmlspecialchars($featured_movie['title']) ?>" 
							class="hero-image img-fluid w-100" 
							style="height: 400px; object-fit: cover; display: block;">
					<?php else: ?>
						<div class="hero-image bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">
							<span>Chưa có hình ảnh</span>
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
								href="<?= BASE_URL ?>movies/<?= (int)$featured_movie['id'] ?>" 
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

<!-- Recommended Movies Carousel Section -->
<section class="recommendations-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<!-- Section Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="recommendations-title h3 mb-0">
					Phim Được Đề Xuất
				</h2>
				<p class="text-muted small mt-1">
					Tìm kiếm những bộ phim hấp dẫn từ danh sách được đề xuất của chúng tôi
				</p>
			</div>
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
													src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" 
													alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown') ?>" 
													class="movie-card-image img-fluid w-100" 
													loading="lazy"
													style="height: 300px; object-fit: cover; display: block;">
											<?php else: ?>
												<div class="movie-card-image bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
													<span class="small">Chưa có hình ảnh</span>
												</div>
											<?php endif; ?>
										</div>

										<!-- Movie Info -->
										<div class="movie-card-content p-3">
											<!-- Title -->
											<h5 class="movie-card-title mb-2">
												<a 
													href="<?= BASE_URL ?>movies/<?= (int)($movie['id'] ?? 0) ?>" 
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
													<span class="movie-rating badge bg-secondary text-white">
														<i class="fas fa-star me-1"></i>N/A
													</span>
												<?php endif; ?>

												<?php if (!empty($movie['release_date'])): ?>
													<small class="text-muted">
														<?= date('m/Y', strtotime($movie['release_date'])) ?>
													</small>
												<?php endif; ?>
											</div>

											<!-- CTA Button -->
											<div class="movie-card-cta mt-3">
												<a 
													href="<?= BASE_URL ?>movies/<?= (int)($movie['id'] ?? 0) ?>" 
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
		<?php else: ?>
			<!-- Fallback when no recommendations -->
			<div class="row">
				<div class="col-12">
					<div class="alert alert-info" role="alert">
						<h5 class="alert-heading">Chưa có phim được đề xuất</h5>
						<p class="mb-0">Vui lòng quay lại sau để xem các phim được đề xuất.</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Ads Banner Carousel Section -->
<section class="ads-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<!-- Section Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="ads-title h3 mb-0">
					Khuyến Mãi Đặc Biệt
				</h2>
				<p class="text-muted small mt-1">
					Khám phá những ưu đãi hấp dẫn dành riêng cho bạn
				</p>
			</div>
		</div>

		<!-- Carousel Container -->
		<?php if (!empty($ads) && is_array($ads) && count($ads) > 0): ?>
			<div class="row">
				<div class="col-12">
					<!-- Swiper Carousel -->
					<div class="swiper ads-carousel" data-swiper-id="ads">
						<!-- Slides wrapper -->
						<div class="swiper-wrapper">
							<?php foreach ($ads as $ad): ?>
								<div class="swiper-slide">
									<div class="ad-card h-100">
										<!-- Ad Banner Image -->
										<div class="ad-card-image-wrapper position-relative overflow-hidden">
											<?php if (!empty($ad['image'])): ?>
												<img 
													src="<?= htmlspecialchars($ad['image']) ?>" 
													alt="<?= htmlspecialchars($ad['title'] ?? 'Ad Banner') ?>" 
													class="ad-card-image img-fluid w-100" 
													loading="lazy"
													style="height: 280px; object-fit: cover; display: block;">
											<?php else: ?>
												<div class="ad-card-image bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 280px;">
													<span class="small">Chưa có hình ảnh quảng cáo</span>
												</div>
											<?php endif; ?>
										</div>

										<!-- Ad Info -->
										<div class="ad-card-content p-4">
											<!-- Title -->
											<h5 class="ad-card-title mb-2">
												<?= htmlspecialchars($ad['title'] ?? 'Ad Title') ?>
											</h5>

											<!-- Description -->
											<?php if (!empty($ad['description'])): ?>
												<p class="ad-card-description mb-3 small text-muted">
													<?= htmlspecialchars(strlen($ad['description']) > 80 ? substr($ad['description'], 0, 80) . '...' : $ad['description']) ?>
												</p>
											<?php endif; ?>

										<!-- CTA Button -->
										<div class="ad-card-cta">
											<?php if (!empty($ad['link'])): ?>
												<a 
													href="<?= $ad['link'] ?>" 
													class="btn btn-sm btn-primary w-100">
														<i class="fas fa-arrow-right me-1"></i>Xem chi tiết
													</a>
												<?php else: ?>
													<button class="btn btn-sm btn-primary w-100" disabled>
														<i class="fas fa-arrow-right me-1"></i>Xem chi tiết
													</button>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<!-- Pagination dots -->
						<div class="swiper-pagination ads-pagination"></div>

						<!-- Navigation buttons -->
						<div class="swiper-button-prev ads-button-prev"></div>
						<div class="swiper-button-next ads-button-next"></div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<!-- Fallback when no ads -->
			<div class="row">
				<div class="col-12">
					<div class="alert alert-info" role="alert">
						<h5 class="alert-heading">Không có quảng cáo</h5>
						<p class="mb-0">Vui lòng quay lại sau để xem các khuyến mãi đặc biệt.</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Genre Filter Bar Section -->
<section class="genre-filter-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<!-- Section Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="genre-title h3 mb-0">
					Browse by Genre
				</h2>
				<p class="text-muted small mt-1">
					Explore movies organized by your favorite genres
				</p>
			</div>
		</div>

		<!-- Genre Buttons/Chips -->
		<?php if (!empty($genres) && is_array($genres) && count($genres) > 0): ?>
			<div class="row">
				<div class="col-12">
					<div class="genre-chips-wrapper d-flex flex-wrap gap-3">
						<?php foreach ($genres as $genre): ?>
							<a 
								href="<?= BASE_URL ?>movies/current?genre=<?= urlencode($genre['slug'] ?? '') ?>" 
								class="genre-chip btn btn-outline-secondary"
								title="<?= htmlspecialchars($genre['name'] ?? 'Unknown Genre') ?>">
								<span class="genre-name">
									<?= htmlspecialchars($genre['name'] ?? 'Unknown') ?>
								</span>
								<span class="genre-count text-muted ms-2">
									(<?= (int)($genre['movie_count'] ?? 0) ?> <?= ((int)($genre['movie_count'] ?? 0) === 1) ? 'movie' : 'movies' ?>)
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php else: ?>
			<!-- Fallback when no genres -->
			<div class="row">
				<div class="col-12">
					<div class="alert alert-info" role="alert">
						<h5 class="alert-heading">No genres available</h5>
						<p class="mb-0">Please check back later to browse movies by genre.</p>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- Coming Soon Carousel Section -->
<section class="coming-soon-section py-5 mb-5">
	<div class="container-fluid px-4 px-md-5">
		<!-- Section Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="coming-soon-title h3 mb-0">
					Coming Soon
				</h2>
				<p class="text-muted small mt-1">
					Get excited for these upcoming releases
				</p>
			</div>
		</div>

		<!-- Carousel Container -->
		<?php if (!empty($coming_soon) && is_array($coming_soon) && count($coming_soon) > 0): ?>
			<div class="row">
				<div class="col-12">
					<!-- Swiper Carousel -->
					<div class="swiper coming-soon-carousel" data-swiper-id="coming-soon">
						<!-- Slides wrapper -->
						<div class="swiper-wrapper">
							<?php foreach ($coming_soon as $movie): ?>
								<div class="swiper-slide">
									<div class="movie-card h-100">
										<!-- Movie Poster -->
										<div class="movie-card-image-wrapper position-relative overflow-hidden">
											<?php if (!empty($movie['poster'])): ?>
												<img 
													src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" 
													alt="<?= htmlspecialchars($movie['title'] ?? 'Unknown') ?>" 
													class="movie-card-image img-fluid w-100" 
													loading="lazy"
													style="height: 300px; object-fit: cover; display: block;">
											<?php else: ?>
												<div class="movie-card-image bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
													<span class="small">Chưa có hình ảnh</span>
												</div>
											<?php endif; ?>
										</div>

										<!-- Movie Info -->
										<div class="movie-card-content p-3">
											<!-- Title -->
											<h5 class="movie-card-title mb-2">
												<a 
													href="<?= BASE_URL ?>movies/<?= (int)($movie['id'] ?? 0) ?>" 
													class="text-decoration-none"
													title="<?= htmlspecialchars($movie['title'] ?? 'Unknown') ?>">
													<?= htmlspecialchars(strlen($movie['title'] ?? '') > 25 ? substr($movie['title'], 0, 25) . '...' : ($movie['title'] ?? 'Unknown')) ?>
												</a>
											</h5>

											<!-- Release Date -->
											<div class="movie-card-meta d-flex justify-content-between align-items-center">
												<?php if (!empty($movie['release_date'])): ?>
													<?php 
														$releaseDate = new DateTime($movie['release_date']);
														$today = new DateTime();
														$interval = $today->diff($releaseDate);
														
														if ($interval->days === 0) {
															$dateDisplay = 'Today';
														} elseif ($interval->days === 1) {
															$dateDisplay = 'Tomorrow';
														} elseif ($interval->days <= 30) {
															$dateDisplay = 'In ' . $interval->days . ' day' . ($interval->days > 1 ? 's' : '');
														} else {
															$dateDisplay = $releaseDate->format('d M Y');
														}
													?>
													<small class="text-muted">
														<?= htmlspecialchars($dateDisplay) ?>
													</small>
												<?php endif; ?>
											</div>

											<!-- CTA Button -->
											<div class="movie-card-cta mt-3">
												<a 
													href="<?= BASE_URL ?>movies/<?= (int)($movie['id'] ?? 0) ?>" 
													class="btn btn-sm btn-outline-primary w-100">
													<i class="fas fa-ticket-alt me-1"></i>Details
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<!-- Pagination dots -->
						<div class="swiper-pagination coming-soon-pagination"></div>

						<!-- Navigation buttons -->
						<div class="swiper-button-prev coming-soon-button-prev"></div>
						<div class="swiper-button-next coming-soon-button-next"></div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<!-- Fallback when no coming soon movies -->
			<div class="row">
				<div class="col-12">
					<div class="alert alert-info" role="alert">
						<h5 class="alert-heading">No upcoming releases</h5>
						<p class="mb-0">Please check back later to see upcoming movies.</p>
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
									<div class="news-card-image bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
										<span class="small">Chưa có hình ảnh</span>
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

<!-- Additional Sections Placeholder -->
<section class="panel">
	<h2>Mục tiêu tuần này</h2>
	<ul>
		<?php foreach (($highlights ?? []) as $item): ?>
			<li><?= htmlspecialchars($item) ?></li>
		<?php endforeach; ?>
	</ul>
</section>
