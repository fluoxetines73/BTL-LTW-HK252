<section class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">Thông tin tài khoản</h1>
        <p class="mt-2 text-sm text-slate-500">Quản lý thông tin cá nhân và bảo mật tài khoản của bạn.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-[220px_1fr] md:items-start">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-center">
            <?php if (!empty($user['avatar'])): ?>
                <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Ảnh đại diện" class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow">
            <?php else: ?>
                <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Ảnh đại diện mặc định" class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow">
            <?php endif; ?>
            <p class="mt-3 text-xs uppercase tracking-wide text-slate-500">Ảnh đại diện</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2 rounded-xl bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Họ và tên</dt>
                    <dd class="mt-1 text-lg font-semibold text-slate-800"><?= htmlspecialchars($user['full_name'] ?? '') ?></dd>
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt>
                    <dd class="mt-1 text-base font-medium text-slate-800 break-all"><?= htmlspecialchars($user['email'] ?? '') ?></dd>
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Số điện thoại</dt>
                    <dd class="mt-1 text-base font-medium text-slate-800"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="<?= BASE_URL ?>profile/edit" class="inline-flex items-center rounded-xl bg-teal-700 px-5 py-2.5 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-300">
                    Chỉnh sửa hồ sơ
                </a>
                <a href="<?= BASE_URL ?>profile/changePassword" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-100">
                    Đổi mật khẩu
                </a>
            </div>
        </div>
    </div>
</section>
