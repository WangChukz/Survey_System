<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SJT Survey System</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/logo.svg">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f9fafb; /* Gray 50 (Rất nhạt) */
            font-family: 'Inter', sans-serif; 
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-10 px-4">
    <!-- Container chính với Soft Shadow và Bo góc nhẹ -->
    <div class="w-full <?= $maxWidth ?? 'max-w-4xl' ?> bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 transition-all duration-300">
        <?= $content ?? '' ?>
    </div>
</body>
</html>
