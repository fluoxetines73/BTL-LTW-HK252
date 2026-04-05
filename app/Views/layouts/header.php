<?php
$authUser = $_SESSION['auth_user'] ?? null;
$isAdmin = !empty($authUser) && (($authUser['role'] ?? '') === 'admin');
$homeHref = $isAdmin ? (BASE_URL . 'admin/admin_dashboard') : BASE_URL;
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-success" href="<?= $homeHref ?>">ABC Company</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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

                    <?php if (!empty($authUser)): ?>
                        <?php if ($isAdmin): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>profile/index"><?= htmlspecialchars((string)($authUser['name'] ?? 'Tài khoản')) ?></a>
                        </li>
                        <li class="nav-item ps-2">
                            <a class="btn btn-dark btn-sm" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ps-2">
                            <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>auth/login">Đăng nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>