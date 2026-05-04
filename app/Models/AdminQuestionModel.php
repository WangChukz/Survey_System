<?php
/**
 * MODULE: [M5] Admin & Analytics
 * OWNER: Trang Anh (hoặc Admin Developer)
 * 
 * Logic xử lý riêng cho Admin để thêm/sửa/xóa câu hỏi.
 * Tách biệt hoàn toàn với QuestionModel của Hiếu (dùng cho user làm bài).
 */

namespace App\Models;

use Core\Database;

class AdminQuestionModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Thêm mới một câu hỏi và trả về ID của câu hỏi đó
     */
    public function createQuestion(int $surveyId, string $batchCode, string $content, string $questionType): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO questions (survey_id, batch_code, content, question_type, created_at, updated_at) 
            VALUES (:survey_id, :batch_code, :content, :question_type, NOW(), NOW())
        ");
        
        $stmt->execute([
            ':survey_id' => $surveyId,
            ':batch_code' => $batchCode,
            ':content' => $content,
            ':question_type' => $questionType
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Thêm danh sách đáp án cho một câu hỏi
     * $options là mảng các array: ['option_text' => '...', 'points' => 2, 'cost_tag' => '...']
     */
    public function createOptions(int $questionId, array $options): void
    {
        $sql = "INSERT INTO answer_options (question_id, option_text, points, cost_tag, created_at, updated_at) 
                VALUES (:question_id, :option_text, :points, :cost_tag, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);

        foreach ($options as $opt) {
            $stmt->execute([
                ':question_id' => $questionId,
                ':option_text' => $opt['option_text'],
                ':points'      => $opt['points'] ?? 0,
                ':cost_tag'    => $opt['cost_tag'] ?? null
            ]);
        }
    }
}
