<?php
$flash = $flash ?? null;
$article = $article ?? [];
$currentImage = trim((string)($article['image'] ?? ''));
if ($currentImage !== '') {
    if (str_starts_with($currentImage, 'public/')) {
        $currentImageUrl = BASE_URL . $currentImage;
    } elseif (str_starts_with($currentImage, 'uploads/')) {
        $currentImageUrl = BASE_URL . 'public/' . ltrim($currentImage, '/');
    } elseif (preg_match('#^https?://#i', $currentImage) === 1) {
        $currentImageUrl = $currentImage;
    } else {
        $currentImageUrl = BASE_URL . 'public/' . ltrim($currentImage, '/');
    }
} else {
    $currentImageUrl = BASE_URL . 'public/images/about/about-6.png';
}
?>
<div class="admin-container">
    <aside class="sidebar">
        <div class="sidebar-logo"><h3><i class="fas fa-cogs"></i> Admin</h3></div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>admin/users"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
            <li><a href="<?= BASE_URL ?>admin/products"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
            <li><a href="<?= BASE_URL ?>admin/news" class="active"><i class="fas fa-newspaper"></i> Quản lý Tin tức</a></li>
            <li><a href="<?= BASE_URL ?>profile/edit"><i class="fas fa-user-edit"></i> Hồ sơ cá nhân</a></li>
            <li><a href="<?= BASE_URL ?>auth/logout" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?');"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <section class="panel">
            <h1>Sửa Tin Tức</h1>

            <?php if (!empty($flash)): ?>
                <div class="flash error"><?= htmlspecialchars((string)($flash['message'] ?? '')) ?></div>
            <?php endif; ?>

            <div class="preview">
                <strong>Ảnh hiện tại:</strong><br>
                <img src="<?= htmlspecialchars($currentImageUrl) ?>" alt="Ảnh tin hiện tại">
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="field">
                    <label for="title">Tiêu đề</label>
                    <input id="title" type="text" name="title" required minlength="4" value="<?= htmlspecialchars((string)($article['title'] ?? '')) ?>">
                </div>

                <div class="field">
                    <label for="highlight_title">Tiêu đề nổi bật (hiển thị ở trang Tin tức)</label>
                    <input id="highlight_title" type="text" name="highlight_title" minlength="4" value="<?= htmlspecialchars((string)($article['highlight_title'] ?? '')) ?>" placeholder="Ví dụ: TÊN HÙNG ĐI XEM ANH HÙNG - SĂN LÙNG NỬA GIÁ">
                </div>

                <div class="field">
                    <label for="category">Danh mục</label>
                    <select id="category" name="category">
                        <option value="tin-tuc" <?= (($article['category'] ?? '') === 'tin-tuc') ? 'selected' : '' ?>>Tin tức</option>
                        <option value="khuyen-mai" <?= (($article['category'] ?? '') === 'khuyen-mai') ? 'selected' : '' ?>>Khuyến mãi</option>
                        <option value="su-kien" <?= (($article['category'] ?? '') === 'su-kien') ? 'selected' : '' ?>>Sự kiện</option>
                        <option value="phim-hay-thang" <?= (($article['category'] ?? '') === 'phim-hay-thang') ? 'selected' : '' ?>>Phim hay tháng</option>
                    </select>
                </div>

                <div class="field">
                    <label for="content">Nội dung</label>
                    <textarea id="content" name="content" rows="6" required minlength="10"><?= htmlspecialchars((string)($article['content'] ?? '')) ?></textarea>
                </div>

                <div class="field">
                    <label for="detail_content">Nội dung chi tiết (quản lý riêng cho trang detail)</label>
                    <div class="rich-editor" data-target="detail_content">
                        <div class="rich-editor-toolbar">
                            <button type="button" class="editor-btn" data-command="bold"><strong>B</strong></button>
                            <button type="button" class="editor-btn" data-command="italic"><em>I</em></button>
                            <button type="button" class="editor-btn" data-command="insertUnorderedList">• List</button>
                            <button type="button" class="editor-btn" data-command="insertOrderedList">1. List</button>
                            <button type="button" class="editor-btn" data-command="formatBlock" data-value="H2">H2</button>
                            <button type="button" class="editor-btn" data-command="formatBlock" data-value="H3">H3</button>
                            <button type="button" class="editor-btn" data-command="formatBlock" data-value="P">P</button>
                            <label class="editor-color-wrap">Màu
                                <input type="color" class="editor-color" value="#e71a0f">
                            </label>
                        </div>
                        <div id="detail_editor" class="rich-editor-content" contenteditable="true" data-placeholder="Nhập nội dung chi tiết... có thể tạo tiêu đề, in đậm, đổi màu, xuống dòng giống mẫu."></div>
                        <textarea id="detail_content" name="detail_content" rows="12" minlength="10" class="rich-editor-textarea"><?= htmlspecialchars((string)($article['detail_content'] ?? '')) ?></textarea>
                    </div>
                </div>

                <div class="field">
                    <label for="image">Đổi ảnh tin tức (không bắt buộc)</label>
                    <input id="image" type="file" name="image" accept="image/*">
                </div>

                <div class="field">
                    <label class="featured-toggle">
                        <input type="checkbox" name="featured" value="1" <?= ($article['featured'] ?? 0) ? 'checked' : '' ?>>
                        <span>Hiển thị trên slider</span>
                    </label>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-submit">Lưu thay đổi</button>
                    <a href="<?= BASE_URL ?>admin/news" class="btn-back">Quay lại</a>
                </div>
            </form>

            <!-- Load DOMPurify for HTML sanitization -->
            <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>

            <script>
            (function () {
                const editorWrap = document.querySelector('.rich-editor[data-target="detail_content"]');
                if (!editorWrap) return;

                const editor = editorWrap.querySelector('.rich-editor-content');
                const textarea = document.getElementById('detail_content');
                const form = editorWrap.closest('form');

                // Sanitize HTML content before inserting into DOM to prevent XSS
                const sanitizeHTML = (html) => {
                    if (typeof DOMPurify !== 'undefined') {
                        return DOMPurify.sanitize(html);
                    }
                    // Fallback: create a temporary div and use textContent to strip tags
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    return temp.innerHTML; // Browser will auto-escape dangerous content
                };

                const initialHTML = textarea.value.trim() !== '' ? textarea.value : '<p></p>';
                editor.innerHTML = sanitizeHTML(initialHTML);

                editorWrap.querySelectorAll('.editor-btn').forEach((btn) => {
                    btn.addEventListener('click', function () {
                        const command = this.getAttribute('data-command');
                        const value = this.getAttribute('data-value');
                        editor.focus();
                        document.execCommand(command, false, value || null);
                    });
                });

                const colorInput = editorWrap.querySelector('.editor-color');
                if (colorInput) {
                    colorInput.addEventListener('input', function () {
                        editor.focus();
                        document.execCommand('foreColor', false, this.value);
                    });
                }

                form.addEventListener('submit', function () {
                    textarea.value = editor.innerHTML.trim();
                });
            })();
            </script>
        </section>
    </main>
</div>
