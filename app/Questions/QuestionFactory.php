<?php

declare(strict_types=1);

namespace App\Questions;

use App\Questions\BaseQuestion;

/**
 * QuestionFactory - Tạo đúng loại BaseQuestion dựa vào type string.
 *
 * Open/Closed Principle:
 *   - Thêm loại câu hỏi mới → tạo class mới + thêm 1 dòng vào $map
 *   - Không cần sửa code hiện tại
 */
class QuestionFactory
{
    /**
     * Map từ type string (lưu trong DB) → class PHP tương ứng.
     *
     * @var array<string, class-string<BaseQuestion>>
     */
    private static array $map = [
        'multiple_choice' => MultipleChoiceQuestion::class,
        // ← Thêm loại mới tại đây
    ];

    /**
     * Tạo instance BaseQuestion phù hợp.
     *
     * @param string                $type    Giá trị cột `type` trong DB
     * @param array<string, mixed>  $data    Attributes của Question model
     * @param \App\Models\Option[]  $options Danh sách options
     * @param array<string, mixed>  $config  Config đã parse từ JSON
     *
     * @throws \InvalidArgumentException Nếu type chưa được đăng ký
     */
    public static function create(
        string $type,
        array $data,
        array $options = [],
        array $config  = []
    ): BaseQuestion {
        if (!isset(self::$map[$type])) {
            throw new \InvalidArgumentException(
                "Loại câu hỏi không hợp lệ: '{$type}'. "
                . "Các type hợp lệ: " . implode(', ', array_keys(self::$map))
            );
        }

        $class = self::$map[$type];
        return new $class($data, $options, $config);
    }

    /**
     * Đăng ký một loại câu hỏi mới tại runtime.
     * Dùng khi cần inject type từ plugin/extension.
     *
     * @param class-string<BaseQuestion> $class
     */
    public static function register(string $type, string $class): void
    {
        if (!is_subclass_of($class, BaseQuestion::class)) {
            throw new \InvalidArgumentException(
                "{$class} phải extend BaseQuestion"
            );
        }
        self::$map[$type] = $class;
    }

    /**
     * Lấy danh sách tất cả type đã được đăng ký.
     *
     * @return string[]
     */
    public static function getSupportedTypes(): array
    {
        return array_keys(self::$map);
    }
}
