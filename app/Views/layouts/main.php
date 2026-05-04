<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Hệ thống khảo sát trực tuyến — <?= htmlspecialchars($pageTitle ?? '') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>

<!-- ── Header ────────────────────────────────────────────────────────────── -->
<header class="site-header">
    <div class="container">
        <a href="<?= BASE_URL ?>/" class="site-logo">
            <span class="logo-icon">📋</span>
            <span class="logo-text"><?= APP_NAME ?></span>
        </a>
        <nav class="site-nav">
            <a href="<?= BASE_URL ?>/" class="nav-link">Trang chủ</a>
        </nav>
    </div>
</header>

<!-- ── Main Content ──────────────────────────────────────────────────────── -->
<main class="site-main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<!-- ── Footer ────────────────────────────────────────────────────────────── -->
<footer class="site-footer">
    <div class="container">
        <p>© <?= date('Y') ?> <?= APP_NAME ?> · Phiên bản <?= APP_VERSION ?></p>
    </div>
</footer>

<!-- Chart.js (dùng cho analytics) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>
