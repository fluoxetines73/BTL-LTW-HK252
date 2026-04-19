<style>
    /* CSS bọc riêng cho nội dung trang chủ, không ảnh hưởng layout chung */
    .home-wrapper { background-color: #1a1a1a; color: #fff; min-height: 100vh; padding-bottom: 50px; } 
    .hero-section { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=2070') center/cover; padding: 100px 0; }
    .movie-card { transition: transform 0.3s, box-shadow 0.3s; border-radius: 10px; overflow: hidden; border: none; background: #2d2d2d; }
    .movie-card:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(229, 9, 20, 0.5); }
    .movie-img { height: 350px; object-fit: cover; }
    .text-cgv { color: #e50914; } 
    .btn-book { background-color: #e50914; color: white; border: none; font-weight: bold; }
    .btn-book:hover { background-color: #b20710; color: white; }
</style>

<div class="home-wrapper">
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4 text-white">Khám Phá Thế Giới Điện Ảnh</h1>
            <p class="lead mb-5 text-light">Đặt vé ngay hôm nay để nhận những ưu đãi hấp dẫn nhất!</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="<?= BASE_URL ?>" method="GET" class="d-flex shadow-lg">
                        <input type="text" name="q" class="form-control form-control-lg rounded-start" 
                               placeholder="Nhập tên phim bạn muốn tìm..." 
                               value="<?= htmlspecialchars($keyword) ?>">
                        <button type="submit" class="btn btn-book btn-lg rounded-end px-4">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <?php if ($keyword !== ''): ?>
            <h3 class="mb-4 border-bottom border-danger pb-2">
                Kết quả tìm kiếm cho: <span class="text-cgv">"<?= htmlspecialchars($keyword) ?>"</span>
                <a href="<?= BASE_URL ?>" class="btn btn-sm btn-outline-light float-end">Xóa tìm kiếm</a>
            </h3>
            <?php if (empty($nowShowing) && empty($comingSoon)): ?>
                <div class="alert alert-secondary text-center py-5" style="background: #2d2d2d; border: none; color: #ccc;">
                    <i class="fas fa-sad-tear fa-3x mb-3"></i><br>Không tìm thấy bộ phim nào phù hợp.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($nowShowing)): ?>
            <h3 class="mb-4 mt-4 fw-bold text-uppercase border-start border-5 border-danger ps-3">Phim Đang Chiếu</h3>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach ($nowShowing as $movie): ?>
                <div class="col">
                    <div class="card movie-card h-100">
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . 'public/uploads/movies/' . $movie['poster'] : 'https://via.placeholder.com/300x450?text=Poster+' . urlencode($movie['title']) ?>" class="card-img-top movie-img" alt="<?= htmlspecialchars($movie['title']) ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-light text-truncate" title="<?= htmlspecialchars($movie['title']) ?>"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="fas fa-clock me-1"></i> <?= $movie['duration_min'] ?> phút | 
                                <span class="badge bg-warning text-dark"><?= $movie['age_rating'] ?></span>
                            </p>
                            <div class="mt-auto d-grid gap-2">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-book">
                                    <i class="fas fa-ticket-alt me-1"></i> Đặt Vé Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($comingSoon)): ?>
            <h3 class="mb-4 mt-5 fw-bold text-uppercase border-start border-5 border-info ps-3">Phim Sắp Chiếu</h3>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach ($comingSoon as $movie): ?>
                <div class="col">
                    <div class="card movie-card h-100" style="opacity: 0.8;">
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . 'public/uploads/movies/' . $movie['poster'] : 'https://via.placeholder.com/300x450?text=Poster+' . urlencode($movie['title']) ?>" class="card-img-top movie-img" alt="<?= htmlspecialchars($movie['title']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-light text-truncate"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text text-info small mb-3">
                                <i class="fas fa-calendar-alt me-1"></i> Khởi chiếu: <?= date('d/m/Y', strtotime($movie['release_date'])) ?>
                            </p>
                            <div class="mt-auto d-grid gap-2">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-outline-info">
                                    <i class="fas fa-info-circle me-1"></i> Xem Thông Tin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>