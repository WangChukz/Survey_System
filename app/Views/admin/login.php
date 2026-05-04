<div class="text-center mb-8">
    <img src="<?= BASE_URL ?>/assets/logo.svg" alt="SJT Logo" class="w-20 h-20 mx-auto mb-4 object-contain">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Admin Login</h1>
    <p class="text-sm text-gray-500 mt-2">Hệ thống khảo sát SJT</p>
</div>

<?php if (!empty($error)): ?>
<div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 flex items-center text-sm font-medium">
    <i class="fas fa-exclamation-circle mr-2"></i>
    <span><?= htmlspecialchars($error) ?></span>
</div>
<?php endif; ?>

<form action="<?= BASE_URL ?>/admin/login" method="POST" class="space-y-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên đăng nhập</label>
        <div class="relative">
            <input type="text" name="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all text-sm" placeholder="Nhập admin..." required>
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu</label>
        <div class="relative">
            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all text-sm" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:bg-black hover:shadow-lg transition-all flex items-center justify-center gap-2">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập hệ thống
    </button>
</form>

<div class="mt-8 pt-6 border-t border-gray-100 text-center">
    <a href="<?= BASE_URL ?>/" class="text-sm text-gray-500 hover:text-gray-800 font-medium transition-colors">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại trang chủ sinh viên
    </a>
</div>
