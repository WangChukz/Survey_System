<?php

namespace App\Models;

use Core\Model;
use PDO;

class QuestionModel extends Model
{
    /**
     * Lấy danh sách câu hỏi thuộc một lô, kèm theo toàn bộ đáp án (options) của từng câu.
     * Sử dụng kỹ thuật JOIN và format lại mảng để tối ưu hiệu suất truy vấn.
     *
     * @param string $batchCode Mã/ID lô câu hỏi.
     * @return array Danh sách câu hỏi đã được nhóm kèm đáp án.
     */
    public function getQuestionsByBatch(string $batchCode): array
    {
        $sql = "SELECT q.id as question_id, q.content, q.question_type,
                       o.id as option_id, o.option_text, o.points, o.cost_tag
                FROM questions q
                LEFT JOIN answer_options o ON q.id = o.question_id
                WHERE q.batch_code = :batch_code";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':batch_code' => $batchCode]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questions = [];
        foreach ($results as $row) {
            $qId = $row['question_id'];
            
            if (!isset($questions[$qId])) {
                $questions[$qId] = [
                    'id'            => $qId,
                    'content'       => $row['content'],
                    'question_type' => $row['question_type'],
                    'options'       => []
                ];
            }
            
            if ($row['option_id']) {
                $questions[$qId]['options'][] = [
                    'id'          => $row['option_id'],
                    'option_text' => $row['option_text'],
                    'points'      => (float)$row['points'],
                    'cost_tag'    => $row['cost_tag']
                ];
            }
        }

        return array_values($questions);
    }

    /**
     * Lấy danh sách các đáp án chỉ thuộc về một câu hỏi cụ thể.
     *
     * @param int $questionId ID của câu hỏi.
     * @return array Danh sách đáp án.
     */
    public function getOptionsByQuestion(int $questionId): array
    {
        $sql = "SELECT id, option_text, points, cost_tag 
                FROM answer_options 
                WHERE question_id = :question_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':question_id' => $questionId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
