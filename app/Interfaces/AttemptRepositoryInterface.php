<?php

namespace App\Interfaces;

interface AttemptRepositoryInterface
{
    /**
     * Khởi tạo một lượt làm bài mới cho người tham gia.
     *
     * @param int $participantId ID của người tham gia.
     * @param int $surveyId ID của bài khảo sát.
     * @return array Trả về mảng dữ liệu Attempt.
     */
    public function createAttempt(int $participantId, int $surveyId): array;

    /**
     * Lưu kết quả trả lời cho một câu hỏi.
     * Hỗ trợ lưu mảng optionIds dành cho các câu hỏi Multiple Choice (MC).
     *
     * @param int $attemptId ID của lượt làm bài.
     * @param int $questionId ID câu hỏi đang trả lời.
     * @param array<int> $optionIds Danh sách các ID đáp án đã chọn.
     * @param int $responseTimeMs Thời gian phản hồi tính bằng mili-giây (ms).
     * @return bool Trạng thái lưu thành công.
     */
    public function saveAnswer(int $attemptId, int $questionId, array $optionIds, int $responseTimeMs): bool;

    /**
     * Cập nhật thông câu hỏi lô tiếp theo và cộng dồn điểm số vào tổng điểm lượt làm bài.
     *
     * @param int $attemptId ID của lượt làm bài.
     * @param string $nextBatchCode Mã lô sẽ thực hiện tiếp theo.
     * @param float $addedScore Điểm số từ lô hiện tại để cộng dồn.
     * @return bool Trạng thái cập nhật thành công.
     */
    public function updateAttemptBatch(int $attemptId, string $nextBatchCode, float $addedScore): bool;
}
