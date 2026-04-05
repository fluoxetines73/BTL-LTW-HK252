<section class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">Đổi mật khẩu</h1>
        <p class="mt-2 text-sm text-slate-500">Tạo mật khẩu mới để bảo vệ tài khoản của bạn tốt hơn.</p>
    </div>

    <?php if (!empty($flash)): ?>
        <?php $isError = ($flash['type'] ?? '') === 'error'; ?>
        <div class="mb-5 rounded-xl border px-4 py-3 text-sm <?= $isError ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>profile/updatePassword" class="grid gap-5" novalidate>
        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Mật khẩu hiện tại</span>
            <input
                type="password"
                name="current_password"
                required
                autocomplete="current-password"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-800 outline-none transition focus:border-teal-600 focus:ring-2 focus:ring-teal-100"
                placeholder="Nhập mật khẩu hiện tại"
            >
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Mật khẩu mới</span>
                <input
                    type="password"
                    name="new_password"
                    required
                    minlength="6"
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-800 outline-none transition focus:border-teal-600 focus:ring-2 focus:ring-teal-100"
                    placeholder="Tối thiểu 6 ký tự"
                >
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-semibold text-slate-700">Xác nhận mật khẩu mới</span>
                <input
                    type="password"
                    name="new_password_confirmation"
                    required
                    minlength="6"
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-800 outline-none transition focus:border-teal-600 focus:ring-2 focus:ring-teal-100"
                    placeholder="Nhập lại mật khẩu mới"
                >
            </label>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-1">
            <button type="submit" class="inline-flex items-center rounded-xl bg-teal-700 px-5 py-2.5 font-semibold text-white transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-300">
                Cập nhật mật khẩu
            </button>
            <a href="<?= BASE_URL ?>profile/index" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-100">
                Quay lại hồ sơ
            </a>
        </div>
    </form>
</section>
