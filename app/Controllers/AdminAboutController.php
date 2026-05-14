<?php
require_once ROOT . '/core/Controller.php';

class AdminAboutController extends Controller {
    
    public function __construct() {
        $this->middlewareAdmin();
    }

    /**
     * Main edit page - shows all sections in one form
     */
    public function index() {
        $settingsModel = $this->model('AboutPageSettings');
        $timelineModel = $this->model('AboutTimelineItems');
        $statsModel = $this->model('AboutStatistics');
        $valuesModel = $this->model('AboutCoreValues');
        $leadershipModel = $this->model('AboutLeadership');

        $settings = $settingsModel->getSettings();
        $timelineItems = $timelineModel->getAllItemsAdmin();
        $statistics = $statsModel->getAllItemsAdmin();
        $coreValues = $valuesModel->getAllItemsAdmin();
        $leadership = $leadershipModel->getAllItemsAdmin();

        $this->adminView('admin/about/index', 'about', [
            'title' => 'Quản lý Trang Giới thiệu',
            'settings' => $settings,
            'timelineItems' => $timelineItems,
            'statistics' => $statistics,
            'coreValues' => $coreValues,
            'leadership' => $leadership,
            'extraScripts' => $this->getDynamicFormScripts()
        ]);
    }

    /**
     * Handle form submission for all sections
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/about/index');
            return;
        }

        $userId = $_SESSION['auth_user']['id'] ?? null;
        
        if ($userId === null) {
            $_SESSION['error'] = 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.';
            $this->redirect('auth/login');
            return;
        }
        
        $hasError = false;

        try {
            $this->updateSettings($_POST, (int)$userId);

            if (isset($_POST['timeline'])) {
                $this->updateTimelineItems($_POST['timeline']);
            }

            if (isset($_POST['stats'])) {
                $this->updateStatistics($_POST['stats']);
            }

            if (isset($_POST['values'])) {
                $this->updateCoreValues($_POST['values']);
            }

            // Store mapping of temp IDs to actual database IDs
            $tempIdToDbId = [];
            if (isset($_POST['leadership'])) {
                $tempIdToDbId = $this->updateLeadership($_POST['leadership']);
            }

            $this->handleFileUploads($tempIdToDbId);

            $_SESSION['success'] = 'Cập nhật trang Giới thiệu thành công.';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            $hasError = true;
        }

        $this->redirect('admin/about/index');
    }

    /**
     * Update main settings
     */
    private function updateSettings(array $post, ?int $userId): void {
        $settingsModel = $this->model('AboutPageSettings');
        
        $data = [
            'hero_title' => trim($post['hero_title'] ?? ''),
            'hero_kicker' => trim($post['hero_kicker'] ?? ''),
            'intro_heading' => trim($post['intro_heading'] ?? ''),
            'intro_paragraph_1' => $this->sanitizeHtml($post['intro_paragraph_1'] ?? ''),
            'intro_paragraph_2' => $this->sanitizeHtml($post['intro_paragraph_2'] ?? ''),
            'vision_title' => trim($post['vision_title'] ?? ''),
            'vision_icon' => trim($post['vision_icon'] ?? ''),
            'vision_content' => $this->sanitizeHtml($post['vision_content'] ?? ''),
            'mission_title' => trim($post['mission_title'] ?? ''),
            'mission_icon' => trim($post['mission_icon'] ?? ''),
            'mission_content' => $this->sanitizeHtml($post['mission_content'] ?? '')
        ];

        if (!$settingsModel->updateSettings($data, $userId)) {
            throw new Exception('Không thể cập nhật cài đặt chính');
        }
    }

    /**
     * Update timeline items
     */
    private function updateTimelineItems(array $items): void {
        $timelineModel = $this->model('AboutTimelineItems');
        
        $existingIds = array_column($timelineModel->getAllItemsAdmin(), 'id');
        $processedIds = [];

        foreach ($items as $index => $item) {
            $data = [
                'year_label' => trim($item['year_label'] ?? ''),
                'content' => $this->sanitizeHtml($item['content'] ?? ''),
                'sort_order' => $index + 1,
                'is_active' => isset($item['is_active']) ? 1 : (isset($item['id']) ? 1 : 1)
            ];

            if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                $timelineModel->updateItem((int)$item['id'], $data);
                $processedIds[] = $item['id'];
            } else {
                if (!empty($data['year_label']) && !empty($data['content'])) {
                    $timelineModel->createItem($data);
                }
            }
        }

        foreach ($existingIds as $existingId) {
            if (!in_array($existingId, $processedIds)) {
                $timelineModel->deleteItem($existingId);
            }
        }
    }

    /**
     * Update statistics
     */
    private function updateStatistics(array $items): void {
        $statsModel = $this->model('AboutStatistics');
        
        $existingIds = array_column($statsModel->getAllItemsAdmin(), 'id');
        $processedIds = [];

        foreach ($items as $index => $item) {
            $data = [
                'number_display' => trim($item['number_display'] ?? ''),
                'label' => trim($item['label'] ?? ''),
                'sort_order' => $index + 1,
                'is_active' => 1
            ];

            if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                $statsModel->updateItem((int)$item['id'], $data);
                $processedIds[] = $item['id'];
            } else {
                if (!empty($data['number_display']) && !empty($data['label'])) {
                    $statsModel->createItem($data);
                }
            }
        }

        foreach ($existingIds as $existingId) {
            if (!in_array($existingId, $processedIds)) {
                $statsModel->deleteItem($existingId);
            }
        }
    }

    private function updateCoreValues(array $items): void {
        $valuesModel = $this->model('AboutCoreValues');
        
        $existingIds = array_column($valuesModel->getAllItemsAdmin(), 'id');
        $processedIds = [];

        foreach ($items as $index => $item) {
            $data = [
                'icon_class' => trim($item['icon_class'] ?? ''),
                'title' => trim($item['title'] ?? ''),
                'description' => $this->sanitizeHtml($item['description'] ?? ''),
                'sort_order' => $index + 1,
                'is_active' => 1
            ];

            if (!empty($item['id']) && in_array($item['id'], $existingIds)) {
                $valuesModel->updateItem((int)$item['id'], $data);
                $processedIds[] = $item['id'];
            } else {
                if (!empty($data['title']) && !empty($data['description'])) {
                    $valuesModel->createItem($data);
                }
            }
        }

        foreach ($existingIds as $existingId) {
            if (!in_array($existingId, $processedIds)) {
                $valuesModel->deleteItem($existingId);
            }
        }
    }

    private function updateLeadership(array $items): array {
        $leadershipModel = $this->model('AboutLeadership');
        
        $existingIds = array_column($leadershipModel->getAllItemsAdmin(), 'id');
        $processedIds = [];
        $tempIdToDbId = [];

        foreach ($items as $index => $item) {
            $data = [
                'name' => trim($item['name'] ?? ''),
                'role' => trim($item['role'] ?? ''),
                'avatar_type' => $item['avatar_type'] ?? 'icon',
                'avatar_value' => $item['avatar_type'] === 'image' 
                    ? ($item['existing_avatar'] ?? '') 
                    : trim($item['avatar_value'] ?? ''),
                'avatar_color_class' => 'team-avatar-' . (($index % 4) + 1),
                'status' => $item['status'] ?? 'active',
                'sort_order' => $index + 1,
                'is_active' => 1
            ];

            if (!empty($item['id']) && in_array((int)$item['id'], $existingIds)) {
                $leadershipModel->updateItem((int)$item['id'], $data);
                $processedIds[] = (int)$item['id'];
            } else {
                if (!empty($data['name']) && !empty($data['role'])) {
                    $newId = $leadershipModel->createItem($data);
                    // Map the form index to the new database ID for file uploads
                    $tempIdToDbId[$index] = $newId;
                }
            }
        }

        foreach ($existingIds as $existingId) {
            if (!in_array($existingId, $processedIds)) {
                $leadershipModel->deleteItem($existingId);
            }
        }

        return $tempIdToDbId;
    }

    /**
     * Handle file uploads for intro image and leadership avatars
     * @param array $tempIdToDbId Mapping of form indices to database IDs for newly created members
     */
    private function handleFileUploads(array $tempIdToDbId = []): void {
        $uploadDir = ROOT . '/public/uploads/about/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (isset($_FILES['intro_image']) && $_FILES['intro_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['intro_image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Chỉ chấp nhận file ảnh JPG, PNG, hoặc WebP');
            }

            $filename = 'intro_' . time() . '_' . basename($file['name']);
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $settingsModel = $this->model('AboutPageSettings');
                $settingsModel->updateIntroImage('public/uploads/about/' . $filename);
            }
        }

        if (isset($_FILES['leadership_avatars'])) {
            $leadershipModel = $this->model('AboutLeadership');
            
            foreach ($_FILES['leadership_avatars']['tmp_name'] as $id => $tmpName) {
                if ($_FILES['leadership_avatars']['error'][$id] === UPLOAD_ERR_OK) {
                    $fileType = $_FILES['leadership_avatars']['type'][$id];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        continue;
                    }

                    // Map the ID: if it's a numeric string, use it directly
                    // If it's a temp ID (starts with 'new_'), look up the actual DB ID
                    $dbId = $id;
                    if (is_string($id) && strpos($id, 'new_') === 0) {
                        // Extract the index from temp ID format: new_timestamp_index
                        $parts = explode('_', $id);
                        $index = end($parts);
                        if (isset($tempIdToDbId[$index])) {
                            $dbId = $tempIdToDbId[$index];
                        } else {
                            // Skip if we can't map this temp ID
                            continue;
                        }
                    }

                    $filename = 'avatar_' . $dbId . '_' . time() . '.jpg';
                    $filepath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($tmpName, $filepath)) {
                        $leadershipModel->updateAvatarImage((int)$dbId, 'public/uploads/about/' . $filename);
                    }
                }
            }
        }
    }

    private function sanitizeHtml(string $content): string {
        $allowedTags = '<br><p><strong><em><b><i><ul><ol><li><span>';
        return strip_tags($content, $allowedTags);
    }

    private function getDynamicFormScripts(): string {
        return <<<'SCRIPT'
<script>
function addTimelineItem() {
    const container = document.getElementById('timeline-container');
    const index = container.children.length;
    const html = `
        <div class="timeline-item-form border rounded p-3 mb-3" data-index="${index}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Cột mốc #${index + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTimelineItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Năm/Thời kỳ</label>
                    <input type="text" class="form-control" name="timeline[${index}][year_label]" placeholder="VD: 2011">
                </div>
                <div class="col-md-9">
                    <label class="form-label">Nội dung</label>
                    <textarea class="form-control" rows="2" name="timeline[${index}][content]"></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeTimelineItem(btn) {
    btn.closest('.timeline-item-form').remove();
    reindexItems('timeline-item-form', 'Cột mốc');
}

function addStatItem() {
    const container = document.getElementById('stats-container');
    const index = container.children.length;
    const html = `
        <div class="col-md-6 mb-3 stat-item" data-index="${index}">
            <div class="border rounded p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Thống kê #${index + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStatItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label class="form-label small">Số liệu</label>
                        <input type="text" class="form-control" name="stats[${index}][number_display]" placeholder="VD: 80+">
                    </div>
                    <div class="col-8">
                        <label class="form-label small">Nhãn</label>
                        <input type="text" class="form-control" name="stats[${index}][label]" placeholder="VD: Cụm rạp toàn quốc">
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeStatItem(btn) {
    btn.closest('.stat-item').remove();
    reindexItems('stat-item', 'Thống kê');
}

function addValueItem() {
    const container = document.getElementById('values-container');
    const index = container.children.length;
    const html = `
        <div class="col-md-6 mb-3 value-item" data-index="${index}">
            <div class="border rounded p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Giá trị #${index + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeValueItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Icon FontAwesome</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-icons"></i></span>
                        <input type="text" class="form-control" name="values[${index}][icon_class]" placeholder="VD: fa-solid fa-film">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Tiêu đề</label>
                    <input type="text" class="form-control" name="values[${index}][title]">
                </div>
                <div class="mb-2">
                    <label class="form-label small">Mô tả</label>
                    <textarea class="form-control" rows="2" name="values[${index}][description]"></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeValueItem(btn) {
    btn.closest('.value-item').remove();
    reindexItems('value-item', 'Giá trị');
}

function addLeaderItem() {
    const container = document.getElementById('leadership-container');
    const index = container.children.length;
    const tempId = 'new_' + Date.now() + '_' + index;
    const html = `
        <div class="col-md-6 mb-3 leader-item" data-index="${index}" data-temp-id="${tempId}">
            <div class="border rounded p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Thành viên #${index + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLeaderItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Họ tên</label>
                        <input type="text" class="form-control" name="leadership[${index}][name]">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Chức vụ</label>
                        <input type="text" class="form-control" name="leadership[${index}][role]">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Loại avatar</label>
                    <select class="form-select" name="leadership[${index}][avatar_type]" onchange="toggleAvatarInput(this, '${tempId}')">
                        <option value="icon">Icon FontAwesome</option>
                        <option value="image">Hình ảnh</option>
                    </select>
                </div>
                <div class="mb-2 avatar-input-${tempId}">
                    <label class="form-label small">Icon class</label>
                    <input type="text" class="form-control" name="leadership[${index}][avatar_value]" placeholder="VD: fa-solid fa-user-tie">
                </div>
                <div class="mb-2">
                    <label class="form-label small">Trạng thái</label>
                    <select class="form-select" name="leadership[${index}][status]">
                        <option value="active">Đang hoạt động</option>
                        <option value="retired">Đã nghỉ hưu</option>
                    </select>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function removeLeaderItem(btn) {
    btn.closest('.leader-item').remove();
    reindexItems('leader-item', 'Thành viên');
}

function toggleAvatarInput(select, idOrTempId) {
    const container = select.closest('.leader-item').querySelector(`.avatar-input-${idOrTempId}`);
    if (select.value === 'image') {
        container.innerHTML = `
            <label class="form-label small">Hình ảnh</label>
            <input type="file" class="form-control" name="leadership_avatars[${idOrTempId}]" accept="image/*">
        `;
    } else {
        container.innerHTML = `
            <label class="form-label small">Icon class</label>
            <input type="text" class="form-control" name="leadership[${select.closest('.leader-item').dataset.index}][avatar_value]" placeholder="VD: fa-solid fa-user-tie">
        `;
    }
}

function reindexItems(selectorClass, labelPrefix) {
    document.querySelectorAll('.' + selectorClass).forEach((el, i) => {
        const label = el.querySelector('.fw-bold, h6');
        if (label) {
            label.textContent = `${labelPrefix} #${i + 1}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = e.target.closest('.mb-3').querySelector('img');
                if (preview) {
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    });
});
</script>
SCRIPT;
    }
}
