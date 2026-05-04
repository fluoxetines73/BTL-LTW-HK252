<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Rạp 3D/4DX</h1>
		<p class="text-muted">Những rạp chiếu phim với công nghệ 3D và 4DX immersive</p>
	</div>

	<div class="row g-4">
		<?php foreach (($theaters ?? []) as $theater): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border-danger">
					<div class="card-header bg-danger text-white">
						<small class="fw-bold">3D/4DX</small>
					</div>
					<img src="<?= htmlspecialchars($theater['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($theater['name']) ?>" style="height: 250px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($theater['name']) ?></h5>
						<p class="card-text">
							<small class="d-block"><strong>Địa chỉ:</strong> <?= htmlspecialchars($theater['location'] ?? 'Chưa cập nhật') ?></small>
							<small class="d-block"><strong>Phòng chiếu 3D:</strong> <?= (int)($theater['screen_count_3d'] ?? 0) ?> phòng</small>
							<small class="d-block mb-2"><strong>Định dạng:</strong></small>
							<div class="mb-2">
								<?php foreach ($theater['amenities'] ?? [] as $amenity): ?>
									<span class="badge bg-danger me-1 mb-1"><?= htmlspecialchars($amenity) ?></span>
								<?php endforeach; ?>
							</div>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<a href="<?= BASE_URL ?>theaters" class="btn btn-sm btn-danger w-100">Đặt Vé Ngay</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($theaters)): ?>
		<div class="alert alert-warning text-center mt-4">
			<p>Hiện chưa có rạp 3D/4DX nào. Vui lòng quay lại sau.</p>
		</div>
	<?php endif; ?>

	<div class="mt-5 text-center">
		<a href="<?= BASE_URL ?>theaters" class="btn btn-outline-danger">Quay lại trang chính</a>
		<a href="<?= BASE_URL ?>theaters/all" class="btn btn-outline-danger ms-2">Tất Cả Rạp</a>
	</div>
</section>
