<section class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">Chỉnh sửa hồ sơ</h1>
        <p class="mt-2 text-sm text-slate-500">Cập nhật thông tin cá nhân và ảnh đại diện của bạn.</p>
    </div>

    <?php if (!empty($flash)): ?>
        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
        <div class="mb-5 rounded-xl border px-4 py-3 text-sm <?= $isError ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="grid gap-5" novalidate>
        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Họ và tên</span>
                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-800 outline-none transition focus:border-teal-600 focus:ring-2 focus:ring-teal-100"
                    placeholder="Ví dụ: Nguyễn Văn A"
                >
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Số điện thoại</span>
                <input
                    type="text"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-800 outline-none transition focus:border-teal-600 focus:ring-2 focus:ring-teal-100"
                    placeholder="Ví dụ: 0901234567"
                >
            </label>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-3 text-sm font-semibold text-slate-700">Ảnh đại diện</div>

            <?php if (!empty($user['avatar'])): ?>
                <?php $avatarPath = (string)$user['avatar']; if (!str_starts_with($avatarPath, 'public/')) { $avatarPath = 'public/' . ltrim($avatarPath, '/'); } ?>
                <img src="<?= BASE_URL . htmlspecialchars($avatarPath) ?>" alt="Ảnh đại diện hiện tại" class="mb-3 h-16 w-16 rounded-full border-2 border-white object-cover shadow">
            <?php else: ?>
                <img src="<?= BASE_URL ?>public/uploads/avatars/default-avatar.svg" alt="Ảnh đại diện mặc định" class="mb-3 h-16 w-16 rounded-full border-2 border-white object-cover shadow">
            <?php endif; ?>

            <input
                type="file"
                name="avatar"
                accept="image/*"
                class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-slate-700 hover:file:bg-slate-200"
            >
            <p class="mt-2 text-xs text-slate-500">Định dạng hỗ trợ: JPG, PNG, WEBP. Kích thước tối đa theo cấu hình hệ thống.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-1">
            <button type="submit" class="inline-flex items-center rounded-xl bg-teal-700 px-5 py-2.5 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-300">
                Lưu thay đổi
            </button>
            <a href="<?= BASE_URL ?>profile/index" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-100">
                Quay lại hồ sơ
            </a>
        </div>
    </form>
</section>
