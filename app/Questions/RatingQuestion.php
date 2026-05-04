<?php

declare(strict_types=1);

namespace App\Questions;

use App\Questions\BaseQuestion;

/**
 * RatingQuestion - Câu hỏi đánh giá theo thang điểm.
 *
 * Config:
 *   - min:   int (mặc định 1)
 *   - max:   int (mặc định 5)
 *   - style: 'stars' | 'numbers' | 'slider'
 */
class RatingQuestion extends BaseQuestion
{
    private int    $min;
    private int    $max;
    private string $style;

    private string $lastError = '';

    public function __construct(array $data, array $options = [], array $config = [])
    {
        parent::__construct($data, $options, $config);
        $this->min   = (int)    ($config['min']   ?? 1);
        $this->max   = (int)    ($config['max']   ?? 5);
        $this->style = (string) ($config['style'] ?? 'stars');
    }

    /**
     * Render star rating hoặc number buttons dựa vào config.
     */
    public function render(): string
    {
        $name = "answers[{$this->id}]";

        return match ($this->style) {
            'stars'   => $this->renderStars($name),
            'slider'  => $this->renderSlider($name),
            default   => $this->renderNumbers($name),
        };
    }

    private function renderStars(string $name): string
    {
        $html = '<div class="star-rating" data-min="' . $this->min . '" data-max="' . $this->max . '">';
        for ($i = $this->max; $i >= $this->min; $i--) {
            $html .= sprintf(
                '<input type="radio" id="star_%d_%d" name="%s" value="%d">
                 <label for="star_%d_%d" title="%d sao">★</label>',
                $this->id, $i, $name, $i,
                $this->id, $i, $i
            );
        }
        $html .= '</div>';
        return $html;
    }

    private function renderNumbers(string $name): string
    {
        $html = '<div class="number-rating">';
        for ($i = $this->min; $i <= $this->max; $i++) {
            $html .= sprintf(
                '<label class="number-option">
                    <input type="radio" name="%s" value="%d"> %d
                </label>',
                $name, $i, $i
            );
        }
        $html .= '</div>';
        return $html;
    }

    private function renderSlider(string $name): string
    {
        return sprintf(
            '<div class="slider-wrapper">
                <input type="range" name="%s" min="%d" max="%d" value="%d" class="rating-slider"
                       oninput="this.nextElementSibling.textContent = this.value">
                <span class="slider-value">%d</span>
            </div>',
            $name, $this->min, $this->max,
            (int) round(($this->min + $this->max) / 2),
            (int) round(($this->min + $this->max) / 2)
        );
    }

    /**
     * Validate: giá trị phải là số nguyên trong khoảng [min, max].
     */
    public function validate(mixed $answer): bool
    {
        if ($this->isRequired && ($answer === null || $answer === '')) {
            $this->lastError = 'Vui lòng chọn mức đánh giá.';
            return false;
        }

        if ($answer === null || $answer === '') {
            return true;
        }

        $value = (int) $answer;
        if ($value < $this->min || $value > $this->max) {
            $this->lastError = "Đánh giá phải từ {$this->min} đến {$this->max}.";
            return false;
        }

        return true;
    }

    public function getValidationError(): string
    {
        return $this->lastError;
    }
}
