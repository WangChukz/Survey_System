<?php

declare(strict_types=1);

namespace App\Questions;

/**
 * QuestionFactory - Tạo đúng loại BaseQuestion dựa vào type string.
 * Áp dụng Factory Method Pattern.
 */
class QuestionFactory
{
    /**
     * Map từ type string (trong DB) → class PHP tương ứng.
     *
     * @var array<string, class-string<BaseQuestion>>
     */
    private static array $map = [
        'SC' => SingleChoiceQuestion::class,
        'MC' => MultipleChoiceQuestion::class,
    ];

    /**
     * Tạo instance BaseQuestion phù hợp.
     *
     * @param string $type  Loại câu hỏi (SC, MC)
     * @param array  $data  Dữ liệu row từ DB (bao gồm cả options)
     * @param int    $index Vị trí câu hỏi để hiển thị số thứ tự
     *
     * @throws \InvalidArgumentException Nếu type chưa được đăng ký
     */
    public static function create(string $type, array $data, int $index = 0): BaseQuestion
    {
        if (!isset(self::$map[$type])) {
            throw new \InvalidArgumentException(
                "Loại câu hỏi không hợp lệ: '{$type}'. "
                . "Các type hợp lệ: " . implode(', ', array_keys(self::$map))
            );
        }

        $class = self::$map[$type];
        return new $class($data, $index);
    }
}
