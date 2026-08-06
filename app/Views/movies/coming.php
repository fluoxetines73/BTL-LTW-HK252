<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-2 border-info pb-2">
        <h2 class="text-uppercase text-info fw-bold mb-0"><i class="fas fa-calendar-alt me-2"></i>Phim Sắp Chiếu</h2>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white fw-bold">
                    <i class="fas fa-filter me-1"></i> Bộ lọc tìm kiếm
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>movies/coming" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Từ khóa</label>
                            <input type="text" class="form-control form-control-sm" name="q" placeholder="Tên phim, đạo diễn..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Thể loại</label>
                            <div class="row g-2">
                                <?php foreach ($genres as $genre): ?>
                                    <div class="col-6 col-lg-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genre[]" value="<?= $genre['slug'] ?>" id="genre_<?= $genre['slug'] ?>" <?= in_array($genre['slug'], $selectedGenres ?? []) ? 'checked' : '' ?>>
                                            <label class="form-check-label small" for="genre_<?= $genre['slug'] ?>">
                                                <?= $genre['name'] ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info text-white btn-sm fw-bold"><i class="fas fa-search me-1"></i> Lọc Phim</button>
                            <a href="<?= BASE_URL ?>movies/coming" class="btn btn-outline-secondary btn-sm">Xóa bộ lọc</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php if (empty($comingSoon)): ?>
                <div class="alert alert-warning text-center py-5 shadow-sm border-0">
                    <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                    <h5 class="text-muted">Không có bộ phim sắp chiếu nào phù hợp với điều kiện lọc.</h5>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4">
                    <?php foreach ($comingSoon as $movie): ?>
                        <div class="col">
                            <div class="card h-100 movie-card shadow-sm border-0 position-relative">
                                <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>">
                                    <img src="<?= empty($movie['poster']) ? BASE_URL . 'public/uploads/movies/default-poster.jpg' : (str_starts_with($movie['poster'], 'http') ? $movie['poster'] : BASE_URL . 'public/uploads/movies/' . $movie['poster']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($movie['title']) ?>" 
                                         style="height: 280px; object-fit: cover; filter: brightness(0.9);">
                                </a>
                                
                                <div class="position-absolute top-0 end-0 bg-info text-white px-2 py-1 m-2 rounded small fw-bold shadow-sm">
                                    <?= date('d/m/Y', strtotime($movie['release_date'])) ?>
                                </div>

                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="card-title fw-bold text-truncate mb-1" title="<?= htmlspecialchars($movie['title']) ?>">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($movie['title']) ?></a>
                                    </h6>
                                    <p class="card-text small text-muted mb-3">
                                        <i class="fas fa-clock me-1"></i> <?= htmlspecialchars($movie['duration'] ?? 120) ?> phút
                                    </p>
                                    <div class="mt-auto d-grid">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $movie['id'] ?>" class="btn btn-outline-info btn-sm fw-bold">
                                            <i class="fas fa-info-circle me-1"></i> Xem Chi Tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php 
                            $queryParams = $_GET;
                            unset($queryParams['url']); 
                            unset($queryParams['page']); 
                            $baseQueryString = http_build_query($queryParams);
                            $baseLink = BASE_URL . 'movies/coming?' . ($baseQueryString ? $baseQueryString . '&' : '');
                            ?>
                            
                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link shadow-none" href="<?= $baseLink . 'page=' . ($currentPage - 1) ?>">&laquo;</a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link shadow-none <?= ($i == $currentPage) ? 'bg-info border-info text-white' : 'text-dark' ?>" 
                                       href="<?= $baseLink . 'page=' . $i ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link shadow-none text-dark" href="<?= $baseLink . 'page=' . ($currentPage + 1) ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>