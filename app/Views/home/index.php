<section class="hero">
	<p class="eyebrow">Giai doan 1 - Nen tang du an</p>
	<h1>Website cong ty theo mo hinh MVC</h1>
	<p>Skeleton nay giup 4 thanh vien code doc lap va ghep lai khong vo flow.</p>
	<div class="hero-actions">
		<a class="btn" href="<?= BASE_URL ?>product">Xem san pham</a>
		<a class="btn btn-secondary" href="<?= BASE_URL ?>home/contact">Lien he ngay</a>
	</div>
</section>

<section class="panel">
	<h2>Muc tieu tuan nay</h2>
	<ul>
		<?php foreach (($highlights ?? []) as $item): ?>
			<li><?= htmlspecialchars($item) ?></li>
		<?php endforeach; ?>
	</ul>
</section>
