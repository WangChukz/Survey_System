<?php

declare(strict_types=1);

/**
 * Cấu hình chung của ứng dụng.
 */

define('APP_NAME',    'Survey System');
define('APP_VERSION', '1.0.0');
define('APP_ENV',     'development'); // 'production' | 'development'
define('APP_DEBUG',   APP_ENV === 'development');
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = str_replace('\\', '/', $scriptDir); // Fix cho Windows
define('BASE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($scriptDir === '/' ? '' : $scriptDir));
define('VIEW_PATH',   APP_PATH . '/Views');

// Bật hiển thị lỗi khi development
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
