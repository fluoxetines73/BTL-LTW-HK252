<!-- app/Views/movies/current.php -->
<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase border-start border-5 border-danger ps-3 text-white">Phim Đang Chiếu</h3>
    
   <!-- Thanh Bộ Lọc (Filter) Thể Loại -->
    <?php if (!empty($genres)): ?>
        <div class="genre-filter-wrapper mb-5 d-flex flex-wrap justify-content-center gap-2">
            <!-- Nút Tất cả: Xóa toàn bộ filter -->
            <a href="<?= BASE_URL ?>movies/current" 
               class="btn rounded-pill px-4 py-2 <?= empty($selectedGenres) ? 'btn-danger text-white' : 'btn-outline-secondary text-light' ?>">
                Tất cả
            </a>
            
            <?php foreach ($genres as $genre): ?>
                <?php 
                    // Copy mảng các thể loại đang được chọn hiện tại
                    $currentQuery = $selectedGenres; 
                    
                    // Logic Bật/Tắt (Toggle):
                    if (in_array($genre['slug'], $currentQuery)) {
                        // Nếu đã chọn rồi -> Bấm vào sẽ Gỡ ra khỏi mảng
                        $currentQuery = array_diff($currentQuery, [$genre['slug']]);
                    } else {
                        // Nếu chưa chọn -> Bấm vào sẽ Thêm vào mảng
                        $currentQuery[] = $genre['slug'];
                    }
                    
                    // Dùng http_build_query để tự động tạo chuỗi URL chuẩn (vd: genre[0]=hai&genre[1]=hanh-dong)
                    $queryString = !empty($currentQuery) ? '?' . http_build_query(['genre' => $currentQuery]) : '';
                    $url = BASE_URL . 'movies/current' . $queryString;
                ?>
                <!-- In nút bấm -->
                <a href="<?= $url ?>" 
                   class="btn rounded-pill px-4 py-2 <?= in_array($genre['slug'], $selectedGenres) ? 'btn-danger text-white' : 'btn-outline-secondary text-light' ?>">
                    <?= htmlspecialchars($genre['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Danh sách phim Đang Chiếu -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($nowShowing)): ?>
            <?php foreach ($nowShowing as $movie): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm bg-dark text-white border-0">
                        <!-- Hình ảnh Poster phim -->
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/300x450?text=No+Poster' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Tên phim -->
                            <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($movie['title']) ?>">
                                <?= htmlspecialchars($movie['title']) ?>
                            </h5>
                            
                            <!-- Thông tin thời lượng và độ tuổi -->
                            <p class="card-text text-muted small mb-3">
                                <i class="fas fa-clock me-1"></i> <?= (int)($movie['duration_min'] ?? 0) ?> phút | 
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($movie['age_rating'] ?? 'P') ?></span>
                            </p>
                            
                            <!-- Nút điều hướng Đặt Vé Ngay -->
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
            <!-- Hiển thị khi không có phim nào (hoặc khi lọc không có kết quả) -->
            <div class="col-12 text-center py-5">
                <p class="text-white fs-5">Hiện không có phim nào thuộc thể loại này đang chiếu.</p>
                <a href="<?= BASE_URL ?>movies/current" class="btn btn-outline-light mt-3">Xem tất cả phim</a>
            </div>
        <?php endif; ?>
    </div>
</div>