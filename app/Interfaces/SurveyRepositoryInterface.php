<?php

namespace App\Interfaces;

interface SurveyRepositoryInterface
{
    /**
     * Lấy danh sách các câu hỏi thuộc một lô cụ thể.
     * Yêu cầu: Bắt buộc Eager Load quan hệ `answerOptions`.
     *
     * @param string $batchCode Mã lô câu hỏi cần lấy dữ liệu.
     * @return array Tập hợp các câu hỏi dạng mảng.
     */
    public function getBatchWithQuestions(string $batchCode): array;

    /**
     * Lấy quy tắc phân nhánh cho lô câu hỏi hiện tại.
     * Dữ liệu bao gồm các mốc threshold, mã next_batch_high và next_batch_low.
     *
     * @param string $batchCode Mã lô câu hỏi.
     * @return array|null Trả về mảng Rule, hoặc null nếu không có luật (lô cuối).
     */
    public function getBatchRules(string $batchCode): ?array;
}
