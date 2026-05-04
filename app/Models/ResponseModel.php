<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class ResponseModel extends Model
{
    /**
     * Lấy ID sinh viên đã tồn tại hoặc tạo mới
     */
    public function getOrCreateParticipant(string $fullname, string $faculty, string $studentCode): int
    {
        $sql = "SELECT id FROM participants WHERE student_code = :code LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => $studentCode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Có thể thêm logic cập nhật $faculty nếu cần, tạm thời bỏ qua để tối giản
            return (int)$row['id'];
        }

        $sqlInsert = "INSERT INTO participants (fullname, faculty, student_code, created_at) 
                      VALUES (:name, :faculty, :code, NOW())";
        $stmtInsert = $this->db->prepare($sqlInsert);
        $stmtInsert->execute([
            ':name' => $fullname,
            ':faculty' => $faculty,
            ':code' => $studentCode
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Khởi tạo lượt làm bài mới (Attempt)
     *
     * @param int $participantId
     * @param int $surveyId
     * @return int ID của Attempt vừa tạo
     */
    public function createAttempt(int $participantId, int $surveyId): int
    {
        $sql = "INSERT INTO attempts (participant_id, survey_id, current_batch, status, created_at) 
                VALUES (:p_id, :s_id, '1', 'in_progress', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':p_id' => $participantId, ':s_id' => $surveyId]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Lấy lượt làm bài (Attempt) mới nhất của một sinh viên
     */
    public function getAttemptByParticipant(int $participantId, int $surveyId): ?array
    {
        $sql = "SELECT * FROM attempts 
                WHERE participant_id = :p_id AND survey_id = :s_id 
                ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':p_id' => $participantId, ':s_id' => $surveyId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ?: null;
    }

    /**
     * Lấy thông tin sinh viên từ Attempt ID
     */
    public function getParticipantByAttempt(int $attemptId): ?array
    {
        $sql = "SELECT p.* FROM participants p
                INNER JOIN attempts a ON p.id = a.participant_id
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $attemptId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Lưu kết quả trả lời của người dùng vào các bảng attempts.
     * Sử dụng Transaction để đảm bảo tính toàn vẹn (ACID).
     *
     * @param int $attemptId ID lượt làm bài.
     * @param int $questionId ID câu hỏi.
     * @param array $optionIds Mảng các ID đáp án đã chọn.
     * @param int $responseTimeMs Thời gian trả lời (ms).
     * @return bool True nếu lưu thành công.
     */
    public function saveResponse(int $attemptId, int $questionId, array $optionIds, int $responseTimeMs): bool
    {
        if (empty($optionIds)) return false;

        try {
            $this->db->beginTransaction();

            // 1. Lưu thông tin trả lời câu hỏi
            $sqlAnswer = "INSERT INTO attempt_answers (attempt_id, question_id, response_time_ms, created_at) 
                          VALUES (:attempt_id, :question_id, :response_time, NOW())";
            $stmtAnswer = $this->db->prepare($sqlAnswer);
            $stmtAnswer->execute([
                ':attempt_id' => $attemptId,
                ':question_id' => $questionId,
                ':response_time' => $responseTimeMs
            ]);
            
            $attemptAnswerId = $this->db->lastInsertId();

            // 2. Lưu chi tiết các lựa chọn (Hỗ trợ Multiple Choice)
            $sqlOption = "INSERT INTO attempt_answer_options (attempt_answer_id, answer_option_id, created_at) 
                          VALUES (:attempt_answer_id, :answer_option_id, NOW())";
            $stmtOption = $this->db->prepare($sqlOption);

            foreach ($optionIds as $optId) {
                $stmtOption->execute([
                    ':attempt_answer_id' => $attemptAnswerId,
                    ':answer_option_id' => $optId
                ]);
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("DB Error in saveResponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính tổng điểm mà người dùng đạt được trong một lô cụ thể.
     *
     * @param int $attemptId ID lượt làm bài.
     * @param string $batchCode Mã lô câu hỏi.
     * @return float Tổng điểm đạt được.
     */
    public function calculateTotalScoreByBatch(int $attemptId, string $batchCode): float
    {
        $sql = "SELECT SUM(o.points) as total_score
                FROM attempt_answers aa
                INNER JOIN attempt_answer_options aao ON aa.id = aao.attempt_answer_id
                INNER JOIN answer_options o ON aao.answer_option_id = o.id
                INNER JOIN questions q ON aa.question_id = q.id
                WHERE aa.attempt_id = :attempt_id 
                  AND q.batch_code = :batch_code";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':attempt_id' => $attemptId,
            ':batch_code' => $batchCode
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total_score'] !== null ? (float)$result['total_score'] : 0.0;
    }
    
    /**
     * Cập nhật trạng thái và lô tiếp theo cho Attempt
     */
    public function updateAttemptBatch(int $attemptId, ?string $nextBatchCode): void
    {
        if ($nextBatchCode === null) {
            $sql = "UPDATE attempts SET status = 'completed', updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $attemptId]);
        } else {
            $sql = "UPDATE attempts SET current_batch = :batch, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':batch' => $nextBatchCode, ':id' => $attemptId]);
        }
    }

    /**
     * Cập nhật điểm tổng kết cuối cùng cho Attempt
     */
    public function updateFinalScore(int $attemptId, float $score): void
    {
        $sql = "UPDATE attempts SET total_score = :score, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':score' => $score, ':id' => $attemptId]);
    }
}
