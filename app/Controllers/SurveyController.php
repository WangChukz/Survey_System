<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\QuestionModel;
use App\Models\ResponseModel;
use App\Services\AdaptiveLogic;
use App\Models\ResultModel;


class SurveyController extends Controller
{
    private QuestionModel $questionModel;
    private ResponseModel $responseModel;
    private ResultModel $resultModel;
    private AdaptiveLogic $adaptiveLogic;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Khởi tạo kết nối DB trực tiếp
        $db = (new \Core\Database())->getConnection();

        $this->questionModel = new QuestionModel($db);
        $this->responseModel = new ResponseModel($db);
        $this->resultModel = new ResultModel($db);
        
        // Khởi tạo Service (Tiêm Models vào Service)
        $this->adaptiveLogic = new AdaptiveLogic($this->responseModel);

        
        // Đảm bảo session hoạt động
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Hiển thị trang chủ (Nhập thông tin)
     */
    public function index(): void
    {
        $this->render('home/index');
    }

    /**
     * Khởi tạo bắt đầu bài khảo sát
     */
    public function startSurvey(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $faculty = trim($_POST['faculty'] ?? '');
            $studentCode = trim($_POST['student_code'] ?? '');

            if (empty($fullname) || empty($faculty) || empty($studentCode)) {
                die("Vui lòng nhập đầy đủ thông tin cá nhân.");
            }

            // Lấy ID sinh viên từ DB
            $participantId = $this->responseModel->getOrCreateParticipant($fullname, $faculty, $studentCode);
            $_SESSION['participant_id'] = $participantId;
            
            $surveyId = 1; // Khảo sát SJT mặc định

            // Kiểm tra xem đã có attempt nào chưa
            $existingAttempt = $this->responseModel->getAttemptByParticipant($participantId, $surveyId);

            if ($existingAttempt) {
                if ($existingAttempt['status'] === 'completed') {
                    // Đã làm xong -> Gợi ý xem lại kết quả
                    $_SESSION['error'] = 'Bạn đã hoàn thành bài khảo sát này trước đó.';
                    $_SESSION['can_view_result'] = true;
                    $_SESSION['attempt_id'] = $existingAttempt['id']; // Gán ID để trang kết quả có thể truy xuất
                    $this->redirect(BASE_URL . '/');
                    return;
                } else {
                    // Đang làm dở -> resume
                    $_SESSION['attempt_id'] = $existingAttempt['id'];
                    $this->redirect(BASE_URL . '/survey?batch=' . $existingAttempt['current_batch']);
                    return;
                }
            }
            
            // Tạo Attempt mới
            $attemptId = $this->responseModel->createAttempt($participantId, $surveyId);
            $_SESSION['attempt_id'] = $attemptId;
            
            $this->redirect(BASE_URL . '/survey?batch=1');
        }
    }

    /**
     * Hiển thị giao diện làm bài của một Lô câu hỏi.
     */
    public function showBatch(): void
    {
        if (!isset($_SESSION['attempt_id'])) {
            $this->redirect(BASE_URL . '/');
            return;
        }

        $batchId = $_GET['batch'] ?? '1';

        $questions = $this->questionModel->getQuestionsByBatch($batchId);
        
        if (empty($questions)) {
            die("Không tìm thấy dữ liệu cho Lô câu hỏi này.");
        }

        $this->render('survey/step', [
            'batchId' => $batchId,
            'questions' => $questions
        ]);
    }

    /**
     * Xử lý dữ liệu Submit của một Lô câu hỏi.
     */
    public function submitBatch(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['attempt_id'])) {
            $this->redirect(BASE_URL . '/');
            return;
        }

        $attemptId = (int)$_SESSION['attempt_id'];
        $currentBatchId = $_POST['batch_id'] ?? '';
        $answers = $_POST['answers'] ?? []; 
        $responseTimeMs = (int)($_POST['response_time_ms'] ?? 0);

        if (empty($currentBatchId) || empty($answers)) {
            die("Dữ liệu gửi lên không hợp lệ.");
        }

        // 1. Lưu kết quả
        foreach ($answers as $questionId => $optionIds) {
            $qId = (int)$questionId;
            $optionIds = !is_array($optionIds) ? [$optionIds] : $optionIds;
            $optionIds = array_map('intval', $optionIds);

            $this->responseModel->saveResponse($attemptId, $qId, $optionIds, $responseTimeMs);
        }

        // 2. Xác định lô tiếp theo thông qua Service rẽ nhánh
        $nextBatchId = $this->adaptiveLogic->determineNextBatch($attemptId, $currentBatchId);

        // 3. Điều hướng
        if ($nextBatchId !== null) {
            $this->redirect(BASE_URL . '/survey?batch=' . $nextBatchId);
        } else {
            $this->redirect(BASE_URL . '/result');
        }
    }

    /**
     * Hiển thị trang Kết quả (Dashboard)
     */
    public function showResult(): void
    {
        if (!isset($_SESSION['attempt_id'])) {
            $this->redirect(BASE_URL . '/');
            return;
        }

        $attemptId = $_SESSION['attempt_id'];
        $participant = $this->responseModel->getParticipantByAttempt($attemptId);
        
        // 1. Tính toán Insight thực tế từ DB
        $insights = $this->resultModel->getFullInsights($attemptId);

        if (empty($insights)) {
            die("Không có dữ liệu trả lời để tính toán kết quả.");
        }

        // 2. Cập nhật điểm tổng vào bảng attempts để Admin có thể xem
        $this->responseModel->updateFinalScore($attemptId, $insights['totalScore']);

        $this->render('result/dashboard', [
            'maxWidth' => 'max-w-7xl', 
            'participant' => $participant,
            'groupName' => $insights['groupName'],
            'totalScore' => $insights['totalScore'],
            'maxScore' => $insights['maxScore'],
            'radarData' => $insights['radarData'],
            'doughnutData' => $insights['doughnutData'],
            'similarityData' => $insights['similarityData']
        ]);
    }
}
