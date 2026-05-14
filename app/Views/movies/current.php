<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase border-start border-5 border-danger ps-3 text-dark">Phim Đang Chiếu</h3>
    
    <div class="row mb-4 justify-content-center">
        <div class="col-md-8 col-lg-6">
            <form action="<?= BASE_URL ?>movies/current" method="GET" class="d-flex shadow-sm rounded-pill bg-white p-1 border">
                <?php if (!empty($selectedGenres)): ?>
                    <?php foreach ($selectedGenres as $g): ?>
                        <input type="hidden" name="genre[]" value="<?= htmlspecialchars($g) ?>">
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <input type="text" name="q" class="form-control border-0 rounded-pill px-4 shadow-none" 
                       placeholder="Nhập tên phim hoặc đạo diễn..." 
                       value="<?= htmlspecialchars($keyword ?? '') ?>">
                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </form>
        </div>
    </div>

    <?php if (!empty($genres)): ?>
        <div class="genre-filter-wrapper mb-5 d-flex flex-wrap justify-content-center gap-2">
            <a href="<?= BASE_URL ?>movies/current<?= !empty($keyword) ? '?q=' . urlencode($keyword) : '' ?>" 
               class="btn rounded-pill px-4 py-2 <?= empty($selectedGenres) ? 'btn-danger text-white' : 'btn-outline-secondary text-dark' ?>">
                Tất cả
            </a>
            
            <?php foreach ($genres as $genre): ?>
                <?php 
                    $currentQuery = $selectedGenres; 
                    if (in_array($genre['slug'], $currentQuery)) {
                        $currentQuery = array_diff($currentQuery, [$genre['slug']]);
                    } else {
                        $currentQuery[] = $genre['slug'];
                    }
                    
                    $queryParams = [];
                    if (!empty($currentQuery)) $queryParams['genre'] = $currentQuery;
                    if (!empty($keyword)) $queryParams['q'] = $keyword;
                    
                    $queryString = !empty($queryParams) ? '?' . http_build_query($queryParams) : '';
                    $url = BASE_URL . 'movies/current' . $queryString;
                ?>
                <a href="<?= $url ?>" 
                   class="btn rounded-pill px-4 py-2 <?= in_array($genre['slug'], $selectedGenres) ? 'btn-danger text-white' : 'btn-outline-secondary text-dark' ?>">
                    <?= htmlspecialchars($genre['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($nowShowing)): ?>
            <?php foreach ($nowShowing as $movie): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-white text-dark border-0">
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . 'public/uploads/movies/' . htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/300x450?text=No+Poster' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($movie['title']) ?>">
                                <?= htmlspecialchars($movie['title']) ?>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="fas fa-clock me-1"></i> <?= (int)($movie['duration_min'] ?? 0) ?> phút | 
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($movie['age_rating'] ?? 'P') ?></span>
                            </p>
                            <div class="mt-auto d-grid">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-danger fw-bold">
                                    <i class="fas fa-ticket-alt me-1"></i> Đặt Vé Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-dark fs-5">Không tìm thấy phim nào phù hợp với tìm kiếm của bạn.</p>
                <a href="<?= BASE_URL ?>movies/current" class="btn btn-outline-danger mt-3">Xóa bộ lọc và xem tất cả</a>
            </div>
        <?php endif; ?>
    </div>
</div>