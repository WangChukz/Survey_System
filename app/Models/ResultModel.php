<?php

namespace App\Models;

use Core\Model;
use PDO;

class ResultModel extends Model
{


    /**
     * Tính toán toàn bộ Insights cho một lượt làm bài (Attempt)
     */
    public function getFullInsights(int $attemptId): array
    {
        // 1. Lấy dữ liệu thô từ các câu trả lời
        $sql = "SELECT q.batch_code, o.cost_tag, o.points 
                FROM attempt_answers aa
                INNER JOIN attempt_answer_options aao ON aa.id = aao.attempt_answer_id
                INNER JOIN answer_options o ON aao.answer_option_id = o.id
                INNER JOIN questions q ON aa.question_id = q.id
                WHERE aa.attempt_id = :attempt_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':attempt_id' => $attemptId]);
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($responses)) return [];

        // 2. Tính Radar Chart Data
        $radarData = [
            'Tiện lợi' => 0,
            'Hình ảnh' => 0,
            'Quan hệ' => 0,
            'Kỷ luật' => 0,
            'Dấn thân' => 0
        ];

        foreach ($responses as $res) {
            $tag = trim($res['cost_tag']);
            if (isset($radarData[$tag])) {
                $radarData[$tag] += (float)$res['points'];
            }
        }

        // 3. Tính Tổng điểm dựa trên TỔNG ĐIỂM CAO NHẤT của các câu hỏi đã làm
        $totalScore = array_sum(array_column($responses, 'points'));
        
        $sqlMax = "SELECT SUM(max_p) as max_possible FROM (
            SELECT 
                CASE 
                    WHEN q.question_type = 'MC' THEN SUM(CASE WHEN o.points > 0 THEN o.points ELSE 0 END)
                    ELSE MAX(o.points)
                END as max_p 
            FROM attempt_answers aa
            INNER JOIN questions q ON aa.question_id = q.id
            INNER JOIN answer_options o ON q.id = o.question_id
            WHERE aa.attempt_id = :attempt_id
            GROUP BY aa.question_id, q.question_type
        ) as subquery";
        $stmtMax = $this->db->prepare($sqlMax);
        $stmtMax->execute([':attempt_id' => $attemptId]);
        $maxResult = $stmtMax->fetch(PDO::FETCH_ASSOC);
        $maxScore = $maxResult['max_possible'] ?? 60; // fallback


        // 4. Xác định Nhóm (Archetype) dựa trên Batch Code cuối cùng
        $lastBatch = '';
        foreach ($responses as $res) {
            if (in_array($res['batch_code'], ['3A1', '3A2', '3B1', '3B2'])) {
                $lastBatch = $res['batch_code'];
            }
        }

        $archetypeMap = [
            '3A1' => 'Trách nhiệm cao - Chủ động',
            '3A2' => 'Trách nhiệm cao - Thận trọng',
            '3B1' => 'Trách nhiệm thấp - Thụ động',
            '3B2' => 'Trách nhiệm thấp - Cá nhân'
        ];
        $mainGroup = $archetypeMap[$lastBatch] ?? 'Chưa xác định';

        // 5. Tính Tỷ trọng Doughnut (Dựa trên điểm số ở các nhánh)
        $batchScores = ['3A1' => 0, '3A2' => 0, '3B1' => 0, '3B2' => 0];
        foreach ($responses as $res) {
            $bc = $res['batch_code'];
            if (isset($batchScores[$bc])) $batchScores[$bc] += (float)$res['points'];
            // Cộng thêm điểm từ Batch 1 & 2 vào các nhóm tương ứng để tạo độ "tương đồng"
            if ($bc == '1' || $bc == '2A') {
                $batchScores['3A1'] += (float)$res['points'] * 0.5;
                $batchScores['3A2'] += (float)$res['points'] * 0.4;
            }
            if ($bc == '1' || $bc == '2B') {
                $batchScores['3B1'] += (float)$res['points'] * 0.5;
                $batchScores['3B2'] += (float)$res['points'] * 0.4;
            }
        }

        $totalBatchPoints = array_sum($batchScores);
        $doughnutData = [];
        foreach ($batchScores as $code => $pts) {
            $name = $archetypeMap[$code];
            $doughnutData[$name] = $totalBatchPoints > 0 ? round(($pts / $totalBatchPoints) * 100) : 25;
        }

        // 6. Tính Similarity Data (Kèm lý do)
        // Lấy tất cả các tag user đã chọn
        $userTags = [];
        foreach ($responses as $res) {
            $t = $res['cost_tag'];
            if (!isset($userTags[$t])) $userTags[$t] = 0;
            $userTags[$t] += (float)$res['points'];
        }

        // Định nghĩa các nhóm tag đặc trưng cho từng Archetype để phân tích lý do
        $archetypeTags = [
            '3A1' => ['Dấn thân', 'Kỷ luật'],          // Chủ động
            '3A2' => ['Kỷ luật', 'Hình ảnh'],          // Thận trọng
            '3B1' => ['Quan hệ', 'Hình ảnh'],          // Thụ động
            '3B2' => ['Tiện lợi', 'Quan hệ']           // Cá nhân
        ];

        $similarityData = [];
        foreach ($batchScores as $code => $pts) {
            $name = $archetypeMap[$code];
            $perc = $totalBatchPoints > 0 ? round(($pts / $totalBatchPoints) * 100) : 25;
            
            // Tìm 3 lý do (cost_tags) có điểm cao nhất của người dùng MÀ phù hợp với Archetype này
            $reasons = [];
            $relevantTags = [];
            
            $allowedTags = $archetypeTags[$code] ?? [];
            foreach ($allowedTags as $t) {
                if (isset($userTags[$t])) {
                    $relevantTags[$t] = $userTags[$t];
                }
            }
            
            // Nếu user không có tag nào thuộc nhóm này, lấy tag cao nhất chung của họ để bù vào
            if (empty($relevantTags)) {
                $relevantTags = $userTags;
            }

            arsort($relevantTags);
            $topTags = array_slice($relevantTags, 0, 3, true);
            
            $sumTop = array_sum($topTags);
            foreach ($topTags as $tagName => $tagScore) {
                // Tỷ lệ % của lý do đó trong nhóm top 3 lý do (để tổng 3 lý do luôn bằng 100%)
                $reasons[$tagName] = $sumTop > 0 ? round(($tagScore / $sumTop) * 100) : 33; 
            }

            // Đảm bảo tổng % lý do = 100% (bù sai số làm tròn)
            if (!empty($reasons) && array_sum($reasons) != 100) {
                $keys = array_keys($reasons);
                $reasons[$keys[0]] += 100 - array_sum($reasons);
            }

            $similarityData[] = [
                'name' => $name . " ($code)",
                'percentage' => $perc,
                'reasons' => $reasons
            ];
        }

        return [
            'totalScore' => $totalScore,
            'maxScore' => $maxScore,
            'radarData' => $radarData,
            'doughnutData' => $doughnutData,
            'similarityData' => $similarityData,
            'groupName' => $mainGroup
        ];
    }
}
