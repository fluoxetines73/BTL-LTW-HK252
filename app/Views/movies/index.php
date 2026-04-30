<!-- app/Views/movies/index.php -->
<section class="container py-5">
    <div class="mb-4">
        <h1 class="display-4 text-white">Danh sách phim</h1>
        <p class="text-muted">Khám phá bộ sưu tập phim đa dạng tại rạp của chúng tôi</p>
    </div>

    <!-- Kết quả tìm kiếm nếu có -->
    <?php if (isset($keyword) && $keyword !== ''): ?>
        <h3 class="mb-4 text-white">
            Kết quả tìm kiếm cho: <span class="text-danger">"<?= htmlspecialchars($keyword) ?>"</span>
        </h3>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 bg-dark text-white">
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/300x450' ?>" 
                             class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text small text-muted">
                                <?= (int)($movie['duration_min'] ?? 0) ?> phút | 
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($movie['age_rating'] ?? 'P') ?></span>
                            </p>
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-danger w-100">
                                    Chi tiết & Đặt vé
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-white">Hiện chưa có phim nào trong danh sách.</p>
                <a href="<?= BASE_URL ?>" class="btn btn-outline-light">Quay lại trang chủ</a>
            </div>
        <?php endif; ?>
    </div>
</section>