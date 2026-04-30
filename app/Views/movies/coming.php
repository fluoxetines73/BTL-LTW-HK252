<!-- app/Views/movies/coming.php -->
<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase border-start border-5 border-info ps-3 text-white">Phim Sắp Chiếu</h3>
    
   <!-- Thanh Bộ Lọc (Filter) Thể Loại -->
    <?php if (!empty($genres)): ?>
        <div class="genre-filter-wrapper mb-5 d-flex flex-wrap justify-content-center gap-2">
            <!-- Nút Tất cả: Xóa toàn bộ filter -->
            <a href="<?= BASE_URL ?>movies/coming" 
               class="btn rounded-pill px-4 py-2 <?= empty($selectedGenres) ? 'btn-info text-dark fw-bold text-white' : 'btn-outline-secondary text-light' ?>">
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
                    $url = BASE_URL . 'movies/coming' . $queryString;
                ?>
                <!-- In nút bấm -->
                <a href="<?= $url ?>" 
                   class="btn rounded-pill px-4 py-2 <?= in_array($genre['slug'], $selectedGenres) ? 'btn-info text-dark fw-bold text-white' : 'btn-outline-secondary text-light' ?>">
                    <?= htmlspecialchars($genre['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Danh sách phim Sắp Chiếu -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if (!empty($comingSoon)): ?>
            <?php foreach ($comingSoon as $movie): ?>
                <div class="col">
                    <!-- Thêm hiệu ứng opacity cho phim chưa chiếu -->
                    <div class="card h-100 shadow-sm bg-dark text-white border-0" style="opacity: 0.95;">
                        <!-- Hình ảnh Poster phim -->
                        <img src="<?= !empty($movie['poster']) ? BASE_URL . htmlspecialchars($movie['poster']) : 'https://via.placeholder.com/300x450?text=No+Poster' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($movie['title']) ?>" 
                             style="height: 350px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column text-center">
                            <!-- Tên phim -->
                            <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($movie['title']) ?>">
                                <?= htmlspecialchars($movie['title']) ?>
                            </h5>
                            
                            <!-- Thông tin ngày khởi chiếu -->
                            <p class="text-info small mb-3">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Khởi chiếu: <?= !empty($movie['release_date']) ? date('d/m/Y', strtotime($movie['release_date'])) : 'Đang cập nhật' ?>
                            </p>
                            
                            <!-- Nút điều hướng Xem Thông Tin -->
                            <div class="mt-auto d-grid">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-outline-info fw-bold">
                                    <i class="fas fa-info-circle me-1"></i> Xem Thông Tin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Hiển thị khi không có phim nào -->
            <div class="col-12 text-center py-5">
                <p class="text-white fs-5">Hiện không có phim sắp chiếu nào thuộc thể loại này.</p>
                <a href="<?= BASE_URL ?>movies/coming" class="btn btn-outline-light mt-3">Xem tất cả phim sắp chiếu</a>
            </div>
        <?php endif; ?>
    </div>
</div>