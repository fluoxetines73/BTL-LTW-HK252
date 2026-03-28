<section class="panel">
	<a href="<?= BASE_URL ?>product">&larr; Quay lai danh sach</a>

	<h1><?= htmlspecialchars($product['name'] ?? 'San pham') ?></h1>
	<p class="price"><?= number_format((float)($product['price'] ?? 0), 0, ',', '.') ?> VND</p>
	<p><?= htmlspecialchars($product['description'] ?? 'Dang cap nhat noi dung.') ?></p>

	<button type="button">Them vao gio (demo)</button>
</section>
