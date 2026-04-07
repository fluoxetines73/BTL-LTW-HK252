<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Phim Sắp Chiếu</h1>
		<p class="text-muted">Những bộ phim sẽ ra mắt sớm tại CGV Cinema</p>
	</div>

	<div class="row g-4">
		<?php foreach (($movies ?? []) as $movie): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border-warning">
					<div class="card-header bg-warning text-dark">
						<small><strong>SẮP CHIẾU</strong></small>
					</div>
					<img src="<?= htmlspecialchars($movie['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>" style="height: 300px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
						<p class="card-text">
							<small class="d-block"><strong>Đạo diễn:</strong> <?= htmlspecialchars($movie['director'] ?? 'Chưa cập nhật') ?></small>
							<small class="d-block"><strong>Thể loại:</strong> <?= htmlspecialchars($movie['genre'] ?? 'N/A') ?></small>
							<small class="d-block"><strong>Thời lượng:</strong> <?= (int)($movie['duration'] ?? 0) ?> phút</small>
							<small class="d-block"><strong>Phân loại:</strong> <span class="badge bg-info"><?= htmlspecialchars($movie['rating'] ?? 'P') ?></span></small>
						</p>
						<p class="card-text">
							<small class="text-danger"><strong>Công chiếu:</strong> <?= htmlspecialchars($movie['release_date'] ?? 'TBA') ?></small>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<button class="btn btn-sm btn-outline-warning w-100" disabled>Sắp Mở Bán Vé</button>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($movies)): ?>
		<div class="alert alert-info text-center mt-4">
			<p>Hiện chưa có phim nào sắp chiếu. Vui lòng quay lại sau.</p>
		</div>
	<?php endif; ?>

	<div class="mt-5 text-center">
		<a href="<?= BASE_URL ?>movies/current" class="btn btn-outline-success me-2">Xem phim đang chiếu</a>
		<a href="<?= BASE_URL ?>movies" class="btn btn-outline-primary">Quay lại tất cả phim</a>
	</div>
</section>
