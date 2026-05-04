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

<!-- Bulk Action Bar -->
<div id="bulk-action-bar" class="card shadow-sm border-0 mb-3" style="display: none;">
    <div class="card-body py-2 d-flex align-items-center gap-2 flex-wrap">
        <span class="fw-semibold text-muted"><i class="fas fa-tasks me-1"></i>Hành động hàng loạt:</span>
        <form id="bulk-delete-form" method="POST" action="<?= BASE_URL ?>admin/faq/bulkDelete" class="d-inline">
            <input type="hidden" name="selected_ids" id="bulk-delete-ids" value="">
            <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" disabled onclick="confirmBulkDelete();">
                <i class="fas fa-trash me-1"></i>Xóa đã chọn
            </button>
        </form>
        <form id="bulk-status-form" method="POST" action="<?= BASE_URL ?>admin/faq/bulkUpdateStatus" class="d-inline">
            <input type="hidden" name="selected_ids" id="bulk-status-ids" value="">
            <div class="input-group input-group-sm" style="width: auto;">
                <select name="status" id="bulk-status-select" class="form-select form-select-sm">
                    <option value="active">Kích hoạt</option>
                    <option value="inactive">Vô hiệu hóa</option>
                </select>
                <button type="button" id="bulk-status-btn" class="btn btn-outline-primary btn-sm" disabled onclick="submitBulkStatus();">
                    <i class="fas fa-sync-alt me-1"></i>Áp dụng
                </button>
            </div>
        </form>
        <span id="selected-count" class="text-muted ms-auto" style="font-size: 0.875rem;"></span>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="admin-table-header">
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
                        <th class="text-center" style="width: 40px;">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this);">
                        </th>
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
                        <th class="sortable" style="width: 18%;">
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
                            <td class="text-center">
                                <input type="checkbox" class="faq-checkbox" value="<?= (int)($faq['id'] ?? 0) ?>" onchange="updateBulkActions();">
                            </td>
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
                            <td colspan="7" class="text-center py-4 text-muted">
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

<?php
$extraScripts = ($extraScripts ?? '') . <<<'SCRIPT'
<script>
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.faq-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.faq-checkbox');
    const checkedCount = document.querySelectorAll('.faq-checkbox:checked').length;
    const totalCheckboxes = checkboxes.length;

    // Update select-all checkbox state
    const selectAll = document.getElementById('select-all');
    selectAll.checked = totalCheckboxes > 0 && checkedCount === totalCheckboxes;

    // Show/hide bulk action bar
    const actionBar = document.getElementById('bulk-action-bar');
    actionBar.style.display = checkedCount > 0 ? 'block' : 'none';

    // Enable/disable action buttons
    const deleteBtn = document.getElementById('bulk-delete-btn');
    const statusBtn = document.getElementById('bulk-status-btn');
    deleteBtn.disabled = checkedCount === 0;
    statusBtn.disabled = checkedCount === 0;

    // Update selected count text
    const countSpan = document.getElementById('selected-count');
    countSpan.textContent = checkedCount > 0 ? 'Đã chọn ' + checkedCount + ' mục' : '';

    // Update hidden inputs with selected IDs
    const selectedIds = Array.from(document.querySelectorAll('.faq-checkbox:checked')).map(cb => cb.value);
    document.getElementById('bulk-delete-ids').value = selectedIds.join(',');
    document.getElementById('bulk-status-ids').value = selectedIds.join(',');
}

function confirmBulkDelete() {
    const checkedCount = document.querySelectorAll('.faq-checkbox:checked').length;
    if (checkedCount === 0) {
        alert('Vui lòng chọn ít nhất một câu hỏi để xóa.');
        return;
    }
    if (!confirm('Bạn có chắc chắn muốn xóa ' + checkedCount + ' câu hỏi đã chọn? Hành động này không thể hoàn tác!')) {
        return;
    }
    document.getElementById('bulk-delete-form').submit();
}

function submitBulkStatus() {
    const checkedCount = document.querySelectorAll('.faq-checkbox:checked').length;
    if (checkedCount === 0) {
        alert('Vui lòng chọn ít nhất một câu hỏi để cập nhật.');
        return;
    }
    document.getElementById('bulk-status-form').submit();
}
</script>
SCRIPT;