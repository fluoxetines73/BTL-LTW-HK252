<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Danh Sách Rạp CGV</h1>
		<p class="text-muted">Tìm rạp chiếu phim gần bạn nhất</p>
	</div>

	<div class="row g-4">
		<?php foreach (($theaters ?? []) as $theater): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border-primary">
					<img src="<?= htmlspecialchars($theater['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($theater['name']) ?>" style="height: 250px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($theater['name']) ?></h5>
						<p class="card-text">
							<small class="d-block"><strong>Địa chỉ:</strong> <?= htmlspecialchars($theater['location'] ?? 'Chưa cập nhật') ?></small>
							<small class="d-block"><strong>Số phòng chiếu:</strong> <?= (int)($theater['screens'] ?? 0) ?> phòng</small>
							<small class="d-block"><strong>Tiện nghi:</strong> <br>
								<?php foreach ($theater['amenities'] ?? [] as $amenity): ?>
									<span class="badge bg-info"><?= htmlspecialchars($amenity) ?></span>
								<?php endforeach; ?>
							</small>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<a href="<?= BASE_URL ?>theaters/all" class="btn btn-sm btn-primary w-100">Xem Chi Tiết</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($theaters)): ?>
		<div class="alert alert-warning text-center mt-4">
			<p>Hiện chưa có rạp chiếu nào. Vui lòng quay lại sau.</p>
		</div>
	<?php endif; ?>

	<div class="mt-5 text-center">
		<a href="<?= BASE_URL ?>theaters/all" class="btn btn-primary me-2">Tất Cả Rạp</a>
		<a href="<?= BASE_URL ?>theaters/special" class="btn btn-outline-primary me-2">Rạp Đặc Biệt</a>
		<a href="<?= BASE_URL ?>theaters/threeD" class="btn btn-outline-primary">3D/4DX</a>
	</div>
</section>
