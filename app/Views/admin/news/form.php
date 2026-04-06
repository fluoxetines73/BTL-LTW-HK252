<section class="panel" style="max-width: 900px; margin: 0 auto;">

    <h1><?= !empty($isEdit) ? 'Sửa bài viết' : 'Thêm bài viết' ?></h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= ($flash['type'] ?? 'info') === 'success' ? 'success' : 'danger' ?>">
            <?= htmlspecialchars((string)($flash['message'] ?? '')) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label class="form-label" for="title">Tiêu đề</label>
            <input id="title" name="title" class="form-control" required value="<?= htmlspecialchars((string)($article['title'] ?? '')) ?>">
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label" for="category">Danh mục</label>
                <select id="category" name="category" class="form-select" required>
                    <?php $selectedCategory = (string)($article['category'] ?? 'tin-tuc'); ?>
                    <?php foreach (($categories ?? []) as $key => $label): ?>
                        <option value="<?= htmlspecialchars((string)$key) ?>" <?= $selectedCategory === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string)$label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="status">Trạng thái</label>
                <?php $selectedStatus = (string)($article['status'] ?? 'draft'); ?>
                <select id="status" name="status" class="form-select" required>
                    <option value="draft" <?= $selectedStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $selectedStatus === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="content">Nội dung</label>
            <textarea id="content" name="content" class="form-control" rows="12" required><?= htmlspecialchars((string)($article['content'] ?? '')) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label" for="image">Ảnh đại diện (tùy chọn)</label>
            <input id="image" type="file" name="image" class="form-control" accept="image/*">
            <?php if (!empty($article['image'])): ?>
                <p class="mt-2 mb-0 small text-muted">Ảnh hiện tại:</p>
                <img src="<?= BASE_URL . 'public/' . ltrim((string)$article['image'], '/') ?>" alt="Ảnh bài viết" style="max-width: 240px; height: auto; margin-top: 6px; border-radius: 8px;">
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit"><?= !empty($isEdit) ? 'Lưu thay đổi' : 'Tạo bài viết' ?></button>
            <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>admin/news">Quay lại</a>
        </div>
    </form>
</section>
