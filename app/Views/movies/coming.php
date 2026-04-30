<!-- app/Views/movies/coming.php -->
<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase border-start border-5 border-info ps-3 text-white">Phim Sắp Chiếu</h3>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($comingSoon)): ?>
            <?php foreach ($comingSoon as $movie): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-dark text-white border-0" style="opacity: 0.95;">
                        <img src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="text-info small mb-3">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Khởi chiếu: <?= date('d/m/Y', strtotime($movie['release_date'])) ?>
                            </p>
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-outline-info w-100">
                                    <i class="fas fa-info-circle me-1"></i> Xem Thông Tin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-white">Hiện không có phim nào sắp ra mắt.</p>
            </div>
        <?php endif; ?>
    </div>
</div>