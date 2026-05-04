<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJT Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/logo.svg">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f3f4f6; /* Gray 100 cho nền Admin */
            font-family: 'Inter', sans-serif; 
        }
    </style>
</head>
<body class="h-screen flex text-gray-800 overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col">
        <div class="h-20 flex items-center px-6 border-b border-gray-800 gap-3">
            <img src="<?= BASE_URL ?>/assets/logo.svg" alt="Logo" class="w-10 h-10 object-contain">
            <h1 class="text-lg font-bold tracking-wider text-white">SJT ADMIN</h1>
        </div>
        
        <div class="flex-1 py-6 px-4 space-y-2">
            <!-- Link Dashboard -->
            <a href="<?= BASE_URL ?>/admin" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/admin/questions/add') === false) ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
                <i class="fas fa-chart-pie w-5"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            
            <!-- Link Quản lý câu hỏi -->
            <a href="<?= BASE_URL ?>/admin/questions/add" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/admin/questions/add') !== false) ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
                <i class="fas fa-list-check w-5"></i>
                <span class="font-medium text-sm">Quản lý câu hỏi</span>
            </a>
        </div>
        
        <!-- User Info / Logout -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 px-4 py-2">
                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold shadow-inner">
                    AD
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">Admin</p>
                    <p class="text-xs text-gray-400 truncate" title="admin@surveysystem.com">admin@surveysystem.com</p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/admin/logout" class="mt-4 flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-lg text-sm transition-colors font-medium w-full">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <!-- Top header bar for mobile (optional) / Breadcrumbs -->
        <header class="h-16 bg-white shadow-sm flex items-center px-8 border-b border-gray-100">
            <h2 class="text-gray-600 font-semibold text-sm">Hệ thống phân tích hành vi SJT</h2>
        </header>

        <!-- Content Area -->
        <div class="p-8">
            <?= $content ?? '' ?>
        </div>
    </main>

</body>
</html>
