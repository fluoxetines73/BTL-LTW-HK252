<?php
$article = $article ?? [];
$title = (string)($article['title'] ?? 'Bài viết');
$highlightTitle = trim((string)($article['highlight_title'] ?? ''));
$summaryContent = trim((string)($article['content'] ?? ''));
$detailContent = trim((string)($article['detail_content'] ?? ''));
if ($detailContent === '') {
    $detailContent = $summaryContent;
}
$articleDetailHtml = (string)($articleDetailHtml ?? nl2br(htmlspecialchars($detailContent !== '' ? $detailContent : 'Đang cập nhật nội dung chi tiết.')));
$isLoggedIn = isset($_SESSION['auth_user']['id']) || isset($_SESSION['user_id']);
$isAdmin = (
    (isset($_SESSION['auth_user']['role']) && $_SESSION['auth_user']['role'] === 'admin')
    || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true)
);
$newsId = $article['id'] ?? 0;
?>

<section class="news-detail-page">
    <a class="news-detail-back" href="<?= BASE_URL ?>news">&larr; Quay lại danh sách tin</a>

    <?php if ($highlightTitle !== ''): ?>
        <p class="news-detail-highlight-title"><?= htmlspecialchars($highlightTitle) ?></p>
    <?php endif; ?>

    <h1 class="news-detail-main-title"><?= htmlspecialchars($title) ?></h1>

    <?php if (!empty($article['published_at'])): ?>
        <p class="news-detail-meta"><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string)$article['published_at']))) ?></p>
    <?php endif; ?>

    <div class="news-detail-content-grid">
        <?php if (!empty($articleImageUrl)): ?>
            <figure class="news-detail-figure">
                <img src="<?= htmlspecialchars((string)$articleImageUrl) ?>" alt="<?= htmlspecialchars($title) ?>">
            </figure>
        <?php endif; ?>

        <article class="news-detail-article-body">
            <?= $articleDetailHtml ?>
        </article>
    </div>
</section>

<!-- Comments Section -->
<section class="news-detail-comments-section mt-5">
    <div class="news-comments-container">
        <!-- Comments Header -->
        <div class="news-comments-header mb-4">
            <h2 class="news-comments-title">
                <i class="fas fa-comments me-2"></i>Bình luận
            </h2>
            <span class="news-comments-count" id="comment-count">0</span>
        </div>

        <!-- Comment Form -->
        <div class="news-comment-form-wrapper mb-5">
            <div class="news-comment-form-card">
                <h3 class="news-comment-form-title mb-3">Để lại bình luận</h3>
                
                <?php if (!$isLoggedIn): ?>
                    <div class="news-comment-login-prompt">
                        <p class="mb-3">
                            <i class="fas fa-lock me-2"></i>
                            Bạn cần đăng nhập để bình luận
                        </p>
                        <div class="d-flex gap-2">
                            <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                            <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <form id="comment-form" class="news-comment-form">
                        <div class="mb-3">
                            <textarea 
                                id="comment-content" 
                                class="form-control news-comment-input" 
                                placeholder="Chia sẻ ý kiến của bạn về bài viết này (tối thiểu 3 ký tự)..."
                                rows="4"
                                maxlength="1000"
                                required></textarea>
                            <small class="form-text text-muted d-block mt-2">
                                <span id="char-count">0</span>/1000 ký tự
                            </small>
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary" id="submit-comment">
                                <i class="fas fa-paper-plane me-1"></i>Gửi bình luận
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments List -->
        <div class="news-comments-list" id="comments-container">
            <div class="text-center py-4">
                <p class="text-muted">
                    <i class="fas fa-spinner fa-spin me-2"></i>Đang tải bình luận...
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Login Required Modal -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div>
                    <h5 class="modal-title" id="loginRequiredLabel">
                        <i class="fas fa-lock me-2"></i>Yêu cầu đăng nhập
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Bạn cần đăng nhập tài khoản để có thể bình luận trên bài viết.</p>
                <p class="text-muted mb-3">Nếu chưa có tài khoản, vui lòng đăng ký một tài khoản mới.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                </a>
                <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-1"></i>Đăng ký
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Report Comment Modal -->
<div class="modal fade" id="reportCommentModal" tabindex="-1" aria-labelledby="reportCommentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="reportCommentLabel">
                    <i class="fas fa-flag me-2"></i>Báo cáo bình luận
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="report-form">
                <div class="modal-body">
                    <input type="hidden" id="report-comment-id" value="">
                    <div class="mb-3">
                        <label for="report-reason" class="form-label">Lý do báo cáo</label>
                        <textarea 
                            id="report-reason" 
                            class="form-control" 
                            placeholder="Vui lòng cho biết lý do báo cáo bình luận này..."
                            rows="3"
                            minlength="5"
                            maxlength="255"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-flag me-1"></i>Báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsId = <?= (int)$newsId ?>;
    const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
    
    // Load comments
    loadComments();
    
    // Handle comment form submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', handleCommentSubmit);
        
        // Character counter
        const textarea = document.getElementById('comment-content');
        const charCount = document.getElementById('char-count');
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    // Handle report form
    const reportForm = document.getElementById('report-form');
    if (reportForm) {
        reportForm.addEventListener('submit', handleReportSubmit);
    }

    function loadComments() {
        fetch(`<?= BASE_URL ?>comment/getComments/${newsId}`, { credentials: 'same-origin' })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('comments-container');
                const safeCount = Number.isInteger(data.count) ? data.count : 0;
                document.getElementById('comment-count').textContent = safeCount;
                
                if (safeCount === 0) {
                    container.innerHTML = '<div class="text-center py-5"><p class="text-muted"><i class="fas fa-comment-slash me-2"></i>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p></div>';
                } else {
                    container.innerHTML = data.comments.map(comment => renderCommentElement(comment)).join('');
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                document.getElementById('comment-count').textContent = '0';
                document.getElementById('comments-container').innerHTML = '<div class="text-center py-5"><p class="text-muted"><i class="fas fa-comment-slash me-2"></i>Hiện chưa có bình luận nào.</p></div>';
            });
    }

    function renderCommentElement(comment) {
        return `
            <div class="news-comment-item">
                <div class="news-comment-header">
                    <div class="news-comment-author-info">
                        <img src="${comment.avatar ? comment.avatar : '<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg'}" alt="${escapeHtml(comment.username)}" class="news-comment-avatar">
                        <div class="news-comment-author-meta">
                            <strong class="news-comment-author">${escapeHtml(comment.username)}</strong>
                            <span class="news-comment-date">${formatDate(comment.created_at)}</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-danger" onclick="showReportModal(${comment.id})">
                        <i class="fas fa-flag me-1"></i>Báo cáo
                    </button>
                </div>
                <div class="news-comment-content">
                    ${escapeHtml(comment.content)}
                </div>
            </div>
        `;
    }

    function showSuccessToast(message) {
        const toastId = 'comment-success-toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', toastHtml);
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
        setTimeout(() => toastEl.remove(), 4000);
    }

    function handleCommentSubmit(e) {
        e.preventDefault();
        
        const content = document.getElementById('comment-content').value.trim();
        
        if (content.length < 3) {
            alert('Bình luận phải có ít nhất 3 ký tự');
            return;
        }
        
        const submitBtn = document.getElementById('submit-comment');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang gửi...';
        
        fetch('<?= BASE_URL ?>comment/add', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                news_id: newsId,
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.comment) {
                // Clear form immediately
                document.getElementById('comment-content').value = '';
                document.getElementById('char-count').textContent = '0';
                
                // Show success toast
                showSuccessToast('Bình luận đã gửi thành công!');
                
                // Add comment to DOM instantly (at the top since comments are ordered DESC by created_at)
                const container = document.getElementById('comments-container');
                const newCommentHtml = renderCommentElement(data.comment);
                
                // Check if this is the first comment
                if (container.querySelector('.text-center')) {
                    container.innerHTML = newCommentHtml;
                } else {
                    container.insertAdjacentHTML('afterbegin', newCommentHtml);
                }
                
                // Update comment count
                const currentCount = parseInt(document.getElementById('comment-count').textContent) || 0;
                document.getElementById('comment-count').textContent = currentCount + 1;
            } else {
                alert('Lỗi: ' + (data.error || 'Không thể gửi bình luận'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Gửi bình luận';
        });
    }

    function handleReportSubmit(e) {
        e.preventDefault();
        
        const commentId = document.getElementById('report-comment-id').value;
        const reason = document.getElementById('report-reason').value.trim();
        
        if (!commentId || !reason) {
            alert('Vui lòng điền lý do báo cáo');
            return;
        }
        
        fetch('<?= BASE_URL ?>comment/report', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                comment_id: commentId,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cảm ơn bạn đã báo cáo. Chúng tôi sẽ xem xét sớm');
                bootstrap.Modal.getInstance(document.getElementById('reportCommentModal')).hide();
                document.getElementById('report-form').reset();
            } else {
                alert('Lỗi: ' + (data.error || 'Không thể báo cáo bình luận'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    }

    window.showReportModal = function(commentId) {
        document.getElementById('report-comment-id').value = commentId;
        new bootstrap.Modal(document.getElementById('reportCommentModal')).show();
    };

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (date.toDateString() === today.toDateString()) {
            return 'Hôm nay lúc ' + date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        } else if (date.toDateString() === yesterday.toDateString()) {
            return 'Hôm qua';
        } else {
            return date.toLocaleDateString('vi-VN');
        }
    }
});
</script>

