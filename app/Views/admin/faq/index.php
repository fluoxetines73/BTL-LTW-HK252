<?php /* DEBUG: Count rows before table */ ?>
<div class="alert alert-warning">
    <strong>DEBUG:</strong> Total FAQs in array: <?= count($faqs ?? []) ?> |
    mbstring loaded: <?= extension_loaded('mbstring') ? 'YES' : 'NO' ?> |
    First ID: <?= $faqs[0]['id'] ?? 'N/A' ?>
</div>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý FAQ</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fas fa-question-circle text-primary me-2"></i>Quản lý FAQ</h2>
    <a href="<?= BASE_URL ?>admin/faq/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> Thêm Câu Hỏi Mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <?php
                        // Helper function to generate sort URL
                        function getSortUrl($column, $currentSortBy, $currentSortOrder) {
                            $newOrder = ($currentSortBy === $column && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                            return BASE_URL . 'admin/faq/index?sort=' . $column . '&order=' . $newOrder;
                        }
                        
                        // Helper function to get sort icon
                        function getSortIcon($column, $currentSortBy, $currentSortOrder) {
                            if ($currentSortBy !== $column) {
                                return '<i class="fas fa-sort text-muted sort-icon"></i>';
                            }
                            return ($currentSortOrder === 'asc') 
                                ? '<i class="fas fa-sort-up sort-icon"></i>' 
                                : '<i class="fas fa-sort-down sort-icon"></i>';
                        }
                        ?>
                        <th class="text-center sortable" style="width: 50px;">
                            <a href="<?= getSortUrl('id', $sortBy ?? null, $sortOrder ?? 'asc') ?>">
                                ID <?= getSortIcon('id', $sortBy ?? null, $sortOrder ?? 'asc') ?>
                            </a>
                        </th>
                        <th class="sortable" style="width: 35%;">
                            <a href="<?= getSortUrl('question', $sortBy ?? null, $sortOrder ?? 'asc') ?>">
                                Câu hỏi <?= getSortIcon('question', $sortBy ?? null, $sortOrder ?? 'asc') ?>
                            </a>
                        </th>
                        <th class="sortable">
                            <a href="<?= getSortUrl('category', $sortBy ?? null, $sortOrder ?? 'asc') ?>">
                                Danh mục <?= getSortIcon('category', $sortBy ?? null, $sortOrder ?? 'asc') ?>
                            </a>
                        </th>
                        <th class="text-center sortable" style="width: 80px;">
                            <a href="<?= getSortUrl('sort_order', $sortBy ?? null, $sortOrder ?? 'asc') ?>">
                                Thứ tự <?= getSortIcon('sort_order', $sortBy ?? null, $sortOrder ?? 'asc') ?>
                            </a>
                        </th>
                        <th class="text-center sortable" style="width: 100px;">
                            <a href="<?= getSortUrl('status', $sortBy ?? null, $sortOrder ?? 'asc') ?>">
                                Trạng thái <?= getSortIcon('status', $sortBy ?? null, $sortOrder ?? 'asc') ?>
                            </a>
                        </th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($faqs)): ?>
                        <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td class="text-center"><?= (int)($faq['id'] ?? 0) ?></td>
                            <td><?= htmlspecialchars(substr($faq['question'] ?? '', 0, 80)) ?><?= strlen($faq['question'] ?? '') > 80 ? '...' : '' ?></td>
                            <td><span class="badge bg-info"><?= htmlspecialchars($faq['category'] ?? 'N/A') ?></span></td>
                            <td class="text-center"><?= (int)($faq['sort_order'] ?? 0) ?></td>
                            <td class="text-center">
                                <?php if (($faq['status'] ?? '') === 'active'): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>admin/faq/edit/<?= $faq['id'] ?>" class="btn btn-outline-primary" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/faq/delete/<?= $faq['id'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?');"
                                       title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>Chưa có câu hỏi nào. Hãy thêm câu hỏi đầu tiên!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php /* DEBUG: Safe render without mb_substr to prove data exists */ ?>
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-warning text-dark">
        <strong>DEBUG TABLE (using substr instead of mb_substr):</strong>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Question (substr)</th><th>Category</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?= (int)$faq['id'] ?></td>
                        <td><?= htmlspecialchars(substr($faq['question'], 0, 80)) ?></td>
                        <td><?= htmlspecialchars($faq['category']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>