<?php

declare(strict_types=1);

namespace Core;

/**
 * Router - Điều phối request tới đúng Controller@method.
 *
 * Không hardcode logic: routes được đăng ký từ ngoài vào.
 * Hỗ trợ tham số động: /survey/{id}
 */
class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: string}> */
    private array $routes = [];

    /**
     * Đăng ký một route.
     *
     * @param string $method  HTTP method: GET, POST, PUT, DELETE
     * @param string $pattern URI pattern, ví dụ: /survey/{id}
     * @param string $handler Format: ControllerClass@method
     */
    public function register(string $method, string $pattern, string $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Tìm route khớp và dispatch tới Controller.
     */
    public function dispatch(Request $request): void
    {
        $uri    = $request->getUri();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->matchPattern($route['pattern'], $uri);
            if ($params === null) {
                continue;
            }

            $this->callHandler($route['handler'], $params, $request);
            return;
        }

        // Không tìm thấy route → 404
        $this->send404();
    }

    /**
     * So khớp URI với pattern, trả về array tham số hoặc null nếu không khớp.
     *
     * @return array<string, string>|null
     */
    private function matchPattern(string $pattern, string $uri): ?array
    {
        // Chuyển pattern thành regex: /survey/{id} → /survey/(?P<id>[^/]+)
        $regexPattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regexPattern = '#^' . $regexPattern . '$#';

        if (!preg_match($regexPattern, $uri, $matches)) {
            return null;
        }

        // Chỉ lấy các key dạng chuỗi (tên tham số)
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    /**
     * Resolve handler string → khởi tạo Controller và gọi method.
     *
     * @param array<string, string> $params
     */
    private function callHandler(string $handler, array $params, Request $request): void
    {
        [$controllerName, $methodName] = explode('@', $handler);

        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller không tồn tại: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            throw new \RuntimeException("Method không tồn tại: {$controllerClass}::{$methodName}");
        }

        // Inject request và các params vào method
        $controller->$methodName($request, $params);
    }

    private function send404(): void
    {
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
    }
}
