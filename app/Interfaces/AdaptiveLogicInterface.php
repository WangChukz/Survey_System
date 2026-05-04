<?php

namespace App\Interfaces;

interface AdaptiveLogicInterface
{
    /**
     * Tính toán tổng điểm của một lô câu hỏi cụ thể trong một lượt làm bài.
     *
     * @param int $attemptId ID của lượt làm bài.
     * @param string $batchCode Mã lô câu hỏi (VD: '1', '2A').
     * @return float Tổng điểm đạt được trong lô.
     */
    public function calculateBatchScore(int $attemptId, string $batchCode): float;

    /**
     * Xác định mã lô câu hỏi tiếp theo dựa trên luật phân nhánh và điểm số.
     *
     * @param string $currentBatchCode Mã lô hiện tại.
     * @param float $score Điểm số làm cơ sở đánh giá (threshold).
     * @return string|null Mã lô tiếp theo, hoặc null nếu bài test kết thúc.
     */
    public function determineNextBatch(string $currentBatchCode, float $score): ?string;

    /**
     * Đánh giá và tổng hợp kết quả cuối cùng của lượt làm bài.
     *
     * @param int $attemptId ID của lượt làm bài.
     * @return array Kết quả chi tiết (bao gồm phân loại nhóm, tổng điểm các tags...).
     */
    public function evaluateFinalResult(int $attemptId): array;
}
