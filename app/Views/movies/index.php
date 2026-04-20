<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Danh sach phim</h1>
		<p class="text-muted">Khám phá bộ sưu tập phim đa dạng của CGV Cinema</p>
	</div>

	<div class="row g-4">
		<?php foreach (($movies ?? []) as $movie): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm">
					<img src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>" style="height: 300px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
						<p class="card-text text-muted">
							<small><strong>Đạo diễn:</strong> <?= htmlspecialchars($movie['director'] ?? 'Chưa cập nhật') ?></small><br>
							<small><strong>Thể loại:</strong> <?= htmlspecialchars($movie['genre'] ?? 'N/A') ?></small><br>
							<small><strong>Thời lượng:</strong> <?= (int)($movie['duration'] ?? 0) ?> phút</small><br>
							<small><strong>Phân loại:</strong> <span class="badge bg-info"><?= htmlspecialchars($movie['rating'] ?? 'P') ?></span></small>
						</p>
						<p class="card-text">
							<small class="text-success"><strong>Công chiếu:</strong> <?= htmlspecialchars($movie['release_date'] ?? 'TBA') ?></small>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<a href="<?= BASE_URL ?>movies/current" class="btn btn-sm btn-primary w-100">Xem chi tiết & Đặt vé</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($movies)): ?>
		<div class="alert alert-info text-center mt-4">
			<p>Hiện chưa có phim nào. Vui lòng quay lại sau.</p>
		</div>
	<?php endif; ?>
</section>
