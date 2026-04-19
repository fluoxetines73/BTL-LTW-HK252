<!-- Featured Movie Hero Section -->
<section class="hero-section mb-5">
	<?php if (!empty($featured_movie)): ?>
		<div class="container-fluid">
			<div class="row g-0 align-items-center hero-wrapper">
				<!-- Hero Image -->
				<div class="col-12 col-md-6 col-lg-7 hero-image-wrapper">
					<?php if (!empty($featured_movie['banner'])): ?>
						<img 
							src="<?= htmlspecialchars($featured_movie['banner']) ?>" 
							alt="<?= htmlspecialchars($featured_movie['title']) ?>" 
							class="hero-image img-fluid w-100" 
							style="height: 400px; object-fit: cover; display: block;">
					<?php elseif (!empty($featured_movie['poster'])): ?>
						<img 
							src="<?= htmlspecialchars($featured_movie['poster']) ?>" 
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

<!-- Additional Sections Placeholder -->
<section class="panel">
	<h2>Mục tiêu tuần này</h2>
	<ul>
		<?php foreach (($highlights ?? []) as $item): ?>
			<li><?= htmlspecialchars($item) ?></li>
		<?php endforeach; ?>
	</ul>
</section>
