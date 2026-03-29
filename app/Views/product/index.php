<section class="panel">
	<h1>Danh sach san pham</h1>
	<div class="cards">
		<?php foreach (($products ?? []) as $product): ?>
			<article class="card">
				<h3><?= htmlspecialchars($product['name']) ?></h3>
				<p><?= htmlspecialchars($product['description'] ?? 'Dang cap nhat mo ta.') ?></p>
				<p class="price"><?= number_format((float)($product['price'] ?? 0), 0, ',', '.') ?> VND</p>
				<a href="<?= BASE_URL ?>product/detail/<?= (int)$product['id'] ?>">Xem chi tiet</a>
			</article>
		<?php endforeach; ?>
	</div>
</section>
