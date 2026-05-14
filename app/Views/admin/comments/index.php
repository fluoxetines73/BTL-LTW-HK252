<?php
// Admin Comments Management Page
$comments = $comments ?? [];
$activeTab = $activeTab ?? 'all';
$selectedNewsId = $selectedNewsId ?? null;
// layout `stats` comes from controller (users/movies/showtimes/combos/news)
$stats = $stats ?? ['users' => 0, 'movies' => 0, 'showtimes' => 0, 'combos' => 0, 'news' => 0];
// comment-specific stats
$commentStats = $commentStats ?? ['total' => 0, 'reported' => 0, 'pending' => 0];

// Pagination vars (passed from controller)
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$perPage = $perPage ?? 15;
$totalComments = $totalComments ?? $stats['total'];
?>

<div class="admin-section admin-comments">
    <div class="admin-section-header">
        <div class="admin-section-title">
            <h2 class="admin-page-title">Quản lý bình luận</h2>
            <p class="admin-page-subtitle">Duyệt, xoá, và xử lý báo cáo bình luận từ người dùng</p>
        </div>
    </div>

    <!-- Stats Cards -->
        <div class="comments-stats d-flex gap-3 mb-4">
        <div class="stat-badge stat-badge-all">
            <span class="stat-value"><?= $commentStats['total'] ?></span>
            <span class="stat-label">Tổng bình luận</span>
        </div>
        <div class="stat-badge stat-badge-reported">
            <span class="stat-value"><?= $commentStats['reported'] ?></span>
            <span class="stat-label">Báo cáo</span>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs admin-comment-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $activeTab === 'all' ? 'active' : '' ?>" id="all-tab" href="<?= BASE_URL ?>admin/comments/index/all<?= !empty($selectedNewsId) ? '?news_id=' . (int)$selectedNewsId : '' ?>">
                <i class="fas fa-comments me-2"></i>Tất cả (<span class="tab-count"><?= $commentStats['total'] ?></span>)
            </a>
        </li>
        <!-- Pending tab removed as requested -->
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $activeTab === 'reported' ? 'active' : '' ?>" id="reported-tab" href="<?= BASE_URL ?>admin/comments/index/reported<?= !empty($selectedNewsId) ? '?news_id=' . (int)$selectedNewsId : '' ?>">
                <i class="fas fa-flag me-2"></i>Báo cáo (<span class="tab-count"><?= $commentStats['reported'] ?></span>)
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- All Comments Tab -->
        <div class="tab-pane fade <?= $activeTab === 'all' ? 'show active' : '' ?>" id="all-comments" role="tabpanel" aria-labelledby="all-tab">
            <?php if (($totalComments ?? 0) === 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Chưa có bình luận nào
                </div>
            <?php else: ?>
                <div class="comments-toolbar mb-3 d-flex gap-2 align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAllComments" title="Chọn tất cả">
                        <label class="form-check-label" for="selectAllComments">Chọn tất cả</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" id="deleteSelectedBtn" style="display:none;">
                        <i class="fas fa-trash me-1"></i><span id="deleteCountText">Xoá</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover admin-comments-table" id="allCommentsTable">
                        <thead>
                            <tr>
                                <th style="width: 40px;"><input type="checkbox" class="form-check-input" id="headerCheckbox" onchange="toggleAllCheckboxes(this, 'allCommentsTable')"></th>
                                <th>Người bình luận</th>
                                <th>Bài đăng</th>
                                <th>Nội dung</th>
                                <th>Ngày bình luận</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr class="admin-comment-row <?= $comment['is_reported'] ? 'reported' : '' ?>" data-comment-id="<?= $comment['id'] ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input comment-checkbox" value="<?= $comment['id'] ?>" onchange="updateDeleteButton()">
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($comment['username']) ?></strong>
                                        <?php if ($comment['is_reported']): ?>
                                            <div class="mt-2">
                                                <span class="badge bg-danger" title="Số lần báo cáo">
                                                    <i class="fas fa-flag me-1"></i><?= $comment['report_count'] ?> báo cáo
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>news/detail/<?= (int)$comment['news_id'] ?>" target="_blank" class="text-decoration-none text-primary" title="<?= htmlspecialchars($comment['news_title']) ?>">
                                            <?= htmlspecialchars(substr($comment['news_title'], 0, 35)) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="admin-comment-preview">
                                            <?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>...
                                        </span>
                                        <button type="button" class="btn btn-link btn-sm p-0 ms-2" data-bs-toggle="modal" data-bs-target="#commentModal" data-comment="<?= htmlspecialchars(json_encode($comment)) ?>">
                                            Xem đầy đủ
                                        </button>
                                    </td>
                                    <td class="text-muted small">
                                        <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                    </td>
                                    <td>
                                        <?php if (!$comment['is_approved']): ?>
                                            <button type="button" class="btn btn-sm btn-success btn-approve" data-comment-id="<?= $comment['id'] ?>" title="Duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-comment-id="<?= $comment['id'] ?>" title="Xoá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pending tab removed -->

        <!-- Reported Comments Tab -->
        <div class="tab-pane fade <?= $activeTab === 'reported' ? 'show active' : '' ?>" id="reported-comments" role="tabpanel" aria-labelledby="reported-tab">
            <?php if (($totalComments ?? 0) === 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Không có bình luận bị báo cáo
                </div>
            <?php else: ?>
                <div class="comments-toolbar mb-3 d-flex gap-2 align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectReportedComments" title="Chọn tất cả">
                        <label class="form-check-label" for="selectReportedComments">Chọn tất cả</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" id="deleteReportedBtn" style="display:none;">
                        <i class="fas fa-trash me-1"></i><span id="deleteReportedCountText">Xoá</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover admin-comments-table" id="reportedCommentsTable">
                        <thead>
                            <tr>
                                <th style="width: 40px;"><input type="checkbox" class="form-check-input" id="reportedHeaderCheckbox" onchange="toggleAllCheckboxes(this, 'reportedCommentsTable')"></th>
                                <th>Người bình luận</th>
                                <th>Bài đăng</th>
                                <th>Nội dung</th>
                                <th>Lý do báo cáo</th>
                                <th>Báo cáo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr class="admin-comment-row reported" data-comment-id="<?= $comment['id'] ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input comment-checkbox" value="<?= $comment['id'] ?>" onchange="updateDeleteButton()">
                                    </td>
                                    <td><strong><?= htmlspecialchars($comment['username']) ?></strong></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>news/detail/<?= (int)$comment['news_id'] ?>" target="_blank" class="text-decoration-none text-primary">
                                            <?= htmlspecialchars(substr($comment['news_title'], 0, 35)) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="admin-comment-preview">
                                            <?= htmlspecialchars(substr($comment['content'], 0, 40)) ?>...
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-danger">
                                            <strong><?= htmlspecialchars($comment['report_reason'] ?? 'Không có lý do') ?></strong>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-flag me-1"></i><?= $comment['report_count'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-comment-id="<?= $comment['id'] ?>">
                                            <i class="fas fa-trash me-1"></i>Xoá
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
        <nav aria-label="Comments pagination" class="mt-3">
            <ul class="pagination justify-content-center">
                <?php
                $base = BASE_URL . 'admin/comments/index/' . urlencode($activeTab) . '?';
                $qs = '';
                if (!empty($selectedNewsId)) {
                    $qs .= 'news_id=' . (int)$selectedNewsId . '&';
                }
                for ($p = 1; $p <= $totalPages; $p++):
                    $activeClass = $p == $page ? 'active' : '';
                ?>
                    <li class="page-item <?= $activeClass ?>"><a class="page-link" href="<?= $base ?><?= $qs ?>page=<?= $p ?>"><?= $p ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

    <!-- Comment Detail Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Chi tiết bình luận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="comment-detail">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Người bình luận:</strong></p>
                        <p id="modal-author"></p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1"><strong>Bài đăng:</strong></p>
                        <p id="modal-news-title"></p>
                    </div>
                    <div class="mb-3" id="modal-report-section" style="display:none;">
                        <p class="mb-1"><strong><i class="fas fa-flag text-danger me-1"></i>Báo cáo:</strong></p>
                        <div class="alert alert-danger mb-2">
                            <strong>Lý do:</strong> <span id="modal-report-reason"></span>
                        </div>
                        <p class="mb-1"><strong>Số lần báo cáo:</strong> <span id="modal-report-count" class="badge bg-danger"></span></p>
                    </div>
                    <div>
                        <p class="mb-1"><strong>Nội dung bình luận:</strong></p>
                        <div id="modal-content" class="admin-comment-full-content bg-light p-3 rounded"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
.admin-comments {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.comments-stats {
    flex-wrap: wrap;
}

.stat-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 20px;
    border-radius: 8px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
}

.stat-badge-all .stat-value { color: #6b7280; font-weight: bold; font-size: 1.5rem; }
.stat-badge-reported .stat-value { color: #dc2626; font-weight: bold; font-size: 1.5rem; }
.stat-badge-pending .stat-value { color: #f59e0b; font-weight: bold; font-size: 1.5rem; }

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 4px;
}

.admin-comment-tabs .nav-link {
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 600;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.admin-comment-tabs .nav-link.active {
    border-bottom-color: #E71A0F;
    color: #E71A0F;
}

.comments-toolbar {
    background: #f9fafb;
    padding: 12px 16px;
    border-radius: 6px;
}

.admin-comments-table {
    margin-bottom: 0;
}

.admin-comment-row {
    transition: background-color 0.3s ease;
}

.admin-comment-row.reported {
    background-color: #FFF5F5;
}

.admin-comment-row:hover {
    background-color: #f9fafb;
}

.admin-comment-preview {
    color: #6b7280;
    font-size: 0.9rem;
}

.admin-comment-full-content {
    color: #1f2937;
    line-height: 1.6;
    word-wrap: break-word;
    white-space: pre-wrap;
}

.btn-approve,
.btn-delete {
    padding: 6px 10px;
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .admin-comments {
        padding: 16px;
    }
    
    .comments-stats {
        gap: 8px !important;
    }
    
    .stat-badge {
        flex: 1;
        padding: 8px 12px;
    }
    
    .stat-value {
        font-size: 1.25rem !important;
    }
}
</style>
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Chi tiết bình luận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="comment-detail">
                    <p class="mb-2"><strong>Người bình luận:</strong> <span id="modal-author"></span></p>
                    <p class="mb-2"><strong>Bài đăng:</strong> <span id="modal-news-title"></span></p>
                    <p class="mb-3"><strong>Nội dung:</strong></p>
                    <div id="modal-content" class="admin-comment-full-content bg-light p-3 rounded"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
.admin-comments {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.admin-comment-tabs .nav-link {
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 600;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.admin-comment-tabs .nav-link.active {
    border-bottom-color: #E71A0F;
    color: #E71A0F;
}

.admin-comments-table {
    margin-bottom: 0;
}

.admin-comment-row {
    transition: background-color 0.3s ease;
}

.admin-comment-row.reported {
    background-color: #FFF5F5;
}

.admin-comment-row:hover {
    background-color: #f9fafb;
}

.admin-comment-preview {
    color: #6b7280;
    font-size: 0.9rem;
}

.admin-comment-full-content {
    color: #1f2937;
    line-height: 1.6;
    word-wrap: break-word;
}

.btn-approve,
.btn-delete {
    padding: 6px 10px;
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .admin-comments {
        padding: 16px;
    }

    .admin-comment-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    .table-responsive {
        font-size: 0.85rem;
    }
}
</style>

<!-- Admin comments script -->
<script src="<?= BASE_URL ?>public/js/admin-comments.js"></script>
