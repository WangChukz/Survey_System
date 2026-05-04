<?php

declare(strict_types=1);

// ─── Hỗ trợ PHP Built-in Server ────────────────────────────────────────────
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path && is_file($path)) {
        return false; // Trả về file tĩnh (CSS, JS, Hình ảnh,...) trực tiếp
    }
}

/**
 * Front Controller
 * Điểm vào duy nhất của toàn bộ ứng dụng.
 */

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');

session_start();

// ─── Autoloader ────────────────────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    if (str_starts_with($class, 'App\\')) {
        $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    } elseif (str_starts_with($class, 'Core\\')) {
        $file = BASE_PATH . '/Core/' . str_replace('\\', '/', substr($class, 5)) . '.php';
    } else {
        return;
    }
    if (file_exists($file)) {
        require_once $file;
    }
});

// ─── Load Environment Variables ──────────────────────────────────────────
\Core\DotEnv::load(BASE_PATH . '/.env');

// ─── Bootstrap ─────────────────────────────────────────────────────────────
require_once BASE_PATH . '/config/app.php';

// ─── Xử lý Request ─────────────────────────────────────────────────────────
$request = new \Core\Request();

// ─── Load Routes ───────────────────────────────────────────────────────────
$router = new \Core\Router();
$routes = require BASE_PATH . '/config/routes.php';

foreach ($routes as [$method, $pattern, $handler]) {
    $router->register($method, $pattern, $handler);
}

// ─── Dispatch ──────────────────────────────────────────────────────────────
$router->dispatch($request);
