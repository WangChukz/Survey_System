<?php

namespace App\Models;

use Core\Model;
use PDO;

class AnalyticsModel extends Model
{
    /**
     * Lấy thống kê tổng quan (General Stats)
     */
    public function getGeneralStats(): array
    {
        $stats = [
            'total_participants' => 0,
            'completed_attempts' => 0,
            'average_score' => 0.0
        ];

        // Số người tham gia
        $stmt = $this->db->query("SELECT COUNT(id) as count FROM participants");
        $stats['total_participants'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Số lượt làm bài hoàn thành
        $stmt = $this->db->query("SELECT COUNT(id) as count FROM attempts WHERE status = 'completed'");
        $stats['completed_attempts'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Tính Điểm trung bình toàn trường
        $stmt = $this->db->query("SELECT id, total_score FROM attempts WHERE status = 'completed'");
        $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stats['average_max_score'] = 0.0;
        if (count($attempts) > 0) {
            $totalScores = 0;
            foreach ($attempts as $att) {
                $totalScores += (float)$att['total_score'];
            }
            $stats['average_score'] = round($totalScores / count($attempts), 2);
        }

        // Tính Điểm Max tuyệt đối của toàn bộ câu hỏi trong hệ thống
        $sqlGlobalMax = "SELECT SUM(max_p) as global_max FROM (
            SELECT 
                CASE 
                    WHEN q.question_type = 'MC' THEN SUM(CASE WHEN o.points > 0 THEN o.points ELSE 0 END)
                    ELSE MAX(o.points)
                END as max_p
            FROM questions q
            JOIN answer_options o ON q.id = o.question_id
            GROUP BY q.id, q.question_type
        ) as subquery";
        $stmtGlobalMax = $this->db->query($sqlGlobalMax);
        $globalMaxResult = $stmtGlobalMax->fetch(PDO::FETCH_ASSOC);
        $stats['average_max_score'] = (float)($globalMaxResult['global_max'] ?? 0);

        return $stats;
    }

    /**
     * Lấy phân bổ sinh viên theo Khoa (cho biểu đồ tròn)
     */
    public function getParticipantCountByFaculty(): array
    {
        $sql = "SELECT faculty, COUNT(id) as student_count 
                FROM participants 
                GROUP BY faculty
                ORDER BY student_count DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy điểm trung bình theo từng Khoa (cho biểu đồ cột)
     */
    public function getAverageScoreByFaculty(): array
    {
        $sql = "SELECT p.faculty, AVG(a.total_score) as avg_score 
                FROM participants p 
                INNER JOIN attempts a ON p.id = a.participant_id 
                WHERE a.status = 'completed' 
                GROUP BY p.faculty
                ORDER BY avg_score DESC";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Làm tròn điểm
        foreach ($results as &$row) {
            $row['avg_score'] = round((float)$row['avg_score'], 2);
        }
        return $results;
    }

    /**
     * Lấy xu hướng hành vi toàn trường (cho biểu đồ Nhện)
     * Lấy trực tiếp nhãn từ database (động), nhưng chỉ hiển thị 15 nhãn có điểm cao nhất để tối ưu giao diện
     */
    public function getBehavioralTraitsGlobal(): array
    {
        $sql = "SELECT o.cost_tag, SUM(o.points) as total_points 
                FROM attempt_answer_options aao 
                INNER JOIN answer_options o ON aao.answer_option_id = o.id 
                WHERE o.cost_tag IS NOT NULL AND o.cost_tag != ''
                GROUP BY o.cost_tag
                ORDER BY total_points DESC
                LIMIT 15";
        $stmt = $this->db->query($sql);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cấu trúc lại thành mảng key-value [ 'Tên Nhãn' => Điểm ]
        $traits = [];
        foreach ($results as $row) {
            $traits[trim($row['cost_tag'])] = (float)$row['total_points'];
        }
        
        return $traits;
    }
}
