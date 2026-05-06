<?php

declare(strict_types=1);

namespace App\Questions;

/**
 * BaseQuestion - Abstract class cho mọi loại câu hỏi.
 *
 * Áp dụng:
 * - Template Method Pattern: renderWithWrapper() gọi render() của lớp con
 * - Polymorphism: mỗi subclass triển khai render() riêng
 */
abstract class BaseQuestion
{
    protected int    $id;
    protected string $content;
    protected string $type;
    protected int    $index;

    /** @var array */
    protected array $options;

    public function __construct(array $data, int $index = 0)
    {
        $this->id         = (int)  ($data['id'] ?? 0);
        $this->content    = (string)($data['content'] ?? '');
        $this->type       = (string)($data['question_type'] ?? '');
        $this->options    = $data['options'] ?? [];
        $this->index      = $index;
    }

    // ─── Abstract Methods (mỗi subclass PHẢI triển khai) ─────────────────────

    /**
     * Render phần thân các đáp án của câu hỏi
     */
    abstract protected function renderOptions(): string;

    // ─── Template Method ──────────────────────────────────────────────────────

    /**
     * Render câu hỏi với wrapper HTML đầy đủ (áp dụng Tailwind CSS).
     * Template Method Pattern: luôn gọi $this->renderOptions() của subclass.
     */
    public function renderWithWrapper(): string
    {
        return sprintf(
            '<div class="bg-gray-50/50 rounded-xl p-5 border border-gray-100 transition-colors hover:border-gray-200">
                <p class="font-medium text-gray-900 mb-5 leading-relaxed text-[15px]">
                    <span class="text-gray-400 mr-1">%d.</span> 
                    %s
                </p>
                <div class="space-y-2.5">
                    %s
                </div>
            </div>',
            $this->index + 1,
            htmlspecialchars($this->content),
            $this->renderOptions() // ← Polymorphic call
        );
    }

    // ─── Getters ──────────────────────────────────────────────────────────────

    public function getId(): int       { return $this->id; }
    public function getType(): string  { return $this->type; }
}
