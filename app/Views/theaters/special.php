<section class="panel">
	<div class="mb-4">
		<h1 class="display-4">Rạp Đặc Biệt</h1>
		<p class="text-muted">Những rạp chiếu với công nghệ tiên tiến và trải nghiệm vượt trội</p>
	</div>

	<div class="row g-4">
		<?php foreach (($theaters ?? []) as $theater): ?>
			<div class="col-md-6 col-lg-4">
				<div class="card h-100 shadow-sm border-warning">
					<div class="card-header bg-warning text-dark">
						<small class="fw-bold">ĐẶC BIỆT</small>
					</div>
					<img src="<?= htmlspecialchars($theater['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($theater['name']) ?>" style="height: 250px; object-fit: cover;">
					<div class="card-body">
						<h5 class="card-title"><?= htmlspecialchars($theater['name']) ?></h5>
						<?php if (isset($theater['special_feature'])): ?>
							<p class="card-text mb-3">
								<span class="badge bg-warning text-dark"><?= htmlspecialchars($theater['special_feature']) ?></span>
							</p>
						<?php endif; ?>
						<p class="card-text">
							<small class="d-block"><strong>Địa chỉ:</strong> <?= htmlspecialchars($theater['location'] ?? 'Chưa cập nhật') ?></small>
							<small class="d-block"><strong>Số phòng chiếu:</strong> <?= (int)($theater['screens'] ?? 0) ?> phòng</small>
							<small class="d-block mb-2"><strong>Tiện nghi:</strong></small>
							<div class="mb-2">
								<?php foreach ($theater['amenities'] ?? [] as $amenity): ?>
									<span class="badge bg-warning text-dark me-1 mb-1"><?= htmlspecialchars($amenity) ?></span>
								<?php endforeach; ?>
							</div>
						</p>
					</div>
					<div class="card-footer bg-white border-top-0">
						<a href="<?= BASE_URL ?>theaters" class="btn btn-sm btn-warning w-100">Đặt Vé Ngay</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (empty($theaters)): ?>
		<div class="alert alert-warning text-center mt-4">
			<p>Hiện chưa có rạp đặc biệt nào. Vui lòng quay lại sau.</p>
		</div>
	<?php endif; ?>

	<div class="mt-5 text-center">
		<a href="<?= BASE_URL ?>theaters" class="btn btn-outline-warning">Quay lại trang chính</a>
		<a href="<?= BASE_URL ?>theaters/all" class="btn btn-outline-warning ms-2">Tất Cả Rạp</a>
	</div>
</section>
