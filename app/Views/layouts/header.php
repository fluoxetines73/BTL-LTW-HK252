<?php
$authUser = $_SESSION['auth_user'] ?? null;
$isAdmin = !empty($authUser) && (($authUser['role'] ?? '') === 'admin');
$homeHref = BASE_URL;
?>
<header>
    <nav class="navbar navbar-expand-md navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $homeHref ?>">
                <img src="<?= BASE_URL ?>public/images/logo/cgvlogo.svg" alt="CGV Cinema" height="40">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cgvNavbar" aria-controls="cgvNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="cgvNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="moviesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Phim
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="moviesDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>movies/current">Phim Đang Chiếu</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>movies/coming">Phim Sắp Chiếu</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="theatersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Rạp CGV
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="theatersDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>theaters/all">Tất Cả</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>theaters/special">Đặc Biệt</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>theaters/threeD">3D</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="newsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tin mới & Ưu đãi
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="newsDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>news/promotions">Promotions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Thông tin & Hỗ trợ
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="infoDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/about">About</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/faq">FAQ</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/contact">Contact</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/privacy">Privacy</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/terms">Terms</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>home/regulations">Regulations</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <?php if (!empty($authUser)): ?>
                        <?php if ($isAdmin): ?>
                            <a class="btn btn-outline-secondary btn-sm me-2" href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a>
                        <?php endif; ?>
                        <div class="dropdown">
                            <a class="btn text-white" style="background-color: #E71A0F;" href="#" id="userMenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuDropdown">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>profile/index"><?= htmlspecialchars((string)($authUser['name'] ?? 'Tài khoản')) ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn text-white" style="background-color: #E71A0F;" href="<?= BASE_URL ?>auth/login">
                            <i class="fa-solid fa-user"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
