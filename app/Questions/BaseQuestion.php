<?php

declare(strict_types=1);

namespace App\Questions;

/**
 * BaseQuestion - Abstract class cho mọi loại câu hỏi.
 *
 * Áp dụng:
 * - Template Method Pattern: renderWithWrapper() gọi render() của lớp con
 * - Polymorphism: mỗi subclass triển khai render() và validate() riêng
 *
 * Controller và Service chỉ làm việc với BaseQuestion,
 * không cần biết type cụ thể.
 */
abstract class BaseQuestion
{
    protected int    $id;
    protected string $label;
    protected bool   $isRequired;
    protected string $type;

    /** @var array<string, mixed> Cấu hình đặc thù của từng type */
    protected array $config;

    /** @var \App\Models\Option[] */
    protected array $options;

    /**
     * @param array<string, mixed>  $data    Row từ DB (attributes của Question model)
     * @param \App\Models\Option[]  $options Danh sách options (nếu có)
     * @param array<string, mixed>  $config  Config đã parse từ JSON
     */
    public function __construct(array $data, array $options = [], array $config = [])
    {
        $this->id         = (int)  ($data['id']          ?? 0);
        $this->label      = (string)($data['label']       ?? '');
        $this->isRequired = (bool)  ($data['is_required'] ?? true);
        $this->type       = (string)($data['type']        ?? '');
        $this->options    = $options;
        $this->config     = $config;
    }

    // ─── Abstract Methods (mỗi subclass PHẢI triển khai) ─────────────────────

    /**
     * Render HTML input element cho câu hỏi.
     * Không bao gồm wrapper — wrapper do renderWithWrapper() xử lý.
     */
    abstract public function render(): string;

    /**
     * Validate câu trả lời từ user.
     *
     * @param  mixed $answer Giá trị thô từ $_POST
     * @return bool  true nếu hợp lệ
     */
    abstract public function validate(mixed $answer): bool;

    /**
     * Trả về thông báo lỗi nếu validate() thất bại.
     */
    abstract public function getValidationError(): string;

    // ─── Template Method ──────────────────────────────────────────────────────

    /**
     * Render câu hỏi với wrapper HTML đầy đủ (label, error slot, ...).
     * Template Method Pattern: luôn gọi $this->render() của subclass.
     */
    public function renderWithWrapper(array $errors = []): string
    {
        $errorMsg  = $errors[$this->id] ?? '';
        $required  = $this->isRequired ? '<span class="required">*</span>' : '';
        $errorHtml = $errorMsg
            ? "<p class=\"error-msg\">" . htmlspecialchars($errorMsg) . "</p>"
            : '';

        return sprintf(
            '<div class="question-block %s" data-question-id="%d" data-type="%s">
                <label class="question-label">%s%s</label>
                <div class="question-input">%s</div>
                %s
            </div>',
            $errorMsg ? 'has-error' : '',
            $this->id,
            htmlspecialchars($this->type),
            htmlspecialchars($this->label),
            $required,
            $this->render(),       // ← Polymorphic call
            $errorHtml
        );
    }

    // ─── Getters ──────────────────────────────────────────────────────────────

    public function getId(): int       { return $this->id; }
    public function getLabel(): string { return $this->label; }
    public function getType(): string  { return $this->type; }
    public function isRequired(): bool { return $this->isRequired; }

    /** @return array<string, mixed> */
    public function getConfig(): array { return $this->config; }
}
