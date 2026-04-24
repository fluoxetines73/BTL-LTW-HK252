<?php
$flash = $flash ?? null;
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

            <script>
            (function () {
                const editorWrap = document.querySelector('.rich-editor[data-target="detail_content"]');
                if (!editorWrap) return;

                const editor = editorWrap.querySelector('.rich-editor-content');
                const textarea = document.getElementById('detail_content');
                const form = editorWrap.closest('form');

                editor.innerHTML = textarea.value.trim() !== '' ? textarea.value : '<p></p>';

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
