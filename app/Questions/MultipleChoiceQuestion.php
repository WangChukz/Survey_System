<?php

declare(strict_types=1);

namespace App\Questions;

use App\Questions\BaseQuestion;

/**
 * MultipleChoiceQuestion - Câu hỏi trắc nghiệm (radio hoặc checkbox).
 *
 * Config:
 *   - allow_multiple: bool (true → checkbox, false → radio)
 *   - min_select: int (tối thiểu số lựa chọn, chỉ khi allow_multiple)
 *   - max_select: int (tối đa số lựa chọn, chỉ khi allow_multiple)
 */
class MultipleChoiceQuestion extends BaseQuestion
{
    private bool $allowMultiple;
    private int  $minSelect;
    private int  $maxSelect;

    private string $lastError = '';

    public function __construct(array $data, array $options = [], array $config = [])
    {
        parent::__construct($data, $options, $config);
        $this->allowMultiple = (bool) ($config['allow_multiple'] ?? false);
        $this->minSelect     = (int)  ($config['min_select']     ?? 1);
        $this->maxSelect     = (int)  ($config['max_select']     ?? 0); // 0 = không giới hạn
    }

    /**
     * Render danh sách radio buttons hoặc checkboxes dựa vào data.
     * Hoàn toàn generic — tên/nhãn lấy từ $this->options.
     */
    public function render(): string
    {
        if (empty($this->options)) {
            return '<p class="no-options">Chưa có lựa chọn nào.</p>';
        }

        $inputType = $this->allowMultiple ? 'checkbox' : 'radio';
        $name      = $this->allowMultiple
            ? "answers[{$this->id}][]"
            : "answers[{$this->id}]";

        $html = '<ul class="options-list">';
        foreach ($this->options as $option) {
            $value = htmlspecialchars((string) $option->value);
            $label = htmlspecialchars((string) $option->label);
            $optId = "opt_{$this->id}_{$option->id}";

            $html .= sprintf(
                '<li class="option-item">
                    <input type="%s" id="%s" name="%s" value="%s" class="option-input">
                    <label for="%s" class="option-label">%s</label>
                </li>',
                $inputType, $optId, $name, $value, $optId, $label
            );
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Validate: kiểm tra giá trị có trong danh sách options và đủ số lượng.
     */
    public function validate(mixed $answer): bool
    {
        $validValues = array_map(fn($o) => (string) $o->value, $this->options);

        if ($this->isRequired && empty($answer)) {
            $this->lastError = 'Vui lòng chọn ít nhất một đáp án.';
            return false;
        }

        if (empty($answer)) {
            return true; // Không bắt buộc, bỏ qua
        }

        $selected = (array) $answer;

        foreach ($selected as $val) {
            if (!in_array((string) $val, $validValues, true)) {
                $this->lastError = 'Đáp án không hợp lệ.';
                return false;
            }
        }

        if ($this->allowMultiple) {
            $count = count($selected);
            if ($count < $this->minSelect) {
                $this->lastError = "Vui lòng chọn ít nhất {$this->minSelect} đáp án.";
                return false;
            }
            if ($this->maxSelect > 0 && $count > $this->maxSelect) {
                $this->lastError = "Vui lòng chọn tối đa {$this->maxSelect} đáp án.";
                return false;
            }
        }

        return true;
    }

    public function getValidationError(): string
    {
        return $this->lastError;
    }
}
