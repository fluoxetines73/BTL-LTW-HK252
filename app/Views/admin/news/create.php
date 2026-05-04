<?php
$flash = $flash ?? null;
?>
<section class="panel">
    <h1>Đăng Tin Tức</h1>

    <?php if (!empty($flash)): ?>
        <div class="flash error"><?= htmlspecialchars((string)($flash['message'] ?? '')) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="field">
            <label for="title">Tiêu đề</label>
            <input id="title" type="text" name="title" required minlength="4">
        </div>

        <div class="field">
            <label for="highlight_title">Tiêu đề nổi bật (hiển thị ở trang Tin tức)</label>
            <input id="highlight_title" type="text" name="highlight_title" minlength="4" placeholder="Ví dụ: TÊN HÙNG ĐI XEM ANH HÙNG - SĂN LÙNG NỬA GIÁ">
        </div>

        <div class="field">
            <label for="category">Danh mục</label>
            <select id="category" name="category">
                <option value="tin-tuc">Tin tức</option>
                <option value="khuyen-mai">Khuyến mãi</option>
                <option value="su-kien">Sự kiện</option>
                <option value="phim-hay-thang">Phim hay tháng</option>
            </select>
        </div>

        <div class="field">
            <label for="content">Nội dung</label>
            <textarea id="content" name="content" rows="6" required minlength="10" placeholder="Nội dung tóm tắt hiển thị ở trang danh sách tin..."></textarea>
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
                <textarea id="detail_content" name="detail_content" rows="12" minlength="10" class="rich-editor-textarea"></textarea>
            </div>
        </div>

        <div class="field">
            <label for="image">Ảnh tin tức</label>
            <input id="image" type="file" name="image" accept="image/*" required>
        </div>

        <div class="field">
            <label class="featured-toggle">
                <input type="checkbox" name="featured" value="1">
                <span>Hiển thị trên slider</span>
            </label>
        </div>

        <div class="actions">
            <button type="submit" class="btn-submit">Đăng tin</button>
            <a href="<?= BASE_URL ?>admin/news" class="btn-back">Quay lại</a>
        </div>
    </form>
</section>

<?php
$extraScripts = ($extraScripts ?? '') . <<<'SCRIPT'
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
SCRIPT;
