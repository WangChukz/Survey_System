<?php

declare(strict_types=1);

namespace Core;

/**
 * Request - Đóng gói toàn bộ thông tin của HTTP request hiện tại.
 *
 * Controller nhận Request object thay vì truy cập $_GET/$_POST trực tiếp.
 */
class Request
{
    private string $method;
    private string $uri;
    /** @var array<string, mixed> */
    private array $queryParams;
    /** @var array<string, mixed> */
    private array $bodyParams;
    /** @var array<string, string> */
    private array $headers;

    public function __construct()
    {
        $this->method      = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri         = $this->parseUri();
        $this->queryParams = $_GET ?? [];
        $this->bodyParams  = $_POST ?? [];
        $this->headers     = $this->parseHeaders();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function isMethod(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    /**
     * Lấy giá trị từ query string (?key=value).
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Lấy giá trị từ POST body.
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key] ?? $default;
    }

    /**
     * Lấy tất cả POST data.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->bodyParams;
    }

    /**
     * Lấy tất cả POST data, lọc theo danh sách key cho phép.
     *
     * @param  string[] $keys
     * @return array<string, mixed>
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->bodyParams, array_flip($keys));
    }

    /**
     * Lấy HTTP header theo tên.
     */
    public function header(string $name): ?string
    {
        $normalized = strtolower(str_replace('-', '_', $name));
        return $this->headers[$normalized] ?? null;
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Bỏ query string
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        // Bỏ base path (phần prefix của sub-directory)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        return '/' . ltrim($uri, '/') ?: '/';
    }

    /** @return array<string, string> */
    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name           = strtolower(substr($key, 5));
                $headers[$name] = $value;
            }
        }
        return $headers;
    }
}
