/**
 * Admin Comments Management JavaScript
 */

// Modal event listener
document.addEventListener('shown.bs.modal', function (event) {
    if (event.target.id === 'commentModal') {
        const button = event.relatedTarget;
        const commentData = JSON.parse(button.getAttribute('data-comment'));
        
        // Populate modal
        document.getElementById('modal-author').textContent = commentData.username || 'N/A';
        document.getElementById('modal-news-title').innerHTML = `
            <a href="${BASE_URL}news/detail/${commentData.news_id}" target="_blank" class="text-decoration-none">
                ${escapeHtml(commentData.news_title)}
            </a>
        `;
        document.getElementById('modal-content').textContent = commentData.content;
        
        // Show report section if reported
        const reportSection = document.getElementById('modal-report-section');
        if (commentData.is_reported) {
            reportSection.style.display = 'block';
            document.getElementById('modal-report-reason').textContent = commentData.report_reason || 'Không có lý do';
            document.getElementById('modal-report-count').textContent = commentData.report_count + ' báo cáo';
        } else {
            reportSection.style.display = 'none';
        }
    }
});

// Escape HTML function
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Toggle all checkboxes in a table
function toggleAllCheckboxes(headerCheckbox, tableId) {
    const table = document.getElementById(tableId);
    const checkboxes = table.querySelectorAll('tbody .comment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = headerCheckbox.checked;
    });
    updateDeleteButton();
}

// Update delete button state
function updateDeleteButton() {
    const allChecked = document.querySelectorAll('tbody .comment-checkbox:checked');
    const deleteButtons = {
        all: document.getElementById('deleteSelectedBtn'),
        pending: document.getElementById('deletePendingBtn'),
        reported: document.getElementById('deleteReportedBtn')
    };
    
    // Update all comments tab
    if (deleteButtons.all) {
        if (allChecked.length > 0) {
            deleteButtons.all.style.display = 'block';
            document.getElementById('deleteCountText').textContent = `Xoá (${allChecked.length})`;
        } else {
            deleteButtons.all.style.display = 'none';
        }
    }
}

// Delete single comment
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        const btn = e.target.closest('.btn-delete');
        const commentId = btn.getAttribute('data-comment-id');
        
        if (confirm('Bạn chắc chắn muốn xoá bình luận này?')) {
            deleteComment(commentId, btn);
        }
    }
    
    // Approve comment
    if (e.target.closest('.btn-approve')) {
        const btn = e.target.closest('.btn-approve');
        const commentId = btn.getAttribute('data-comment-id');
        approveComment(commentId, btn);
    }
});

// Delete single comment
function deleteComment(commentId, btn) {
    fetch(`${BASE_URL}admin/comments/delete/${commentId}`, {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row with animation
            const row = btn.closest('tr');
            row.style.opacity = '0';
            row.style.transition = 'opacity 0.3s ease';
            setTimeout(() => row.remove(), 300);
            
            showToast('✓ ' + data.message, 'success');
        } else {
            showToast('✗ ' + (data.error || 'Lỗi khi xoá'), 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('✗ Có lỗi xảy ra', 'error');
    });
}

// Delete multiple comments
document.addEventListener('click', function(e) {
    if (e.target.closest('#deleteSelectedBtn') || 
        e.target.closest('#deletePendingBtn') || 
        e.target.closest('#deleteReportedBtn')) {
        
        const btn = e.target.closest('button[id$="Btn"]');
        const table = btn.closest('.tab-pane').querySelector('table');
        const checkedIds = Array.from(table.querySelectorAll('tbody .comment-checkbox:checked'))
            .map(cb => parseInt(cb.value));
        
        if (checkedIds.length === 0) {
            showToast('✗ Vui lòng chọn ít nhất 1 bình luận', 'warning');
            return;
        }
        
        if (confirm(`Xoá ${checkedIds.length} bình luận?`)) {
            deleteMultipleComments(checkedIds, btn);
        }
    }
});

function deleteMultipleComments(ids, btn) {
    fetch(`${BASE_URL}admin/comments/deleteMultiple`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove rows
            ids.forEach(id => {
                const row = document.querySelector(`tr[data-comment-id="${id}"]`);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => row.remove(), 300);
                }
            });
            
            // Hide delete button
            btn.style.display = 'none';
            showToast('✓ ' + data.message, 'success');
        } else {
            showToast('✗ ' + (data.error || 'Lỗi khi xoá'), 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('✗ Có lỗi xảy ra', 'error');
    });
}

// Approve comment
function approveComment(commentId, btn) {
    fetch(`${BASE_URL}admin/comments/approve/${commentId}`, {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update badge
            const row = btn.closest('tr');
            const statusCell = row.querySelector('td:nth-child(5)');
            if (statusCell) {
                statusCell.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Đã duyệt</span>';
            }
            btn.remove();
            showToast('✓ ' + data.message, 'success');
        } else {
            showToast('✗ ' + (data.error || 'Lỗi'), 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showToast('✗ Có lỗi xảy ra', 'error');
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        max-width: 400px;
        z-index: 9999;
        min-width: 300px;
    `;
    
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto dismiss after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
    return container;
}

// Toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        max-width: 400px;
        z-index: 9999;
        min-width: 300px;
    `;
    
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto dismiss after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
    return container;
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + (data.error || 'Không thể duyệt bình luận'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function handleDeleteComment(e) {
    e.preventDefault();
    const commentId = e.target.closest('button').dataset.commentId;

    if (!confirm('Bạn có chắc chắn muốn xoá bình luận này? Hành động này không thể hoàn tác.')) {
        return;
    }

    fetch(`${BASE_URL}admin/comment/delete/${commentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + (data.error || 'Không thể xoá bình luận'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
