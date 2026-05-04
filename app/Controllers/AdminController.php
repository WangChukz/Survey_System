<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\AnalyticsModel;
use App\Models\AdminQuestionModel;

class AdminController extends Controller
{
    private AnalyticsModel $analyticsModel;
    private AdminQuestionModel $adminQuestionModel;

    public function __construct()
    {
        // Kiểm tra Auth: Nếu chưa đăng nhập, đá về trang Login
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: " . BASE_URL . "/admin/login");
            exit;
        }

        $db = (new \Core\Database())->getConnection();
        $this->analyticsModel = new AnalyticsModel($db);
        $this->adminQuestionModel = new AdminQuestionModel();
    }

    /**
     * Hiển thị trang Admin Dashboard
     */
    public function dashboard(): void
    {
        // 1. Lấy thống kê chung
        $generalStats = $this->analyticsModel->getGeneralStats();

        // 2. Lấy dữ liệu phân bổ theo Khoa
        $facultyDistribution = $this->analyticsModel->getParticipantCountByFaculty();
        
        // Cấu trúc lại để Chart.js dễ đọc (labels, data)
        $facultyChartData = [
            'labels' => [],
            'data' => []
        ];
        foreach ($facultyDistribution as $row) {
            $facultyChartData['labels'][] = $row['faculty'] ?: 'Khác';
            $facultyChartData['data'][] = $row['student_count'];
        }

        // 3. Lấy dữ liệu điểm trung bình theo Khoa
        $facultyScores = $this->analyticsModel->getAverageScoreByFaculty();
        $scoreChartData = [
            'labels' => [],
            'data' => []
        ];
        foreach ($facultyScores as $row) {
            $scoreChartData['labels'][] = $row['faculty'] ?: 'Khác';
            $scoreChartData['data'][] = $row['avg_score'];
        }

        // 4. Lấy dữ liệu xu hướng hành vi (Radar Chart)
        $traits = $this->analyticsModel->getBehavioralTraitsGlobal();
        $traitsChartData = [
            'labels' => array_keys($traits),
            'data' => array_values($traits)
        ];

        // Gửi dữ liệu xuống View
        $this->render('admin/dashboard', [
            'maxWidth' => 'max-w-full', // Nới rộng toàn màn hình cho Admin
            'generalStats' => $generalStats,
            'facultyChartData' => json_encode($facultyChartData, JSON_UNESCAPED_UNICODE),
            'scoreChartData' => json_encode($scoreChartData, JSON_UNESCAPED_UNICODE),
            'traitsChartData' => json_encode($traitsChartData, JSON_UNESCAPED_UNICODE)
        ], 'admin_layout');
    }

    /**
     * Hiển thị Form thêm câu hỏi
     */
    public function addQuestion(): void
    {
        $this->render('admin/add_question', [
            'maxWidth' => 'max-w-4xl'
        ], 'admin_layout');
    }

    /**
     * Xử lý POST thêm câu hỏi và đáp án vào Database
     */
    public function storeQuestion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $surveyId = 1; // Mặc định dùng Survey 1
            $batchCode = $_POST['batch_code'] ?? '1';
            $content = $_POST['content'] ?? '';
            $questionType = $_POST['question_type'] ?? 'SC';

            // 1. Tạo Question lấy ID
            $questionId = $this->adminQuestionModel->createQuestion($surveyId, $batchCode, $content, $questionType);

            // 2. Thu thập Options từ form động
            $options = [];
            $optTexts = $_POST['option_text'] ?? [];
            $optPoints = $_POST['option_points'] ?? [];
            $optTags = $_POST['option_cost_tag'] ?? [];

            for ($i = 0; $i < count($optTexts); $i++) {
                if (!empty(trim($optTexts[$i]))) {
                    $options[] = [
                        'option_text' => trim($optTexts[$i]),
                        'points' => (float)($optPoints[$i] ?? 0),
                        'cost_tag' => trim($optTags[$i] ?? '')
                    ];
                }
            }

            // 3. Tạo Options
            if (!empty($options)) {
                $this->adminQuestionModel->createOptions($questionId, $options);
            }

            // Redirect về trang thêm kèm thông báo thành công
            header('Location: ' . BASE_URL . '/admin/questions/add?success=1');
            exit;
        }
    }
}
