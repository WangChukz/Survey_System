<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    private PDO $conn;

    /**
     * @param array<string, mixed>|null $config Mảng cấu hình database. Nếu null, tự động load từ file config.
     */
    public function __construct(?array $config = null)
    {
        if ($config === null) {
            // Load cấu hình mặc định bằng path tương đối từ thư mục Core
            $config = require dirname(__DIR__) . '/config/config.php';
        }

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? 'localhost',
            $config['port'] ?? 3306,
            $config['database'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $this->conn = new PDO(
                $dsn,
                $config['username'] ?? 'root',
                $config['password'] ?? '',
                $config['options'] ?? []
            );
        } catch (PDOException $e) {
            // Ném ra Exception thay vì dùng die() để ứng dụng gọi xử lý lỗi dễ dàng
            throw new PDOException("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Thực thi truy vấn với tham số
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Lấy đối tượng PDO connection
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
