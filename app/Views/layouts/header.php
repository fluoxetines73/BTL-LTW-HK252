<?php
$authUser = $_SESSION['auth_user'] ?? null;
$isAdmin = !empty($authUser) && (($authUser['role'] ?? '') === 'admin');
$homeHref = $isAdmin ? (BASE_URL . 'admin/admin_dashboard') : BASE_URL;
?>

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
    <nav id="main-nav" class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-4 py-4 md:flex-row md:items-center md:justify-between md:px-6 lg:px-8">
        <a class="text-lg font-bold tracking-tight text-teal-700" href="<?= $homeHref ?>">ABC Company</a>

        <div class="nav-links flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-medium text-slate-700">
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>">Trang chủ</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>home/about">Giới thiệu</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>product">Sản phẩm</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>home/pricing">Bảng giá</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>news">Tin tức</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>home/faq">Hỏi đáp</a>
            <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>home/contact">Liên hệ</a>

            <?php if (!empty($authUser)): ?>
                <?php if ($isAdmin): ?>
                    <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>admin/admin_dashboard">Dashboard</a>
                <?php endif; ?>
                <a class="transition hover:text-teal-700" href="<?= BASE_URL ?>profile/index"><?= htmlspecialchars((string)($authUser['name'] ?? 'Tài khoản')) ?></a>
                <a class="rounded-md bg-slate-800 px-3 py-1.5 text-white transition hover:bg-slate-900" href="<?= BASE_URL ?>auth/logout">Đăng xuất</a>
            <?php else: ?>
                <a class="rounded-md bg-teal-600 px-3 py-1.5 text-white transition hover:bg-teal-700" href="<?= BASE_URL ?>auth/login">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </nav>
</header>