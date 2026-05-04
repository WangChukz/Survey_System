<?php

declare(strict_types=1);

namespace App\Questions;

use App\Questions\BaseQuestion;

/**
 * TextQuestion - Câu hỏi nhập văn bản tự do.
 *
 * Config:
 *   - input_type: 'text' | 'email' | 'number' | 'textarea'
 *   - max_length:  int
 *   - min_length:  int
 *   - pattern:     string (regex để validate, tùy chọn)
 *   - placeholder: string
 */
class TextQuestion extends BaseQuestion
{
    private string $inputType;
    private int    $maxLength;
    private int    $minLength;
    private string $pattern;
    private string $placeholder;

    private string $lastError = '';

    public function __construct(array $data, array $options = [], array $config = [])
    {
        parent::__construct($data, $options, $config);
        $this->inputType   = (string) ($config['input_type']  ?? 'text');
        $this->maxLength   = (int)    ($config['max_length']  ?? 1000);
        $this->minLength   = (int)    ($config['min_length']  ?? 0);
        $this->pattern     = (string) ($config['pattern']     ?? '');
        $this->placeholder = (string) ($config['placeholder'] ?? '');
    }

    /**
     * Render textarea hoặc input tùy thuộc vào input_type.
     */
    public function render(): string
    {
        $name        = "answers[{$this->id}]";
        $placeholder = htmlspecialchars($this->placeholder);
        $maxLength   = $this->maxLength;

        if ($this->inputType === 'textarea') {
            return sprintf(
                '<textarea name="%s" class="form-textarea" placeholder="%s" maxlength="%d" rows="4">%s</textarea>',
                $name, $placeholder, $maxLength, ''
            );
        }

        return sprintf(
            '<input type="%s" name="%s" class="form-input" placeholder="%s" maxlength="%d">',
            htmlspecialchars($this->inputType),
            $name,
            $placeholder,
            $maxLength
        );
    }

    /**
     * Validate: độ dài, bắt buộc, pattern (nếu có).
     */
    public function validate(mixed $answer): bool
    {
        $value = trim((string) ($answer ?? ''));

        if ($this->isRequired && $value === '') {
            $this->lastError = 'Vui lòng nhập câu trả lời.';
            return false;
        }

        if ($value === '') {
            return true; // Không bắt buộc, trống thì OK
        }

        if ($this->minLength > 0 && mb_strlen($value) < $this->minLength) {
            $this->lastError = "Câu trả lời cần ít nhất {$this->minLength} ký tự.";
            return false;
        }

        if (mb_strlen($value) > $this->maxLength) {
            $this->lastError = "Câu trả lời không được vượt quá {$this->maxLength} ký tự.";
            return false;
        }

        if ($this->pattern !== '' && !preg_match($this->pattern, $value)) {
            $this->lastError = 'Định dạng câu trả lời không hợp lệ.';
            return false;
        }

        return true;
    }

    public function getValidationError(): string
    {
        return $this->lastError;
    }
}
