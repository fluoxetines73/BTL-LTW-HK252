<?php
$authUser = $_SESSION['auth_user'] ?? null;
$isAdmin = !empty($authUser) && (($authUser['role'] ?? '') === 'admin');
$homeHref = $isAdmin ? (BASE_URL . 'admin/admin_dashboard') : BASE_URL;
?>

<header>
    <nav id="main-nav" class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-success" href="<?= $homeHref ?>">ABC Company</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>home/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>product">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>home/pricing">Bảng giá</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>news">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>home/faq">Hỏi đáp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>home/contact">Liên hệ</a>
                    </li>
                </ul>

                <div class="nav-account ms-auto d-flex align-items-center gap-3">
                    <?php if (!empty($authUser)): ?>
                        <?php if ($isAdmin): ?>
                            <a class="nav-link d-none d-lg-inline-flex" href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a>
                        <?php endif; ?>
                        <a class="nav-user-link d-inline-flex align-items-center gap-2" href="<?= BASE_URL ?>profile/index">
                            <?php
                                $avatarPath = (string)($authUser['avatar'] ?? '');
                                if ($avatarPath === '') {
                                    $avatarPath = 'public/uploads/avatars/default-avatar.svg';
                                } elseif (!str_starts_with($avatarPath, 'public/')) {
                                    $avatarPath = 'public/' . ltrim($avatarPath, '/');
                                }
                            ?>
                            <img class="nav-avatar" src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Avatar">
                            <span><?= htmlspecialchars((string)($authUser['name'] ?? 'Tài khoản')) ?></span>
                        </a>
                        <a class="btn btn-dark btn-sm" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a>
                    <?php else: ?>
                        <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>auth/login">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>