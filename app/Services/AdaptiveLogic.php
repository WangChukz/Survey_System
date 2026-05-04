<?php

namespace App\Services;

use App\Models\ResponseModel;

class AdaptiveLogic
{
    private ResponseModel $responseModel;

    // Hardcode luật rẽ nhánh theo chuẩn thiết kế SJT (Option A)
    private array $branchingRules = [
        '1' => [
            'threshold_score' => 10,
            'next_batch_high' => '2A',
            'next_batch_low'  => '2B'
        ],
        '2A' => [
            'threshold_score' => 12,
            'next_batch_high' => '3A1',
            'next_batch_low'  => '3A2'
        ],
        '2B' => [
            'threshold_score' => 9,
            'next_batch_high' => '3B1',
            'next_batch_low'  => '3B2'
        ]
        // Lô 3A1, 3A2, 3B1, 3B2 là lô cuối, không có trong map này
    ];

    public function __construct(ResponseModel $responseModel)
    {
        $this->responseModel = $responseModel;
    }

    /**
     * Xác định lô tiếp theo dựa trên luật rẽ nhánh và điểm số lô hiện tại.
     *
     * @param int $attemptId ID lượt làm bài.
     * @param string $currentBatchCode Mã lô hiện tại (VD: '1', '2A').
     * @return string|null Mã lô tiếp theo, hoặc null nếu kết thúc.
     */
    public function determineNextBatch(int $attemptId, string $currentBatchCode): ?string
    {
        // Nếu lô hiện tại không nằm trong mảng luật, tức là đã đến lô cuối cùng
        if (!isset($this->branchingRules[$currentBatchCode])) {
            $this->responseModel->updateAttemptBatch($attemptId, null); // Hoàn thành
            return null;
        }

        // 1. Lấy tổng điểm mà người dùng đạt được tại lô hiện tại
        $score = $this->responseModel->calculateTotalScoreByBatch($attemptId, $currentBatchCode);

        // 2. So sánh điểm với threshold (ngưỡng) để rẽ nhánh
        $rule = $this->branchingRules[$currentBatchCode];
        
        $nextBatch = ($score >= $rule['threshold_score']) 
                        ? $rule['next_batch_high'] 
                        : $rule['next_batch_low'];
                        
        // Cập nhật trạng thái lô hiện tại vào Attempt
        $this->responseModel->updateAttemptBatch($attemptId, $nextBatch);
        
        return $nextBatch;
    }
}
