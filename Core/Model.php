<?php

namespace Core;

use PDO;

abstract class Model
{
    /**
     * @var PDO
     */
    protected PDO $db;

    /**
     * Khởi tạo Model với kết nối Database truyền vào (Dependency Injection)
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
}
