<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>admin/admin_dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý Trang Giới thiệu</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fas fa-info-circle text-primary me-2"></i>Quản lý Trang Giới thiệu</h2>
    <a href="<?= BASE_URL ?>page/about" target="_blank" class="btn btn-outline-primary">
        <i class="fas fa-eye me-1"></i> Xem trang
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); endif; ?>

<form action="<?= BASE_URL ?>admin/about/update" method="POST" enctype="multipart/form-data">

    <!-- Hero Section -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-heading me-2"></i>Hero Section (Banner đầu trang)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Tiêu đề chính <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" name="hero_title" 
                           value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>" required>
                    <small class="text-muted">Tiêu đề lớn hiển thị trong banner</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Nhãn phụ (Kicker)</label>
                    <input type="text" class="form-control" name="hero_kicker" 
                           value="<?= htmlspecialchars($settings['hero_kicker'] ?? '') ?>">
                    <small class="text-muted">Dòng chữ nhỏ phía trên tiêu đề</small>
                </div>
            </div>
            <div class="alert alert-info mb-0">
                <i class="fas fa-magic me-2"></i>
                <strong>Hiệu ứng AOS:</strong> <code>fade-in</code> - Hiệu ứng mờ dần xuất hiện
            </div>
        </div>
    </div>

    <!-- Introduction Section -->
    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-paragraph me-2"></i>Giới thiệu</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề mục</label>
                <input type="text" class="form-control" name="intro_heading" 
                       value="<?= htmlspecialchars($settings['intro_heading'] ?? '') ?>">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đoạn văn 1</label>
                        <textarea class="form-control" name="intro_paragraph_1" rows="5"><?= 
                            htmlspecialchars($settings['intro_paragraph_1'] ?? '') 
                        ?></textarea>
                        <small class="text-muted">Hỗ trợ HTML: &lt;strong&gt;, &lt;em&gt;, &lt;br&gt;</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đoạn văn 2</label>
                        <textarea class="form-control" name="intro_paragraph_2" rows="4"><?= 
                            htmlspecialchars($settings['intro_paragraph_2'] ?? '') 
                        ?></textarea>
                    </div>
                    <div class="alert alert-light border">
                        <i class="fas fa-magic me-2"></i>
                        <strong>Hiệu ứng AOS:</strong> <code>fade-right</code> - Văn bản trượt từ trái
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hình ảnh</label>
                        <input type="file" class="form-control" name="intro_image" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Để trống nếu giữ ảnh hiện tại. Định dạng: JPG, PNG, WebP</small>
                        <?php if (!empty($settings['intro_image'])): ?>
                            <div class="mt-3">
                                <label class="form-label small text-muted">Ảnh hiện tại:</label>
                                <img src="<?= BASE_URL . $settings['intro_image'] ?>" 
                                     alt="Current" class="img-thumbnail d-block" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="alert alert-light border">
                        <i class="fas fa-magic me-2"></i>
                        <strong>Hiệu ứng AOS:</strong> <code>fade-left</code> - Hình ảnh trượt từ phải
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Hành trình phát triển</h5>
        </div>
        <div class="card-body">
            <div id="timeline-container">
                <?php if (!empty($timelineItems)): ?>
                    <?php foreach ($timelineItems as $index => $item): ?>
                    <div class="timeline-item-form border rounded p-3 mb-3" data-index="<?= $index ?>">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Cột mốc #<?= $index + 1 ?></h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTimelineItem(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Năm/Thờ kỳ</label>
                                <input type="text" class="form-control" 
                                       name="timeline[<?= $index ?>][year_label]" 
                                       value="<?= htmlspecialchars($item['year_label']) ?>" 
                                       placeholder="VD: 2011">
                            </div>
                            <div class="col-md-9">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control" rows="3" 
                                          name="timeline[<?= $index ?>][content]"><?= 
                                    htmlspecialchars($item['content']) 
                                ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="timeline[<?= $index ?>][id]" value="<?= $item['id'] ?>">
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-magic me-1"></i>
                                Hiệu ứng: <?= $index % 2 === 0 ? 'fade-right (trái)' : 'fade-left (phải)' ?>
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-success" onclick="addTimelineItem()">
                <i class="fas fa-plus me-1"></i> Thêm cột mốc
            </button>
            <div class="alert alert-light border mt-3 mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Các cột mốc sẽ hiển thị xen kẽ trái/phải. Hiệu ứng AOS tự động: <code>fade-right</code> / <code>fade-left</code>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Section -->
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Tầm nhìn & Sứ mệnh</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Vision Card -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100 bg-light">
                        <h6 class="text-primary mb-3"><i class="fas fa-globe me-1"></i>Tầm nhìn</h6>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="vision_title" 
                                   value="<?= htmlspecialchars($settings['vision_title'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon FontAwesome</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="<?= $settings['vision_icon'] ?? 'fas fa-globe' ?>"></i></span>
                                <input type="text" class="form-control" name="vision_icon" 
                                       value="<?= htmlspecialchars($settings['vision_icon'] ?? '') ?>"
                                       placeholder="VD: fa-solid fa-globe">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea class="form-control" name="vision_content" rows="4"><?= 
                                htmlspecialchars($settings['vision_content'] ?? '') 
                            ?></textarea>
                        </div>
                        <div class="alert alert-white border mb-0">
                            <small><i class="fas fa-magic me-1"></i>AOS: <code>zoom-in-up</code> (delay 100ms)</small>
                        </div>
                    </div>
                </div>
                <!-- Mission Card -->
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100 bg-light">
                        <h6 class="text-primary mb-3"><i class="fas fa-bullseye me-1"></i>Sứ mệnh</h6>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="mission_title" 
                                   value="<?= htmlspecialchars($settings['mission_title'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon FontAwesome</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="<?= $settings['mission_icon'] ?? 'fas fa-bullseye' ?>"></i></span>
                                <input type="text" class="form-control" name="mission_icon" 
                                       value="<?= htmlspecialchars($settings['mission_icon'] ?? '') ?>"
                                       placeholder="VD: fa-solid fa-bullseye">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea class="form-control" name="mission_content" rows="4"><?= 
                                htmlspecialchars($settings['mission_content'] ?? '') 
                            ?></textarea>
                        </div>
                        <div class="alert alert-white border mb-0">
                            <small><i class="fas fa-magic me-1"></i>AOS: <code>zoom-in-up</code> (delay 200ms)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê</h5>
        </div>
        <div class="card-body">
            <div class="row" id="stats-container">
                <?php if (!empty($statistics)): ?>
                    <?php foreach ($statistics as $index => $stat): ?>
                    <div class="col-md-6 col-lg-3 mb-3 stat-item" data-index="<?= $index ?>">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Thống kê #<?= $index + 1 ?></span>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStatItem(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Số liệu</label>
                                <input type="text" class="form-control" 
                                       name="stats[<?= $index ?>][number_display]" 
                                       value="<?= htmlspecialchars($stat['number_display']) ?>" 
                                       placeholder="VD: 80+">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Nhãn</label>
                                <input type="text" class="form-control" 
                                       name="stats[<?= $index ?>][label]" 
                                       value="<?= htmlspecialchars($stat['label']) ?>" 
                                       placeholder="VD: Cụm rạp toàn quốc">
                            </div>
                            <input type="hidden" name="stats[<?= $index ?>][id]" value="<?= $stat['id'] ?>">
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-magic me-1"></i>
                                    AOS: fade-up (delay <?= ($index + 1) * 100 ?>ms)
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-danger" onclick="addStatItem()">
                <i class="fas fa-plus me-1"></i> Thêm thống kê
            </button>
        </div>
    </div>

    <!-- Core Values Section -->
    <div class="card mb-4 border-secondary">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-gem me-2"></i>Giá trị nổi bật</h5>
        </div>
        <div class="card-body">
            <div class="row" id="values-container">
                <?php if (!empty($coreValues)): ?>
                    <?php foreach ($coreValues as $index => $value): ?>
                    <div class="col-md-6 mb-3 value-item" data-index="<?= $index ?>">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Giá trị #<?= $index + 1 ?></span>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeValueItem(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Icon FontAwesome</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="<?= $value['icon_class'] ?>"></i></span>
                                    <input type="text" class="form-control" 
                                           name="values[<?= $index ?>][icon_class]" 
                                           value="<?= htmlspecialchars($value['icon_class']) ?>"
                                           placeholder="VD: fa-solid fa-film">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Tiêu đề</label>
                                <input type="text" class="form-control" 
                                       name="values[<?= $index ?>][title]" 
                                       value="<?= htmlspecialchars($value['title']) ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Mô tả</label>
                                <textarea class="form-control" rows="2" 
                                          name="values[<?= $index ?>][description]"><?= 
                                    htmlspecialchars($value['description']) 
                                ?></textarea>
                            </div>
                            <input type="hidden" name="values[<?= $index ?>][id]" value="<?= $value['id'] ?>">
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-magic me-1"></i>
                                    AOS: flip-left (delay <?= ($index + 1) * 100 ?>ms)
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-secondary" onclick="addValueItem()">
                <i class="fas fa-plus me-1"></i> Thêm giá trị
            </button>
            <div class="alert alert-light border mt-3 mb-0">
                <a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">
                    <i class="fas fa-external-link-alt me-1"></i> Xem danh sách icon FontAwesome
                </a>
            </div>
        </div>
    </div>

    <!-- Leadership Section -->
    <div class="card mb-4 border-dark">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Đội ngũ lãnh đạo</h5>
        </div>
        <div class="card-body">
            <div class="row" id="leadership-container">
                <?php if (!empty($leadership)): ?>
                    <?php foreach ($leadership as $index => $member): ?>
                    <div class="col-md-6 mb-3 leader-item" data-index="<?= $index ?>">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Thành viên #<?= $index + 1 ?></span>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLeaderItem(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="form-label small">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           name="leadership[<?= $index ?>][name]" 
                                           value="<?= htmlspecialchars($member['name']) ?>" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Chức vụ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           name="leadership[<?= $index ?>][role]" 
                                           value="<?= htmlspecialchars($member['role']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Loại avatar</label>
                                <select class="form-select" name="leadership[<?= $index ?>][avatar_type]" 
                                        onchange="toggleAvatarInput(this, <?= $index ?>)">
                                    <option value="icon" <?= $member['avatar_type'] === 'icon' ? 'selected' : '' ?>>Icon FontAwesome</option>
                                    <option value="image" <?= $member['avatar_type'] === 'image' ? 'selected' : '' ?>>Hình ảnh</option>
                                </select>
                            </div>
                            <div class="mb-2 avatar-input-<?= $index ?>">
                                <?php if ($member['avatar_type'] === 'icon'): ?>
                                    <label class="form-label small">Icon class</label>
                                    <input type="text" class="form-control" 
                                           name="leadership[<?= $index ?>][avatar_value]" 
                                           value="<?= htmlspecialchars($member['avatar_value']) ?>"
                                           placeholder="VD: fa-solid fa-user-tie">
                                <?php else: ?>
                                    <label class="form-label small">Hình ảnh</label>
                                    <input type="file" class="form-control" 
                                           name="leadership_avatars[<?= $member['id'] ?>]" accept="image/*">
                                    <?php if ($member['avatar_value']): ?>
                                        <img src="<?= BASE_URL . $member['avatar_value'] ?>" 
                                             class="mt-2 img-thumbnail" style="max-height: 80px;">
                                        <input type="hidden" name="leadership[<?= $index ?>][existing_avatar]" 
                                               value="<?= $member['avatar_value'] ?>">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Trạng thái</label>
                                <select class="form-select" name="leadership[<?= $index ?>][status]">
                                    <option value="active" <?= $member['status'] === 'active' ? 'selected' : '' ?>>
                                        Đang hoạt động
                                    </option>
                                    <option value="retired" <?= $member['status'] === 'retired' ? 'selected' : '' ?>>
                                        Đã nghỉ hưu
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="leadership[<?= $index ?>][id]" value="<?= $member['id'] ?>">
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-magic me-1"></i>
                                    AOS: fade-up (delay <?= ($index + 1) * 100 ?>ms)
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline-dark" onclick="addLeaderItem()">
                <i class="fas fa-plus me-1"></i> Thêm thành viên
            </button>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="card bg-light">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><i class="fas fa-save me-2 text-success"></i>Lưu thay đổi</h5>
                    <p class="text-muted mb-0">Tất cả các thay đổi sẽ được áp dụng ngay lập tức lên trang Giới thiệu.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>admin/admin_dashboard" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left me-1"></i> Dashboard
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Lưu tất cả thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>

<style>
.timeline-item-form, .stat-item .border, .value-item .border, .leader-item .border {
    transition: all 0.2s ease;
}
.timeline-item-form:hover, .stat-item .border:hover, .value-item .border:hover, .leader-item .border:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
</style>
