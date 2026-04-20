<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Phim Đang Chiếu</h1>
		<p class="text-muted">Những bộ phim đang được công chiếu tại CGV Cinema</p>
	</div>

	<div class="row g-4">
		<?php foreach (($movies ?? []) as $movie): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border-success">
					<div class="card-header bg-success text-white">
						<small>ĐANG CHIẾU</small>
					</div>
					<img src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>" style="height: 300px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
						<p class="card-text">
							<small class="d-block"><strong>Đạo diễn:</strong> <?= htmlspecialchars($movie['director'] ?? 'Chưa cập nhật') ?></small>
							<small class="d-block"><strong>Thể loại:</strong> <?= htmlspecialchars($movie['genre'] ?? 'N/A') ?></small>
							<small class="d-block"><strong>Thời lượng:</strong> <?= (int)($movie['duration'] ?? 0) ?> phút</small>
							<small class="d-block"><strong>Phân loại:</strong> <span class="badge bg-info"><?= htmlspecialchars($movie['rating'] ?? 'P') ?></span></small>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<a href="<?= BASE_URL ?>movies/current" class="btn btn-sm btn-success w-100">Đặt Vé Ngay</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($movies)): ?>
		<div class="alert alert-warning text-center mt-4">
			<p>Hiện chưa có phim nào đang chiếu. Vui lòng quay lại sau hoặc xem <a href="<?= BASE_URL ?>movies/coming">phim sắp chiếu</a>.</p>
		</div>
	<?php endif; ?>

	<div class="mt-5 text-center">
		<a href="<?= BASE_URL ?>movies" class="btn btn-outline-primary">Quay lại tất cả phim</a>
	</div>
</section>
