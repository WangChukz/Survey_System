CREATE DATABASE IF NOT EXISTS `survey_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `survey_system`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table: surveys
DROP TABLE IF EXISTS `surveys`;
CREATE TABLE `surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table: participants
DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participants_student_code_unique` (`student_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table: questions
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` bigint(20) unsigned NOT NULL,
  `batch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('SC','MC','Likert') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_survey_id_foreign` (`survey_id`),
  CONSTRAINT `questions_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table: answer_options
DROP TABLE IF EXISTS `answer_options`;
CREATE TABLE `answer_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `option_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` double(8,2) NOT NULL DEFAULT 0.00,
  `cost_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answer_options_question_id_foreign` (`question_id`),
  CONSTRAINT `answer_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table: attempts
DROP TABLE IF EXISTS `attempts`;
CREATE TABLE `attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `participant_id` bigint(20) unsigned NOT NULL,
  `survey_id` bigint(20) unsigned NOT NULL,
  `current_batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_score` double(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attempts_participant_id_foreign` (`participant_id`),
  KEY `attempts_survey_id_foreign` (`survey_id`),
  CONSTRAINT `attempts_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attempts_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table: attempt_answers
DROP TABLE IF EXISTS `attempt_answers`;
CREATE TABLE `attempt_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attempt_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `response_time_ms` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attempt_answers_attempt_id_foreign` (`attempt_id`),
  KEY `attempt_answers_question_id_foreign` (`question_id`),
  CONSTRAINT `attempt_answers_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attempt_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table: attempt_answer_options
DROP TABLE IF EXISTS `attempt_answer_options`;
CREATE TABLE `attempt_answer_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attempt_answer_id` bigint(20) unsigned NOT NULL,
  `answer_option_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attempt_answer_options_attempt_answer_id_foreign` (`attempt_answer_id`),
  KEY `attempt_answer_options_answer_option_id_foreign` (`answer_option_id`),
  CONSTRAINT `attempt_answer_options_answer_option_id_foreign` FOREIGN KEY (`answer_option_id`) REFERENCES `answer_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attempt_answer_options_attempt_answer_id_foreign` FOREIGN KEY (`attempt_answer_id`) REFERENCES `attempt_answers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Table: results
DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attempt_id` bigint(20) unsigned NOT NULL,
  `final_group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `results_attempt_id_foreign` (`attempt_id`),
  CONSTRAINT `results_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Table: result_metrics
DROP TABLE IF EXISTS `result_metrics`;
CREATE TABLE `result_metrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `result_id` bigint(20) unsigned NOT NULL,
  `cost_tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_tag_points` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `result_metrics_result_id_foreign` (`result_id`),
  CONSTRAINT `result_metrics_result_id_foreign` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- DATA SEEDING (Dữ liệu mẫu)
-- --------------------------------------------------------

-- Seed: surveys
INSERT INTO `surveys` (`id`, `title`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bài Test Đánh Giá Hành Vi Mạng Xã Hội (SJT)', 'Bài đánh giá tình huống có phân nhánh 7 lô (Lô 1, 2A, 2B...).', 1, NOW(), NOW());

-- Seed: questions
INSERT INTO `questions` (`id`, `survey_id`, `batch_code`, `content`, `question_type`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 'Q01: Bạn thấy một sinh viên khóa dưới đang loay hoay tìm phòng thi lúc sắp đến giờ thi. Nếu bạn dừng lại chỉ đường, bạn có thể bị muộn buổi thuyết trình của nhóm mình. Bạn sẽ làm gì?', 'SC', NOW(), NOW()),
(2, 1, '1', 'Q02: Trong một cuộc khảo sát đánh giá giảng viên, phần lớn lớp thống nhất đánh giá Tốt để thầy dễ tính qua môn, dù thầy dạy rất hời hợt. Bạn sẽ đánh giá thế nào?', 'SC', NOW(), NOW()),
(3, 1, '1', 'Q03: Bạn vô tình phát hiện đề thi bị rò rỉ trong nhóm chat kín của lớp. Nếu báo cáo, bạn có thể bị tẩy chay. Bạn chọn cách nào?', 'SC', NOW(), NOW()),
(4, 1, '1', 'Q04: Khi đang chạy deadline cá nhân sát giờ, một bạn cùng nhóm nhờ bạn hướng dẫn lại phần mềm do bạn đó bị ốm mấy hôm trước. Bạn phản ứng ra sao?', 'SC', NOW(), NOW()),
(5, 1, '1', 'Q05: Bạn sẽ quyết định đứng ra giải quyết một cuộc xung đột trong nhóm khi nào? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(6, 1, '2A', 'Q06: Bạn nhận ra quy định mới của CLB có lỗ hổng gây bất công cho tân sinh viên, nhưng Chủ nhiệm CLB là một người rất bảo thủ và có quyền lực. Bạn xử lý thế nào?', 'SC', NOW(), NOW()),
(7, 1, '2A', 'Q07: Bạn được giao một công việc chung của nhóm nhưng lại thiếu chuyên môn về phần đó. Nếu làm sai sẽ ảnh hưởng cả nhóm. Bạn sẽ:', 'SC', NOW(), NOW()),
(8, 1, '2A', 'Q08: Một sự kiện quan trọng do bạn tổ chức đang diễn ra thì có sự cố mất điện. Không ai biết nguyên nhân. Bạn là người đầu tiên phát hiện tủ điện bị chập. Bạn sẽ:', 'SC', NOW(), NOW()),
(9, 1, '2A', 'Q09: Đang thảo luận nhóm, hai thành viên xảy ra tranh cãi gay gắt dẫn đến công kích cá nhân. Cả nhóm đều im lặng. Bạn sẽ:', 'SC', NOW(), NOW()),
(10, 1, '2A', 'Q10: Những rủi ro nào khiến bạn e ngại nhất khi đóng góp một ý tưởng đột phá nhưng đi ngược lại truyền thống của khoa? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(11, 1, '2B', 'Q11: Một dự án tình nguyện mời bạn tham gia với vai trò truyền thông. Công việc này chiếm khá nhiều thời gian nhưng có thể dùng để làm đẹp CV xin việc sau này. Bạn phản ứng sao?', 'SC', NOW(), NOW()),
(12, 1, '2B', 'Q12: Bạn thấy một người đánh rơi ví trên xe buýt. Có nhiều người xung quanh cũng nhìn thấy nhưng không ai lên tiếng. Bạn sẽ làm gì?', 'SC', NOW(), NOW()),
(13, 1, '2B', 'Q13: Nhóm trưởng phân công một việc cho bạn nhưng lại là việc bạn rất ghét làm. Bạn xử lý thế nào?', 'SC', NOW(), NOW()),
(14, 1, '2B', 'Q14: Lớp trưởng kêu gọi đóng quỹ để giúp đỡ một sinh viên cùng khóa gặp tai nạn. Bạn không thân với người này lắm. Quyết định của bạn là:', 'SC', NOW(), NOW()),
(15, 1, '2B', 'Q15: Trong các tình huống sau, điều gì sẽ khiến bạn từ chối giúp đỡ một người xa lạ trên đường? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(16, 1, '3A1', 'Q16: Bạn đang làm Leader của một dự án lớn nhưng tiến độ đang chậm do một số bạn quá tải. Bạn sẽ làm gì để cứu dự án?', 'SC', NOW(), NOW()),
(17, 1, '3A1', 'Q17: Bạn đại diện lớp đi kiến nghị về cơ sở vật chất kém, nhưng nhà trường từ chối gặp và đưa ra những lý do vòng vo. Bạn sẽ hành động ra sao tiếp theo?', 'SC', NOW(), NOW()),
(18, 1, '3A1', 'Q18: Một bài đăng bôi nhọ sai sự thật về một giảng viên trong khoa lan truyền trên mạng. Mọi người đều chia sẻ. Dù không thân với thầy, bạn sẽ làm gì?', 'SC', NOW(), NOW()),
(19, 1, '3A1', 'Q19: Dự án xã hội do bạn khởi xướng bị thất bại và gây thiệt hại một khoản tiền nhỏ của quỹ lớp. Bạn sẽ giải quyết thế nào?', 'SC', NOW(), NOW()),
(20, 1, '3A1', 'Q20: Bạn thường áp dụng những cách thức nào để thúc đẩy nhóm làm việc hiệu quả? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(21, 1, '3A2', 'Q21: Có người tố cáo nặc danh rằng quỹ CLB bị thâm hụt. Bạn là thủ quỹ nhưng bạn biết rõ số tiền đó được Chủ nhiệm tạm ứng cho việc chung (nhưng chưa có hóa đơn). Bạn sẽ:', 'SC', NOW(), NOW()),
(22, 1, '3A2', 'Q22: Nhóm trưởng đưa ra một quyết định mà bạn thấy có rủi ro pháp lý/nội quy, nhưng cả nhóm đều đã đồng ý vì nó mang lại lợi nhuận cao cho dự án môn học. Bạn sẽ:', 'SC', NOW(), NOW()),
(23, 1, '3A2', 'Q23: Bạn muốn báo cáo một sinh viên thường xuyên nhờ người thi hộ, nhưng bạn không có bằng chứng cụ thể ngoài việc "nhìn thấy bằng mắt". Bạn sẽ:', 'SC', NOW(), NOW()),
(24, 1, '3A2', 'Q24: Bạn được mời làm diễn giả cho một chương trình từ thiện nhưng chưa hiểu rõ về tổ chức đằng sau nó. Bạn sẽ:', 'SC', NOW(), NOW()),
(25, 1, '3A2', 'Q25: Khi phát hiện sai sót nhỏ trong sổ sách quỹ lớp mà bạn là người kiểm duyệt, bạn sẽ hành động theo những hướng nào? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(26, 1, '3B1', 'Q26: Nhóm bạn hẹn nhau cùng cúp học để đi xem phim. Bạn thực sự không muốn cúp học nhưng cũng không muốn bị chê là "kẻ phá đám". Bạn chọn:', 'SC', NOW(), NOW()),
(27, 1, '3B1', 'Q27: Khi thấy bạn bè mình liên tục xả rác tại khu dã ngoại, bạn cũng có rác trên tay. Hành động của bạn là:', 'SC', NOW(), NOW()),
(28, 1, '3B1', 'Q28: Trong buổi họp kín, mọi người đều đồng tình loại một thành viên ra khỏi nhóm dù người đó không có lỗi lớn. Bạn nghĩ người đó bị oan. Bạn sẽ:', 'SC', NOW(), NOW()),
(29, 1, '3B1', 'Q29: Thấy một người bạn mượn xe của mình mà không đổ xăng nhiều lần, bạn rất khó chịu. Phản ứng của bạn:', 'SC', NOW(), NOW()),
(30, 1, '3B1', 'Q30: Bạn thường bị thuyết phục tham gia các hoạt động ngoại khóa bởi những lý do nào? (Chọn nhiều đáp án)', 'MC', NOW(), NOW()),
(31, 1, '3B2', 'Q31: Đi trên đường thấy một nắp cống bị mất, có thể gây nguy hiểm. Bạn có dừng lại báo cho cơ quan chức năng hoặc đặt vật cảnh báo không?', 'SC', NOW(), NOW()),
(32, 1, '3B2', 'Q32: Bạn nhặt được một chiếc điện thoại đắt tiền nhưng đã khóa màn hình. Xung quanh không có ai. Bạn sẽ làm gì?', 'SC', NOW(), NOW()),
(33, 1, '3B2', 'Q33: Nhóm trưởng không phân chia công việc rõ ràng dẫn đến bạn phải làm quá nhiều. Khi nộp bài lấy điểm chung, bạn sẽ:', 'SC', NOW(), NOW()),
(34, 1, '3B2', 'Q34: Thấy một đám đánh nhau ngoài cổng trường, có nguy cơ gây thương tích nghiêm trọng. Bạn phản ứng ra sao?', 'SC', NOW(), NOW()),
(35, 1, '3B2', 'Q35: Khi đối mặt với yêu cầu sinh viên đóng góp thêm công sức, phản ứng thường thấy của bạn là gì? (Chọn nhiều đáp án)', 'MC', NOW(), NOW());

-- Seed: answer_options (Không gán cứng ID để tự động AUTO_INCREMENT)
INSERT INTO `answer_options` (`question_id`, `option_text`, `points`, `cost_tag`, `created_at`, `updated_at`) VALUES
(1, 'A. Chỉ đường cặn kẽ và chấp nhận bị muộn thuyết trình.', 4, 'Dấn thân', NOW(), NOW()),
(1, 'B. Báo với giảng viên mình sẽ đến muộn do bận việc để tránh bị trừ điểm.', 2, 'Kỷ luật', NOW(), NOW()),
(1, 'C. Chỉ qua loa rồi chạy đi vì không muốn nhóm phàn nàn mình.', 1, 'Quan hệ', NOW(), NOW()),
(1, 'D. Giả vờ không nghe thấy vì bài thuyết trình của mình quan trọng hơn.', 0, 'Tiện lợi', NOW(), NOW()),
(2, 'A. Đánh giá đúng sự thật để cải thiện chất lượng dạy học của trường.', 4, 'Dấn thân', NOW(), NOW()),
(2, 'B. Đánh giá Tốt vì ai cũng làm thế, sợ đánh giá kém sẽ bị truy ra.', 1, 'Quan hệ', NOW(), NOW()),
(2, 'C. Đánh giá Tốt để thầy vui vẻ và cho điểm cao, đôi bên cùng có lợi.', 0, 'Tiện lợi', NOW(), NOW()),
(2, 'D. Để trống hoặc tick đại cho xong nhiệm vụ quy định.', 2, 'Kỷ luật', NOW(), NOW()),
(3, 'A. Báo cáo với nhà trường để đảm bảo công bằng cho kỳ thi.', 4, 'Kỷ luật', NOW(), NOW()),
(3, 'B. Nhắn tin riêng cho lớp trưởng yêu cầu giải quyết để tránh ồn ào.', 3, 'Hình ảnh', NOW(), NOW()),
(3, 'C. Im lặng không nói gì vì sợ bị lớp tẩy chay.', 1, 'Quan hệ', NOW(), NOW()),
(3, 'D. Im lặng và tranh thủ tải đề về học để được điểm cao.', 0, 'Tiện lợi', NOW(), NOW()),
(4, 'A. Thức khuya hướng dẫn tận tình để bạn ấy theo kịp tiến độ.', 4, 'Dấn thân', NOW(), NOW()),
(4, 'B. Gửi tài liệu và bảo bạn ấy tự xem trước vì sợ trễ deadline của mình.', 2, 'Kỷ luật', NOW(), NOW()),
(4, 'C. Cố giúp để bạn ấy thấy mình là người nhiệt tình, giỏi giang.', 3, 'Hình ảnh', NOW(), NOW()),
(4, 'D. Lờ đi tin nhắn hoặc nói dối là mình đang không cầm máy tính.', 0, 'Tiện lợi', NOW(), NOW()),
(5, 'A. Khi thấy vấn đề vi phạm nghiêm trọng nội quy chung.', 4, 'Kỷ luật', NOW(), NOW()),
(5, 'B. Khi hai bên tranh cãi đều là những người bạn rất thân của mình.', 3, 'Quan hệ', NOW(), NOW()),
(5, 'C. Khi thấy không ai chịu làm và công việc chung sắp đổ vỡ.', 4, 'Dấn thân', NOW(), NOW()),
(5, 'D. Khi bản thân có thể giải quyết nhanh gọn mà không tốn công sức.', 1, 'Tiện lợi', NOW(), NOW()),
(6, 'A. Viết một báo cáo phân tích rủi ro chi tiết gửi cho Ban Giám hiệu.', 4, 'Kỷ luật', NOW(), NOW()),
(6, 'B. Dành thời gian nói chuyện riêng với Chủ nhiệm để thuyết phục.', 4, 'Dấn thân', NOW(), NOW()),
(6, 'C. Đăng một bài ẩn danh trên group trường để tạo sức ép.', 2, 'Hình ảnh', NOW(), NOW()),
(6, 'D. Im lặng vì không muốn đụng chạm đến quyền lợi của Chủ nhiệm.', 1, 'Quan hệ', NOW(), NOW()),
(7, 'A. Báo cáo ngay cho nhóm để phân lại việc, dù bị mắng là kém cỏi.', 4, 'Kỷ luật', NOW(), NOW()),
(7, 'B. Thức đêm tự học cấp tốc để làm cho xong, không muốn ai thất vọng.', 3, 'Hình ảnh', NOW(), NOW()),
(7, 'C. Nhờ một bạn khác trong nhóm làm hộ để giữ hòa khí.', 1, 'Quan hệ', NOW(), NOW()),
(7, 'D. Cứ làm bừa, sai thì bảo do phần này khó quá.', 0, 'Tiện lợi', NOW(), NOW()),
(8, 'A. Lập tức chạy đi tìm đồ nghề sửa tạm hoặc gọi thợ khẩn cấp.', 4, 'Dấn thân', NOW(), NOW()),
(8, 'B. Báo ngay cho Trưởng ban tổ chức để họ quyết định.', 3, 'Kỷ luật', NOW(), NOW()),
(8, 'C. Giữ im lặng và vờ như mình không biết để tránh bị đổ lỗi làm hỏng.', 2, 'Hình ảnh', NOW(), NOW()),
(8, 'D. Đi tìm nhóm bạn thân để rủ họ ra về sớm cho khỏe.', 0, 'Tiện lợi', NOW(), NOW()),
(9, 'A. Chủ động đứng ra can ngăn và yêu cầu cả hai tập trung vào công việc.', 4, 'Dấn thân', NOW(), NOW()),
(9, 'B. Báo cho lớp trưởng hoặc cố vấn học tập giải quyết theo quy định.', 3, 'Kỷ luật', NOW(), NOW()),
(9, 'C. Cố gắng dỗ dành từng người để giữ hòa khí nhóm.', 2, 'Quan hệ', NOW(), NOW()),
(9, 'D. Đeo tai nghe vào làm việc riêng, tranh cãi chán rồi họ tự im.', 0, 'Tiện lợi', NOW(), NOW()),
(10, 'A. Sợ bị các thầy cô lớn tuổi đánh giá là nổi loạn, chơi trội.', 2, 'Hình ảnh', NOW(), NOW()),
(10, 'B. Sợ ý tưởng thất bại sẽ làm lãng phí quỹ thời gian cá nhân.', 1, 'Tiện lợi', NOW(), NOW()),
(10, 'C. Lo rằng ý tưởng thiếu tính thực tiễn và vi phạm các quy chế.', 3, 'Kỷ luật', NOW(), NOW()),
(10, 'D. Sợ bạn bè chê cười và xa lánh vì mình quá khác biệt.', 2, 'Quan hệ', NOW(), NOW()),
(11, 'A. Tham gia và cố gắng làm thật tốt để PR cho dự án.', 3, 'Dấn thân', NOW(), NOW()),
(11, 'B. Tham gia chủ yếu để xin cấp giấy chứng nhận làm đẹp CV.', 2, 'Hình ảnh', NOW(), NOW()),
(11, 'C. Hỏi xem bạn bè có ai tham gia không thì mới đi cùng cho vui.', 1, 'Quan hệ', NOW(), NOW()),
(11, 'D. Từ chối ngay vì tốn quá nhiều thời gian, làm việc khác sướng hơn.', 0, 'Tiện lợi', NOW(), NOW()),
(12, 'A. Chạy tới nhặt lên và hô to xem ai là người đánh rơi.', 4, 'Dấn thân', NOW(), NOW()),
(12, 'B. Đưa ví cho tài xế xe buýt xử lý theo quy định.', 3, 'Kỷ luật', NOW(), NOW()),
(12, 'C. Chờ xem có ai nhặt không, không thì mình mới nhặt đưa công an.', 1, 'Hình ảnh', NOW(), NOW()),
(12, 'D. Vờ như không thấy vì sợ nhặt lên người ta lại bảo mình ăn cắp.', 0, 'Quan hệ', NOW(), NOW()),
(13, 'A. Chấp nhận làm và tìm hiểu để làm thật tốt vì trách nhiệm nhóm.', 4, 'Dấn thân', NOW(), NOW()),
(13, 'B. Cứ làm nhưng làm đối phó cho có lệ.', 1, 'Kỷ luật', NOW(), NOW()),
(13, 'C. Cằn nhằn với các thành viên khác nhưng vẫn làm để khỏi bị nói.', 2, 'Hình ảnh', NOW(), NOW()),
(13, 'D. Xin đổi việc hoặc kiếm cớ bận để thoái thác ngay lập tức.', 0, 'Tiện lợi', NOW(), NOW()),
(14, 'A. Đóng góp trong khả năng và kêu gọi thêm trên trang cá nhân.', 4, 'Dấn thân', NOW(), NOW()),
(14, 'B. Đóng mức cơ bản giống như đa số các bạn khác cho xong.', 2, 'Quan hệ', NOW(), NOW()),
(14, 'C. Đóng mức thật cao để mọi người trầm trồ và khen ngợi mình.', 3, 'Hình ảnh', NOW(), NOW()),
(14, 'D. Viện lý do đang hết tiền để trốn đóng quỹ.', 0, 'Tiện lợi', NOW(), NOW()),
(15, 'A. Người đó trông có vẻ khả nghi, sợ bị lừa đảo dàn cảnh.', 3, 'Kỷ luật', NOW(), NOW()),
(15, 'B. Đang vội đi chơi hoặc có việc cá nhân cần ưu tiên.', 1, 'Tiện lợi', NOW(), NOW()),
(15, 'C. Sợ giúp sai cách sẽ khiến mọi người xung quanh chỉ trích mình.', 2, 'Hình ảnh', NOW(), NOW()),
(15, 'D. Bạn bè đi cùng khuyên không nên xen vào chuyện bao đồng.', 2, 'Quan hệ', NOW(), NOW()),
(16, 'A. Nhận lại một phần việc của các bạn và cùng làm xuyên đêm.', 4, 'Dấn thân', NOW(), NOW()),
(16, 'B. Đề nghị dời deadline với cấp trên theo đúng quy trình báo cáo.', 3, 'Kỷ luật', NOW(), NOW()),
(16, 'C. Trách mắng các bạn và ép họ phải hoàn thành bằng mọi giá để mình không bị chê.', 2, 'Hình ảnh', NOW(), NOW()),
(16, 'D. Để mặc dự án thất bại và đổ lỗi hoàn toàn cho sự yếu kém của các bạn.', 0, 'Tiện lợi', NOW(), NOW()),
(17, 'A. Viết thư tay gửi thẳng lên Hiệu trưởng hoặc Bộ Giáo dục.', 4, 'Dấn thân', NOW(), NOW()),
(17, 'B. Làm đơn kiến nghị theo đúng form mẫu và thu thập đủ 1000 chữ ký.', 4, 'Kỷ luật', NOW(), NOW()),
(17, 'C. Lên các trang Confession than thở ẩn danh để tạo dư luận.', 2, 'Hình ảnh', NOW(), NOW()),
(17, 'D. Từ bỏ ý định vì nghĩ mình chỉ là sinh viên "thấp cổ bé họng".', 1, 'Quan hệ', NOW(), NOW()),
(18, 'A. Lên tiếng đính chính ngay trên bài viết đó bằng nick thật.', 4, 'Dấn thân', NOW(), NOW()),
(18, 'B. Chụp màn hình gửi cho Ban Cán sự lớp để họ báo cáo nhà trường.', 3, 'Kỷ luật', NOW(), NOW()),
(18, 'C. Báo cáo bài viết ẩn danh rồi chặn trang đó lại.', 2, 'Hình ảnh', NOW(), NOW()),
(18, 'D. Ngồi xem comment hóng hớt, coi như câu chuyện giải trí.', 0, 'Tiện lợi', NOW(), NOW()),
(19, 'A. Công khai nhận lỗi trước lớp và xin tự bỏ tiền túi ra đền bù.', 4, 'Dấn thân', NOW(), NOW()),
(19, 'B. Cố gắng giấu nhẹm khoản thâm hụt và dùng tiền riêng lấp vào.', 2, 'Hình ảnh', NOW(), NOW()),
(19, 'C. Lên nhóm xin lỗi qua loa và bảo mọi người thông cảm cùng chịu thiệt.', 1, 'Quan hệ', NOW(), NOW()),
(19, 'D. Đổ lỗi cho các nguyên nhân khách quan để trốn tránh đền tiền.', 0, 'Tiện lợi', NOW(), NOW()),
(20, 'A. Đưa ra các quy định thưởng phạt phân minh và giám sát chặt chẽ.', 4, 'Kỷ luật', NOW(), NOW()),
(20, 'B. Chủ động làm mẫu và nhận những phần việc khó nhất về mình.', 4, 'Dấn thân', NOW(), NOW()),
(20, 'C. Thường xuyên tổ chức ăn uống, khen ngợi để tạo không khí vui vẻ.', 3, 'Quan hệ', NOW(), NOW()),
(20, 'D. Cho phép các thành viên linh hoạt thời gian, miễn là nộp bài.', 2, 'Tiện lợi', NOW(), NOW()),
(21, 'A. Nộp đầy đủ sổ sách chứng minh và yêu cầu một buổi họp minh bạch.', 4, 'Kỷ luật', NOW(), NOW()),
(21, 'B. Chủ động gặp Chủ nhiệm yêu cầu họ tự giải trình với mọi người.', 4, 'Dấn thân', NOW(), NOW()),
(21, 'C. Im lặng chờ Chủ nhiệm tự xử lý, nếu làm căng sẽ mất lòng sếp.', 1, 'Quan hệ', NOW(), NOW()),
(21, 'D. Xin nghỉ làm thủ quỹ để tránh xa rắc rối.', 0, 'Tiện lợi', NOW(), NOW()),
(22, 'A. Kiên quyết phản đối bằng văn bản, dù bị cả nhóm ghét bỏ.', 4, 'Kỷ luật', NOW(), NOW()),
(22, 'B. Đưa ra các phân tích rủi ro để cả nhóm cùng đánh giá lại.', 3, 'Dấn thân', NOW(), NOW()),
(22, 'C. Cứ đồng ý làm theo vì không muốn bị coi là người ngáng đường.', 1, 'Quan hệ', NOW(), NOW()),
(22, 'D. Đồng ý làm nhưng cố gắng lấy nhiều lợi nhuận nhất có thể về mình.', 0, 'Tiện lợi', NOW(), NOW()),
(23, 'A. Thu thập đủ bằng chứng rồi gửi lên Hội đồng kỷ luật.', 4, 'Kỷ luật', NOW(), NOW()),
(23, 'B. Gặp trực tiếp bạn đó và khuyên họ tự thú hoặc dừng lại.', 3, 'Dấn thân', NOW(), NOW()),
(23, 'C. Không báo cáo nhưng đem chuyện này đi kể xấu với bạn bè khác.', 1, 'Hình ảnh', NOW(), NOW()),
(23, 'D. Không làm gì cả vì không ảnh hưởng trực tiếp đến điểm số của mình.', 0, 'Tiện lợi', NOW(), NOW()),
(24, 'A. Dành 1 ngày tra cứu, thẩm định uy tín tổ chức trước khi nhận lời.', 4, 'Kỷ luật', NOW(), NOW()),
(24, 'B. Đồng ý luôn vì có thể lấy hình ảnh sự kiện để đánh bóng tên tuổi.', 2, 'Hình ảnh', NOW(), NOW()),
(24, 'C. Hỏi thăm ý kiến bạn bè, nếu họ khuyên đi thì đi.', 1, 'Quan hệ', NOW(), NOW()),
(24, 'D. Từ chối luôn cho khỏe, ở nhà nghỉ ngơi tốt hơn.', 0, 'Tiện lợi', NOW(), NOW()),
(25, 'A. Yêu cầu người giữ quỹ giải trình ngay lập tức theo đúng quy định.', 4, 'Kỷ luật', NOW(), NOW()),
(25, 'B. Nhắc nhở riêng tư để bạn đó sửa sai mà không bị bẽ mặt trước lớp.', 3, 'Hình ảnh', NOW(), NOW()),
(25, 'C. Chủ động cùng bạn đó rà soát lại toàn bộ hóa đơn để tìm nguyên nhân.', 4, 'Dấn thân', NOW(), NOW()),
(25, 'D. Nhờ một bạn khác xử lý giúp vì không muốn vướng rắc rối.', 1, 'Tiện lợi', NOW(), NOW()),
(26, 'A. Kiên quyết từ chối và đi học, khuyên các bạn nên đi học cùng.', 4, 'Kỷ luật', NOW(), NOW()),
(26, 'B. Từ chối đi nhưng bảo mình bị ốm để các bạn không giận.', 2, 'Hình ảnh', NOW(), NOW()),
(26, 'C. Miễn cưỡng cúp học đi theo vì sợ lần sau các bạn không chơi cùng.', 0, 'Quan hệ', NOW(), NOW()),
(26, 'D. Đồng ý đi luôn vì cũng đang lười học.', 0, 'Tiện lợi', NOW(), NOW()),
(27, 'A. Nhặt rác của mình bỏ vào thùng, và nhặt luôn cả phần của bạn bè.', 4, 'Dấn thân', NOW(), NOW()),
(27, 'B. Bỏ rác của mình vào thùng, nhưng không nói gì các bạn.', 3, 'Kỷ luật', NOW(), NOW()),
(27, 'C. Lén giấu rác vào bụi cây để không ai thấy.', 1, 'Hình ảnh', NOW(), NOW()),
(27, 'D. Vứt luôn xuống đất giống bạn bè cho tiện.', 0, 'Quan hệ', NOW(), NOW()),
(28, 'A. Đứng lên phản đối mạnh mẽ và đưa ra lý lẽ bảo vệ người đó.', 4, 'Dấn thân', NOW(), NOW()),
(28, 'B. Yêu cầu xem xét lại theo đúng quy định của nhóm.', 3, 'Kỷ luật', NOW(), NOW()),
(28, 'C. Đồng tình theo số đông để không bị nhóm ghét lây.', 1, 'Quan hệ', NOW(), NOW()),
(28, 'D. Im lặng không vote, kệ mọi chuyện ra sao thì ra.', 0, 'Tiện lợi', NOW(), NOW()),
(29, 'A. Thẳng thắn đưa ra quy tắc mượn xe phải tự đổ xăng rõ ràng.', 4, 'Kỷ luật', NOW(), NOW()),
(29, 'B. Nói đùa khéo léo để bạn ấy tự hiểu và tự đi đổ.', 3, 'Hình ảnh', NOW(), NOW()),
(29, 'C. Cứ bực mình nhưng không dám nói vì sợ sứt mẻ tình bạn.', 1, 'Quan hệ', NOW(), NOW()),
(29, 'D. Nói dối là xe bị hỏng để không cho mượn nữa.', 0, 'Tiện lợi', NOW(), NOW()),
(30, 'A. Được bạn bè thân thiết rủ rê, đi cùng cho có phong trào.', 2, 'Quan hệ', NOW(), NOW()),
(30, 'B. Có cơ hội nhận giấy khen hoặc làm đẹp hồ sơ cá nhân.', 2, 'Hình ảnh', NOW(), NOW()),
(30, 'C. Nội dung chương trình thực sự mang lại giá trị giải quyết vấn đề.', 4, 'Dấn thân', NOW(), NOW()),
(30, 'D. Hoạt động tổ chức gần nhà, thời gian ngắn, dễ dàng tham gia.', 1, 'Tiện lợi', NOW(), NOW()),
(31, 'A. Chặn đường lấy cây làm vật cản và gọi ngay đường dây nóng.', 4, 'Dấn thân', NOW(), NOW()),
(31, 'B. Chụp ảnh đăng lên mạng xã hội để cảnh báo mọi người.', 3, 'Hình ảnh', NOW(), NOW()),
(31, 'C. Đứng xem có ai bị lọt cống không để quay clip câu view.', 1, 'Hình ảnh', NOW(), NOW()),
(31, 'D. Lách xe né qua rồi đi thẳng về nhà ăn cơm cho nhanh.', 0, 'Tiện lợi', NOW(), NOW()),
(32, 'A. Mang đến đồn Công an gần nhất để giao nộp.', 4, 'Kỷ luật', NOW(), NOW()),
(32, 'B. Đăng lên Facebook trường để tìm người rơi và nhận lời cảm ơn.', 3, 'Hình ảnh', NOW(), NOW()),
(32, 'C. Để lại chỗ cũ, không động vào vì sợ bị dàn cảnh tống tiền.', 1, 'Quan hệ', NOW(), NOW()),
(32, 'D. Tháo sim, đem đi bẻ khóa bán lấy tiền xài.', 0, 'Tiện lợi', NOW(), NOW()),
(33, 'A. Đưa bảng đánh giá công việc rõ ràng để trừ điểm những người lười.', 4, 'Kỷ luật', NOW(), NOW()),
(33, 'B. Nhận hết công lao về mình trước mặt giảng viên.', 2, 'Hình ảnh', NOW(), NOW()),
(33, 'C. Phàn nàn nhưng cuối cùng vẫn cho mọi người điểm cao như nhau.', 1, 'Quan hệ', NOW(), NOW()),
(33, 'D. Xóa bớt tên các bạn ra khỏi báo cáo mà không nói lời nào.', 0, 'Tiện lợi', NOW(), NOW()),
(34, 'A. Hô hoán mọi người xung quanh cùng vào can ngăn.', 4, 'Dấn thân', NOW(), NOW()),
(34, 'B. Báo ngay cho bảo vệ trường hoặc công an phường.', 3, 'Kỷ luật', NOW(), NOW()),
(34, 'C. Đứng từ xa quay video để hóng biến.', 1, 'Hình ảnh', NOW(), NOW()),
(34, 'D. Chạy nhanh đi đường khác kẻo bị vạ lây.', 0, 'Tiện lợi', NOW(), NOW()),
(35, 'A. Tìm cách né tránh hoặc làm ở mức tối thiểu để đối phó.', 0, 'Tiện lợi', NOW(), NOW()),
(35, 'B. Chờ xem số đông các bạn trong lớp làm gì rồi mới làm theo.', 1, 'Quan hệ', NOW(), NOW()),
(35, 'C. Lên mạng xã hội đăng bài than vãn để tìm sự đồng cảm.', 1, 'Hình ảnh', NOW(), NOW()),
(35, 'D. Nghiên cứu kỹ quy định xem việc này có thực sự bắt buộc hay không.', 2, 'Kỷ luật', NOW(), NOW());

-- Seed: 20 Participants mô phỏng đa dạng các khoa
INSERT INTO `participants` (`id`, `student_code`, `fullname`, `faculty`, `created_at`, `updated_at`) VALUES
(101, '26A4010101', 'Nguyễn Văn Anh', 'Khoa Công nghệ thông tin và Kinh tế số', NOW(), NOW()),
(102, '26A4010102', 'Trần Thị Bình', 'Khoa Tài Chính', NOW(), NOW()),
(103, '26A4010103', 'Lê Hoàng Cường', 'Khoa Ngân Hàng', NOW(), NOW()),
(104, '26A4010104', 'Phạm Minh Đức', 'Khoa Kế toán - Kiểm toán', NOW(), NOW()),
(105, '26A4010105', 'Đỗ Thanh Hải', 'Khoa Quản trị kinh doanh', NOW(), NOW()),
(106, '26A4010106', 'Hoàng Thị Yến', 'Khoa Kinh doanh quốc tế', NOW(), NOW()),
(107, '26A4010107', 'Bùi Xuân Huấn', 'Khoa Luật Kinh tế', NOW(), NOW()),
(108, '26A4010108', 'Vũ Kim Liên', 'Khoa Ngoại ngữ', NOW(), NOW()),
(109, '26A4010109', 'Lý Tiểu Long', 'Khoa Kinh tế', NOW(), NOW()),
(110, '26A4010110', 'Ngô Kiến Huy', 'Khoa Công nghệ thông tin và Kinh tế số', NOW(), NOW()),
(111, '26A4010111', 'Phan Mạnh Quỳnh', 'Khoa Tài Chính', NOW(), NOW()),
(112, '26A4010112', 'Sơn Tùng M-TP', 'Khoa Ngân Hàng', NOW(), NOW()),
(113, '26A4010113', 'Đen Vâu', 'Khoa Kế toán - Kiểm toán', NOW(), NOW()),
(114, '26A4010114', 'Hòa Minzy', 'Khoa Quản trị kinh doanh', NOW(), NOW()),
(115, '26A4010115', 'Erik', 'Khoa Kinh doanh quốc tế', NOW(), NOW()),
(116, '26A4010116', 'Đức Phúc', 'Khoa Luật Kinh tế', NOW(), NOW()),
(117, '26A4010117', 'Suboi', 'Khoa Ngoại ngữ', NOW(), NOW()),
(118, '26A4010118', 'Karik', 'Khoa Kinh tế', NOW(), NOW()),
(119, '26A4010119', 'Binz', 'Khoa Công nghệ thông tin và Kinh tế số', NOW(), NOW()),
(120, '26A4010120', 'JustaTee', 'Khoa Tài Chính', NOW(), NOW());

-- Seed: 20 Attempts tương ứng (Trạng thái hoàn thành với các điểm số khác nhau)
INSERT INTO `attempts` (`id`, `participant_id`, `survey_id`, `current_batch`, `total_score`, `status`, `created_at`, `updated_at`) VALUES
(101, 101, 1, '3A1', 52.0, 'completed', NOW(), NOW()),
(102, 102, 1, '3A2', 45.0, 'completed', NOW(), NOW()),
(103, 103, 1, '3B1', 30.0, 'completed', NOW(), NOW()),
(104, 104, 1, '3B2', 22.0, 'completed', NOW(), NOW()),
(105, 105, 1, '3A1', 58.0, 'completed', NOW(), NOW()),
(106, 106, 1, '3A2', 41.0, 'completed', NOW(), NOW()),
(107, 107, 1, '3B1', 28.0, 'completed', NOW(), NOW()),
(108, 108, 1, '3B2', 15.0, 'completed', NOW(), NOW()),
(109, 109, 1, '3A1', 60.0, 'completed', NOW(), NOW()),
(110, 110, 1, '3A2', 38.0, 'completed', NOW(), NOW()),
(111, 111, 1, '3B1', 33.0, 'completed', NOW(), NOW()),
(112, 112, 1, '3A1', 55.0, 'completed', NOW(), NOW()),
(113, 113, 1, '3A2', 47.0, 'completed', NOW(), NOW()),
(114, 114, 1, '3B2', 18.0, 'completed', NOW(), NOW()),
(115, 115, 1, '3B1', 31.0, 'completed', NOW(), NOW()),
(116, 116, 1, '3A1', 49.0, 'completed', NOW(), NOW()),
(117, 117, 1, '3A2', 42.0, 'completed', NOW(), NOW()),
(118, 118, 1, '3B1', 25.0, 'completed', NOW(), NOW()),
(119, 119, 1, '3A1', 56.0, 'completed', NOW(), NOW()),
(120, 120, 1, '3A2', 44.0, 'completed', NOW(), NOW());

-- Seed: Một số dữ liệu câu trả lời mẫu cho các người dùng khác để Dashboard có dữ liệu thực tế
-- Participant 101
INSERT INTO `attempt_answers` (`id`, `attempt_id`, `question_id`, `created_at`) VALUES (101, 101, 1, NOW()), (102, 101, 2, NOW()), (103, 101, 5, NOW()), (104, 101, 16, NOW()), (105, 101, 17, NOW());
INSERT INTO `attempt_answer_options` (`attempt_answer_id`, `answer_option_id`) VALUES (101, 1), (102, 5), (103, 17), (104, 61), (105, 65);

-- Participant 102
INSERT INTO `attempt_answers` (`id`, `attempt_id`, `question_id`, `created_at`) VALUES (111, 102, 1, NOW()), (112, 102, 2, NOW()), (113, 102, 5, NOW()), (114, 102, 21, NOW()), (115, 102, 22, NOW());
INSERT INTO `attempt_answer_options` (`attempt_answer_id`, `answer_option_id`) VALUES (111, 2), (112, 6), (113, 18), (114, 81), (115, 85);

-- Participant 103
INSERT INTO `attempt_answers` (`id`, `attempt_id`, `question_id`, `created_at`) VALUES (121, 103, 1, NOW()), (122, 103, 2, NOW()), (123, 103, 5, NOW()), (124, 103, 26, NOW()), (125, 103, 27, NOW());
INSERT INTO `attempt_answer_options` (`attempt_answer_id`, `answer_option_id`) VALUES (121, 3), (122, 8), (123, 20), (124, 101), (125, 106);

-- Participant 104
INSERT INTO `attempt_answers` (`id`, `attempt_id`, `question_id`, `created_at`) VALUES (131, 104, 1, NOW()), (132, 104, 2, NOW()), (133, 104, 5, NOW()), (134, 104, 31, NOW()), (135, 104, 32, NOW());
INSERT INTO `attempt_answer_options` (`attempt_answer_id`, `answer_option_id`) VALUES (131, 4), (132, 7), (133, 20), (134, 125), (135, 129);

-- Bổ sung thêm một số dữ liệu rải rác để biểu đồ Radar Admin trông cân đối
-- Sử dụng các ID đã tồn tại ở trên
INSERT INTO `attempt_answer_options` (`attempt_answer_id`, `answer_option_id`) VALUES 
(101, 13), (101, 25), (111, 41), (112, 53), (121, 77), (122, 89), (131, 105), (131, 117), (131, 133), (131, 137);

SET FOREIGN_KEY_CHECKS = 1;