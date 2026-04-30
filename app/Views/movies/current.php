<!-- app/Views/movies/current.php -->
<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase border-start border-5 border-danger ps-3 text-white">Phim Đang Chiếu</h3>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($nowShowing)): ?>
            <?php foreach ($nowShowing as $movie): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-dark text-white border-0">
                        <img src="<?= BASE_URL . htmlspecialchars($movie['poster']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-clock me-1"></i> <?= $movie['duration_min'] ?> phút | 
                                <span class="badge bg-warning text-dark"><?= $movie['age_rating'] ?></span>
                            </p>
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-danger w-100 fw-bold">
                                    <i class="fas fa-ticket-alt me-1"></i> Đặt Vé Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-white">Hiện không có phim nào đang chiếu.</p>
            </div>
        <?php endif; ?>
    </div>
</div>