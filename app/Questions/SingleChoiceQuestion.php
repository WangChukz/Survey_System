<?php

declare(strict_types=1);

namespace App\Questions;

/**
 * SingleChoiceQuestion - Câu hỏi trắc nghiệm chọn 1 (Radio).
 * Kế thừa BaseQuestion, thể hiện tính Đa hình (Polymorphism).
 */
class SingleChoiceQuestion extends BaseQuestion
{
    /**
     * Triển khai renderOptions() riêng cho SC (Radio button)
     */
    protected function renderOptions(): string
    {
        if (empty($this->options)) {
            return '<p class="text-gray-500 text-sm">Chưa có lựa chọn nào.</p>';
        }

        $html = '';
        foreach ($this->options as $opt) {
            $value = htmlspecialchars((string) ($opt['id'] ?? ''));
            $label = htmlspecialchars((string) ($opt['option_text'] ?? ''));
            $name  = "answers[{$this->id}]";

            $html .= sprintf(
                '<label class="flex items-start space-x-3 p-3.5 rounded-lg border border-transparent hover:bg-white hover:shadow-sm hover:border-gray-200 cursor-pointer transition-all duration-200 group">
                    <div class="flex-shrink-0 mt-0.5">
                        <input type="radio" 
                            name="%s" 
                            value="%s" 
                            required
                            class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-900 cursor-pointer transition-colors">
                    </div>
                    <span class="text-gray-600 text-sm group-hover:text-gray-900 transition-colors leading-snug pt-0.5">
                        %s
                    </span>
                </label>',
                $name, $value, $label
            );
        }

        return $html;
    }
}
