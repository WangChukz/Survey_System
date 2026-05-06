--CREATE DATABASE survey_system
USE survey_system;


-- 1. Table: surveys
CREATE TABLE [surveys] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [title] NVARCHAR(255) NOT NULL,
    [description] NVARCHAR(MAX) NULL,
    [is_active] BIT NOT NULL DEFAULT 1,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_surveys] PRIMARY KEY ([id])
);

-- 2. Table: participants
CREATE TABLE [participants] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [student_code] NVARCHAR(255) NOT NULL,
    [fullname] NVARCHAR(255) NOT NULL,
    [faculty] NVARCHAR(255) NOT NULL,
    [class_name] NVARCHAR(255) NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_participants] PRIMARY KEY ([id]),
    CONSTRAINT [UQ_participants_student_code] UNIQUE ([student_code])
);

-- 3. Table: questions
CREATE TABLE [questions] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [survey_id] BIGINT NOT NULL,
    [batch_code] NVARCHAR(255) NOT NULL,
    [content] NVARCHAR(MAX) NOT NULL,
    [question_type] VARCHAR(20) NOT NULL CHECK ([question_type] IN ('SC','MC')),
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_questions] PRIMARY KEY ([id]),
    CONSTRAINT [FK_questions_surveys] FOREIGN KEY ([survey_id]) REFERENCES [surveys] ([id]) ON DELETE CASCADE
);

-- 4. Table: answer_options
CREATE TABLE [answer_options] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [question_id] BIGINT NOT NULL,
    [option_text] NVARCHAR(MAX) NOT NULL,
    [points] DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    [cost_tag] NVARCHAR(255) NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_answer_options] PRIMARY KEY ([id]),
    CONSTRAINT [FK_answer_options_questions] FOREIGN KEY ([question_id]) REFERENCES [questions] ([id]) ON DELETE CASCADE
);

-- 5. Table: attempts
CREATE TABLE [attempts] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [participant_id] BIGINT NOT NULL,
    [survey_id] BIGINT NOT NULL,
    [current_batch] NVARCHAR(255) NULL,
    [total_score] DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    [status] VARCHAR(20) NOT NULL DEFAULT 'in_progress' CHECK ([status] IN ('in_progress','completed')),
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_attempts] PRIMARY KEY ([id]),
    CONSTRAINT [FK_attempts_participants] FOREIGN KEY ([participant_id]) REFERENCES [participants] ([id]) ON DELETE CASCADE,
    CONSTRAINT [FK_attempts_surveys] FOREIGN KEY ([survey_id]) REFERENCES [surveys] ([id]) -- KHÔNG THỂ CASCADE KÉP (SQL Server rule)
);

-- 6. Table: attempt_answers
CREATE TABLE [attempt_answers] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [attempt_id] BIGINT NOT NULL,
    [question_id] BIGINT NOT NULL,
    [response_time_ms] INT NOT NULL DEFAULT 0,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_attempt_answers] PRIMARY KEY ([id]),
    CONSTRAINT [FK_attempt_answers_attempts] FOREIGN KEY ([attempt_id]) REFERENCES [attempts] ([id]) ON DELETE CASCADE,
    CONSTRAINT [FK_attempt_answers_questions] FOREIGN KEY ([question_id]) REFERENCES [questions] ([id]) -- NO ACTION (Tránh lặp Cascade)
);

-- 7. Table: attempt_answer_options
CREATE TABLE [attempt_answer_options] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [attempt_answer_id] BIGINT NOT NULL,
    [answer_option_id] BIGINT NOT NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_attempt_answer_options] PRIMARY KEY ([id]),
    CONSTRAINT [FK_aao_attempt_answers] FOREIGN KEY ([attempt_answer_id]) REFERENCES [attempt_answers] ([id]) ON DELETE CASCADE,
    CONSTRAINT [FK_aao_answer_options] FOREIGN KEY ([answer_option_id]) REFERENCES [answer_options] ([id]) -- NO ACTION (Tránh lặp Cascade)
);

-- 8. Table: results
CREATE TABLE [results] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [attempt_id] BIGINT NOT NULL,
    [final_group_name] NVARCHAR(255) NULL,
    [description] NVARCHAR(MAX) NULL,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_results] PRIMARY KEY ([id]),
    CONSTRAINT [FK_results_attempts] FOREIGN KEY ([attempt_id]) REFERENCES [attempts] ([id]) ON DELETE CASCADE
);

-- 9. Table: result_metrics
CREATE TABLE [result_metrics] (
    [id] BIGINT IDENTITY(1,1) NOT NULL,
    [result_id] BIGINT NOT NULL,
    [cost_tag] NVARCHAR(255) NOT NULL,
    [total_tag_points] DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    [created_at] DATETIME2 NULL,
    [updated_at] DATETIME2 NULL,
    CONSTRAINT [PK_result_metrics] PRIMARY KEY ([id]),
    CONSTRAINT [FK_result_metrics_results] FOREIGN KEY ([result_id]) REFERENCES [results] ([id]) ON DELETE CASCADE
);
GO



-- ========================================================
-- DATA SEEDING (Dữ liệu mẫu)
-- ========================================================

-- Seed: surveys
SET IDENTITY_INSERT [surveys] ON;
INSERT INTO [surveys] ([id], [title], [description], [is_active], [created_at], [updated_at]) VALUES
(1, N'Bài Test Đánh Giá Hành Vi Mạng Xã Hội (SJT)', N'Bài đánh giá tình huống có phân nhánh 7 lô (Lô 1, 2A, 2B...).', 1, GETDATE(), GETDATE());
SET IDENTITY_INSERT [surveys] OFF;
GO

-- Seed: questions
SET IDENTITY_INSERT [questions] ON;
INSERT INTO [questions] ([id], [survey_id], [batch_code], [content], [question_type], [created_at], [updated_at]) VALUES
(1, 1, '1', N'Q01: Bạn thấy một sinh viên khóa dưới đang loay hoay tìm phòng thi lúc sắp đến giờ thi. Nếu bạn dừng lại chỉ đường, bạn có thể bị muộn buổi thuyết trình của nhóm mình. Bạn sẽ làm gì?', 'SC', GETDATE(), GETDATE()),
(2, 1, '1', N'Q02: Trong một cuộc khảo sát đánh giá giảng viên, phần lớn lớp thống nhất đánh giá Tốt để thầy dễ tính qua môn, dù thầy dạy rất hời hợt. Bạn sẽ đánh giá thế nào?', 'SC', GETDATE(), GETDATE()),
(3, 1, '1', N'Q03: Bạn vô tình phát hiện đề thi bị rò rỉ trong nhóm chat kín của lớp. Nếu báo cáo, bạn có thể bị tẩy chay. Bạn chọn cách nào?', 'SC', GETDATE(), GETDATE()),
(4, 1, '1', N'Q04: Khi đang chạy deadline cá nhân sát giờ, một bạn cùng nhóm nhờ bạn hướng dẫn lại phần mềm do bạn đó bị ốm mấy hôm trước. Bạn phản ứng ra sao?', 'SC', GETDATE(), GETDATE()),
(5, 1, '1', N'Q05: Bạn sẽ quyết định đứng ra giải quyết một cuộc xung đột trong nhóm khi nào? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(6, 1, '2A', N'Q06: Bạn nhận ra quy định mới của CLB có lỗ hổng gây bất công cho tân sinh viên, nhưng Chủ nhiệm CLB là một người rất bảo thủ và có quyền lực. Bạn xử lý thế nào?', 'SC', GETDATE(), GETDATE()),
(7, 1, '2A', N'Q07: Bạn được giao một công việc chung của nhóm nhưng lại thiếu chuyên môn về phần đó. Nếu làm sai sẽ ảnh hưởng cả nhóm. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(8, 1, '2A', N'Q08: Một sự kiện quan trọng do bạn tổ chức đang diễn ra thì có sự cố mất điện. Không ai biết nguyên nhân. Bạn là người đầu tiên phát hiện tủ điện bị chập. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(9, 1, '2A', N'Q09: Đang thảo luận nhóm, hai thành viên xảy ra tranh cãi gay gắt dẫn đến công kích cá nhân. Cả nhóm đều im lặng. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(10, 1, '2A', N'Q10: Những rủi ro nào khiến bạn e ngại nhất khi đóng góp một ý tưởng đột phá nhưng đi ngược lại truyền thống của khoa? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(11, 1, '2B', N'Q11: Một dự án tình nguyện mời bạn tham gia với vai trò truyền thông. Công việc này chiếm khá nhiều thời gian nhưng có thể dùng để làm đẹp CV xin việc sau này. Bạn phản ứng sao?', 'SC', GETDATE(), GETDATE()),
(12, 1, '2B', N'Q12: Bạn thấy một người đánh rơi ví trên xe buýt. Có nhiều người xung quanh cũng nhìn thấy nhưng không ai lên tiếng. Bạn sẽ làm gì?', 'SC', GETDATE(), GETDATE()),
(13, 1, '2B', N'Q13: Nhóm trưởng phân công một việc cho bạn nhưng lại là việc bạn rất ghét làm. Bạn xử lý thế nào?', 'SC', GETDATE(), GETDATE()),
(14, 1, '2B', N'Q14: Lớp trưởng kêu gọi đóng quỹ để giúp đỡ một sinh viên cùng khóa gặp tai nạn. Bạn không thân với người này lắm. Quyết định của bạn là:', 'SC', GETDATE(), GETDATE()),
(15, 1, '2B', N'Q15: Trong các tình huống sau, điều gì sẽ khiến bạn từ chối giúp đỡ một người xa lạ trên đường? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(16, 1, '3A1', N'Q16: Bạn đang làm Leader của một dự án lớn nhưng tiến độ đang chậm do một số bạn quá tải. Bạn sẽ làm gì để cứu dự án?', 'SC', GETDATE(), GETDATE()),
(17, 1, '3A1', N'Q17: Bạn đại diện lớp đi kiến nghị về cơ sở vật chất kém, nhưng nhà trường từ chối gặp và đưa ra những lý do vòng vo. Bạn sẽ hành động ra sao tiếp theo?', 'SC', GETDATE(), GETDATE()),
(18, 1, '3A1', N'Q18: Một bài đăng bôi nhọ sai sự thật về một giảng viên trong khoa lan truyền trên mạng. Mọi người đều chia sẻ. Dù không thân với thầy, bạn sẽ làm gì?', 'SC', GETDATE(), GETDATE()),
(19, 1, '3A1', N'Q19: Dự án xã hội do bạn khởi xướng bị thất bại và gây thiệt hại một khoản tiền nhỏ của quỹ lớp. Bạn sẽ giải quyết thế nào?', 'SC', GETDATE(), GETDATE()),
(20, 1, '3A1', N'Q20: Bạn thường áp dụng những cách thức nào để thúc đẩy nhóm làm việc hiệu quả? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(21, 1, '3A2', N'Q21: Có người tố cáo nặc danh rằng quỹ CLB bị thâm hụt. Bạn là thủ quỹ nhưng bạn biết rõ số tiền đó được Chủ nhiệm tạm ứng cho việc chung (nhưng chưa có hóa đơn). Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(22, 1, '3A2', N'Q22: Nhóm trưởng đưa ra một quyết định mà bạn thấy có rủi ro pháp lý/nội quy, nhưng cả nhóm đều đã đồng ý vì nó mang lại lợi nhuận cao cho dự án môn học. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(23, 1, '3A2', N'Q23: Bạn muốn báo cáo một sinh viên thường xuyên nhờ người thi hộ, nhưng bạn không có bằng chứng cụ thể ngoài việc "nhìn thấy bằng mắt". Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(24, 1, '3A2', N'Q24: Bạn được mời làm diễn giả cho một chương trình từ thiện nhưng chưa hiểu rõ về tổ chức đằng sau nó. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(25, 1, '3A2', N'Q25: Khi phát hiện sai sót nhỏ trong sổ sách quỹ lớp mà bạn là người kiểm duyệt, bạn sẽ hành động theo những hướng nào? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(26, 1, '3B1', N'Q26: Nhóm bạn hẹn nhau cùng cúp học để đi xem phim. Bạn thực sự không muốn cúp học nhưng cũng không muốn bị chê là "kẻ phá đám". Bạn chọn:', 'SC', GETDATE(), GETDATE()),
(27, 1, '3B1', N'Q27: Khi thấy bạn bè mình liên tục xả rác tại khu dã ngoại, bạn cũng có rác trên tay. Hành động của bạn là:', 'SC', GETDATE(), GETDATE()),
(28, 1, '3B1', N'Q28: Trong buổi họp kín, mọi người đều đồng tình loại một thành viên ra khỏi nhóm dù người đó không có lỗi lớn. Bạn nghĩ người đó bị oan. Bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(29, 1, '3B1', N'Q29: Thấy một người bạn mượn xe của mình mà không đổ xăng nhiều lần, bạn rất khó chịu. Phản ứng của bạn:', 'SC', GETDATE(), GETDATE()),
(30, 1, '3B1', N'Q30: Bạn thường bị thuyết phục tham gia các hoạt động ngoại khóa bởi những lý do nào? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE()),
(31, 1, '3B2', N'Q31: Đi trên đường thấy một nắp cống bị mất, có thể gây nguy hiểm. Bạn có dừng lại báo cho cơ quan chức năng hoặc đặt vật cảnh báo không?', 'SC', GETDATE(), GETDATE()),
(32, 1, '3B2', N'Q32: Bạn nhặt được một chiếc điện thoại đắt tiền nhưng đã khóa màn hình. Xung quanh không có ai. Bạn sẽ làm gì?', 'SC', GETDATE(), GETDATE()),
(33, 1, '3B2', N'Q33: Nhóm trưởng không phân chia công việc rõ ràng dẫn đến bạn phải làm quá nhiều. Khi nộp bài lấy điểm chung, bạn sẽ:', 'SC', GETDATE(), GETDATE()),
(34, 1, '3B2', N'Q34: Thấy một đám đánh nhau ngoài cổng trường, có nguy cơ gây thương tích nghiêm trọng. Bạn phản ứng ra sao?', 'SC', GETDATE(), GETDATE()),
(35, 1, '3B2', N'Q35: Khi đối mặt với yêu cầu sinh viên đóng góp thêm công sức, phản ứng thường thấy của bạn là gì? (Chọn nhiều đáp án)', 'MC', GETDATE(), GETDATE());
SET IDENTITY_INSERT [questions] OFF;
GO

-- Seed: answer_options (Không cần IDENTITY_INSERT vì tự sinh ra id khớp kịch bản)
INSERT INTO [answer_options] ([question_id], [option_text], [points], [cost_tag], [created_at], [updated_at]) VALUES
(1, N'A. Chỉ đường cặn kẽ và chấp nhận bị muộn thuyết trình.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(1, N'B. Báo với giảng viên mình sẽ đến muộn do bận việc để tránh bị trừ điểm.', 2, N'Kỷ luật', GETDATE(), GETDATE()),
(1, N'C. Chỉ qua loa rồi chạy đi vì không muốn nhóm phàn nàn mình.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(1, N'D. Giả vờ không nghe thấy vì bài thuyết trình của mình quan trọng hơn.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(2, N'A. Đánh giá đúng sự thật để cải thiện chất lượng dạy học của trường.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(2, N'B. Đánh giá Tốt vì ai cũng làm thế, sợ đánh giá kém sẽ bị truy ra.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(2, N'C. Đánh giá Tốt để thầy vui vẻ và cho điểm cao, đôi bên cùng có lợi.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(2, N'D. Để trống hoặc tick đại cho xong nhiệm vụ quy định.', 2, N'Kỷ luật', GETDATE(), GETDATE()),
(3, N'A. Báo cáo với nhà trường để đảm bảo công bằng cho kỳ thi.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(3, N'B. Nhắn tin riêng cho lớp trưởng yêu cầu giải quyết để tránh ồn ào.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(3, N'C. Im lặng không nói gì vì sợ bị lớp tẩy chay.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(3, N'D. Im lặng và tranh thủ tải đề về học để được điểm cao.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(4, N'A. Thức khuya hướng dẫn tận tình để bạn ấy theo kịp tiến độ.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(4, N'B. Gửi tài liệu và bảo bạn ấy tự xem trước vì sợ trễ deadline của mình.', 2, N'Kỷ luật', GETDATE(), GETDATE()),
(4, N'C. Cố giúp để bạn ấy thấy mình là người nhiệt tình, giỏi giang.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(4, N'D. Lờ đi tin nhắn hoặc nói dối là mình đang không cầm máy tính.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(5, N'A. Khi thấy vấn đề vi phạm nghiêm trọng nội quy chung.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(5, N'B. Khi hai bên tranh cãi đều là những người bạn rất thân của mình.', 3, N'Quan hệ', GETDATE(), GETDATE()),
(5, N'C. Khi thấy không ai chịu làm và công việc chung sắp đổ vỡ.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(5, N'D. Khi bản thân có thể giải quyết nhanh gọn mà không tốn công sức.', 1, N'Tiện lợi', GETDATE(), GETDATE()),
(6, N'A. Viết một báo cáo phân tích rủi ro chi tiết gửi cho Ban Giám hiệu.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(6, N'B. Dành thời gian nói chuyện riêng với Chủ nhiệm để thuyết phục.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(6, N'C. Đăng một bài ẩn danh trên group trường để tạo sức ép.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(6, N'D. Im lặng vì không muốn đụng chạm đến quyền lợi của Chủ nhiệm.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(7, N'A. Báo cáo ngay cho nhóm để phân lại việc, dù bị mắng là kém cỏi.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(7, N'B. Thức đêm tự học cấp tốc để làm cho xong, không muốn ai thất vọng.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(7, N'C. Nhờ một bạn khác trong nhóm làm hộ để giữ hòa khí.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(7, N'D. Cứ làm bừa, sai thì bảo do phần này khó quá.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(8, N'A. Lập tức chạy đi tìm đồ nghề sửa tạm hoặc gọi thợ khẩn cấp.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(8, N'B. Báo ngay cho Trưởng ban tổ chức để họ quyết định.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(8, N'C. Giữ im lặng và vờ như mình không biết để tránh bị đổ lỗi làm hỏng.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(8, N'D. Đi tìm nhóm bạn thân để rủ họ ra về sớm cho khỏe.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(9, N'A. Chủ động đứng ra can ngăn và yêu cầu cả hai tập trung vào công việc.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(9, N'B. Báo cho lớp trưởng hoặc cố vấn học tập giải quyết theo quy định.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(9, N'C. Cố gắng dỗ dành từng người để giữ hòa khí nhóm.', 2, N'Quan hệ', GETDATE(), GETDATE()),
(9, N'D. Đeo tai nghe vào làm việc riêng, tranh cãi chán rồi họ tự im.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(10, N'A. Sợ bị các thầy cô lớn tuổi đánh giá là nổi loạn, chơi trội.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(10, N'B. Sợ ý tưởng thất bại sẽ làm lãng phí quỹ thời gian cá nhân.', 1, N'Tiện lợi', GETDATE(), GETDATE()),
(10, N'C. Lo rằng ý tưởng thiếu tính thực tiễn và vi phạm các quy chế.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(10, N'D. Sợ bạn bè chê cười và xa lánh vì mình quá khác biệt.', 2, N'Quan hệ', GETDATE(), GETDATE()),
(11, N'A. Tham gia và cố gắng làm thật tốt để PR cho dự án.', 3, N'Dấn thân', GETDATE(), GETDATE()),
(11, N'B. Tham gia chủ yếu để xin cấp giấy chứng nhận làm đẹp CV.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(11, N'C. Hỏi xem bạn bè có ai tham gia không thì mới đi cùng cho vui.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(11, N'D. Từ chối ngay vì tốn quá nhiều thời gian, làm việc khác sướng hơn.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(12, N'A. Chạy tới nhặt lên và hô to xem ai là người đánh rơi.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(12, N'B. Đưa ví cho tài xế xe buýt xử lý theo quy định.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(12, N'C. Chờ xem có ai nhặt không, không thì mình mới nhặt đưa công an.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(12, N'D. Vờ như không thấy vì sợ nhặt lên người ta lại bảo mình ăn cắp.', 0, N'Quan hệ', GETDATE(), GETDATE()),
(13, N'A. Chấp nhận làm và tìm hiểu để làm thật tốt vì trách nhiệm nhóm.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(13, N'B. Cứ làm nhưng làm đối phó cho có lệ.', 1, N'Kỷ luật', GETDATE(), GETDATE()),
(13, N'C. Cằn nhằn với các thành viên khác nhưng vẫn làm để khỏi bị nói.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(13, N'D. Xin đổi việc hoặc kiếm cớ bận để thoái thác ngay lập tức.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(14, N'A. Đóng góp trong khả năng và kêu gọi thêm trên trang cá nhân.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(14, N'B. Đóng mức cơ bản giống như đa số các bạn khác cho xong.', 2, N'Quan hệ', GETDATE(), GETDATE()),
(14, N'C. Đóng mức thật cao để mọi người trầm trồ và khen ngợi mình.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(14, N'D. Viện lý do đang hết tiền để trốn đóng quỹ.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(15, N'A. Người đó trông có vẻ khả nghi, sợ bị lừa đảo dàn cảnh.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(15, N'B. Đang vội đi chơi hoặc có việc cá nhân cần ưu tiên.', 1, N'Tiện lợi', GETDATE(), GETDATE()),
(15, N'C. Sợ giúp sai cách sẽ khiến mọi người xung quanh chỉ trích mình.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(15, N'D. Bạn bè đi cùng khuyên không nên xen vào chuyện bao đồng.', 2, N'Quan hệ', GETDATE(), GETDATE()),
(16, N'A. Nhận lại một phần việc của các bạn và cùng làm xuyên đêm.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(16, N'B. Đề nghị dời deadline với cấp trên theo đúng quy trình báo cáo.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(16, N'C. Trách mắng các bạn và ép họ phải hoàn thành bằng mọi giá để mình không bị chê.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(16, N'D. Để mặc dự án thất bại và đổ lỗi hoàn toàn cho sự yếu kém của các bạn.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(17, N'A. Viết thư tay gửi thẳng lên Hiệu trưởng hoặc Bộ Giáo dục.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(17, N'B. Làm đơn kiến nghị theo đúng form mẫu và thu thập đủ 1000 chữ ký.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(17, N'C. Lên các trang Confession than thở ẩn danh để tạo dư luận.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(17, N'D. Từ bỏ ý định vì nghĩ mình chỉ là sinh viên "thấp cổ bé họng".', 1, N'Quan hệ', GETDATE(), GETDATE()),
(18, N'A. Lên tiếng đính chính ngay trên bài viết đó bằng nick thật.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(18, N'B. Chụp màn hình gửi cho Ban Cán sự lớp để họ báo cáo nhà trường.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(18, N'C. Báo cáo bài viết ẩn danh rồi chặn trang đó lại.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(18, N'D. Ngồi xem comment hóng hớt, coi như câu chuyện giải trí.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(19, N'A. Công khai nhận lỗi trước lớp và xin tự bỏ tiền túi ra đền bù.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(19, N'B. Cố gắng giấu nhẹm khoản thâm hụt và dùng tiền riêng lấp vào.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(19, N'C. Lên nhóm xin lỗi qua loa và bảo mọi người thông cảm cùng chịu thiệt.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(19, N'D. Đổ lỗi cho các nguyên nhân khách quan để trốn tránh đền tiền.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(20, N'A. Đưa ra các quy định thưởng phạt phân minh và giám sát chặt chẽ.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(20, N'B. Chủ động làm mẫu và nhận những phần việc khó nhất về mình.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(20, N'C. Thường xuyên tổ chức ăn uống, khen ngợi để tạo không khí vui vẻ.', 3, N'Quan hệ', GETDATE(), GETDATE()),
(20, N'D. Cho phép các thành viên linh hoạt thời gian, miễn là nộp bài.', 2, N'Tiện lợi', GETDATE(), GETDATE()),
(21, N'A. Nộp đầy đủ sổ sách chứng minh và yêu cầu một buổi họp minh bạch.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(21, N'B. Chủ động gặp Chủ nhiệm yêu cầu họ tự giải trình với mọi người.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(21, N'C. Im lặng chờ Chủ nhiệm tự xử lý, nếu làm căng sẽ mất lòng sếp.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(21, N'D. Xin nghỉ làm thủ quỹ để tránh xa rắc rối.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(22, N'A. Kiên quyết phản đối bằng văn bản, dù bị cả nhóm ghét bỏ.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(22, N'B. Đưa ra các phân tích rủi ro để cả nhóm cùng đánh giá lại.', 3, N'Dấn thân', GETDATE(), GETDATE()),
(22, N'C. Cứ đồng ý làm theo vì không muốn bị coi là người ngáng đường.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(22, N'D. Đồng ý làm nhưng cố gắng lấy nhiều lợi nhuận nhất có thể về mình.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(23, N'A. Thu thập đủ bằng chứng rồi gửi lên Hội đồng kỷ luật.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(23, N'B. Gặp trực tiếp bạn đó và khuyên họ tự thú hoặc dừng lại.', 3, N'Dấn thân', GETDATE(), GETDATE()),
(23, N'C. Không báo cáo nhưng đem chuyện này đi kể xấu với bạn bè khác.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(23, N'D. Không làm gì cả vì không ảnh hưởng trực tiếp đến điểm số của mình.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(24, N'A. Dành 1 ngày tra cứu, thẩm định uy tín tổ chức trước khi nhận lời.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(24, N'B. Đồng ý luôn vì có thể lấy hình ảnh sự kiện để đánh bóng tên tuổi.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(24, N'C. Hỏi thăm ý kiến bạn bè, nếu họ khuyên đi thì đi.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(24, N'D. Từ chối luôn cho khỏe, ở nhà nghỉ ngơi tốt hơn.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(25, N'A. Yêu cầu người giữ quỹ giải trình ngay lập tức theo đúng quy định.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(25, N'B. Nhắc nhở riêng tư để bạn đó sửa sai mà không bị bẽ mặt trước lớp.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(25, N'C. Chủ động cùng bạn đó rà soát lại toàn bộ hóa đơn để tìm nguyên nhân.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(25, N'D. Nhờ một bạn khác xử lý giúp vì không muốn vướng rắc rối.', 1, N'Tiện lợi', GETDATE(), GETDATE()),
(26, N'A. Kiên quyết từ chối và đi học, khuyên các bạn nên đi học cùng.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(26, N'B. Từ chối đi nhưng bảo mình bị ốm để các bạn không giận.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(26, N'C. Miễn cưỡng cúp học đi theo vì sợ lần sau các bạn không chơi cùng.', 0, N'Quan hệ', GETDATE(), GETDATE()),
(26, N'D. Đồng ý đi luôn vì cũng đang lười học.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(27, N'A. Nhặt rác của mình bỏ vào thùng, và nhặt luôn cả phần của bạn bè.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(27, N'B. Bỏ rác của mình vào thùng, nhưng không nói gì các bạn.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(27, N'C. Lén giấu rác vào bụi cây để không ai thấy.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(27, N'D. Vứt luôn xuống đất giống bạn bè cho tiện.', 0, N'Quan hệ', GETDATE(), GETDATE()),
(28, N'A. Đứng lên phản đối mạnh mẽ và đưa ra lý lẽ bảo vệ người đó.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(28, N'B. Yêu cầu xem xét lại theo đúng quy định của nhóm.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(28, N'C. Đồng tình theo số đông để không bị nhóm ghét lây.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(28, N'D. Im lặng không vote, kệ mọi chuyện ra sao thì ra.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(29, N'A. Thẳng thắn đưa ra quy tắc mượn xe phải tự đổ xăng rõ ràng.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(29, N'B. Nói đùa khéo léo để bạn ấy tự hiểu và tự đi đổ.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(29, N'C. Cứ bực mình nhưng không dám nói vì sợ sứt mẻ tình bạn.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(29, N'D. Nói dối là xe bị hỏng để không cho mượn nữa.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(30, N'A. Được bạn bè thân thiết rủ rê, đi cùng cho có phong trào.', 2, N'Quan hệ', GETDATE(), GETDATE()),
(30, N'B. Có cơ hội nhận giấy khen hoặc làm đẹp hồ sơ cá nhân.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(30, N'C. Nội dung chương trình thực sự mang lại giá trị giải quyết vấn đề.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(30, N'D. Hoạt động tổ chức gần nhà, thời gian ngắn, dễ dàng tham gia.', 1, N'Tiện lợi', GETDATE(), GETDATE()),
(31, N'A. Chặn đường lấy cây làm vật cản và gọi ngay đường dây nóng.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(31, N'B. Chụp ảnh đăng lên mạng xã hội để cảnh báo mọi người.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(31, N'C. Đứng xem có ai bị lọt cống không để quay clip câu view.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(31, N'D. Lách xe né qua rồi đi thẳng về nhà ăn cơm cho nhanh.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(32, N'A. Mang đến đồn Công an gần nhất để giao nộp.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(32, N'B. Đăng lên Facebook trường để tìm người rơi và nhận lời cảm ơn.', 3, N'Hình ảnh', GETDATE(), GETDATE()),
(32, N'C. Để lại chỗ cũ, không động vào vì sợ bị dàn cảnh tống tiền.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(32, N'D. Tháo sim, đem đi bẻ khóa bán lấy tiền xài.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(33, N'A. Đưa bảng đánh giá công việc rõ ràng để trừ điểm những người lười.', 4, N'Kỷ luật', GETDATE(), GETDATE()),
(33, N'B. Nhận hết công lao về mình trước mặt giảng viên.', 2, N'Hình ảnh', GETDATE(), GETDATE()),
(33, N'C. Phàn nàn nhưng cuối cùng vẫn cho mọi người điểm cao như nhau.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(33, N'D. Xóa bớt tên các bạn ra khỏi báo cáo mà không nói lời nào.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(34, N'A. Hô hoán mọi người xung quanh cùng vào can ngăn.', 4, N'Dấn thân', GETDATE(), GETDATE()),
(34, N'B. Báo ngay cho bảo vệ trường hoặc công an phường.', 3, N'Kỷ luật', GETDATE(), GETDATE()),
(34, N'C. Đứng từ xa quay video để hóng biến.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(34, N'D. Chạy nhanh đi đường khác kẻo bị vạ lây.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(35, N'A. Tìm cách né tránh hoặc làm ở mức tối thiểu để đối phó.', 0, N'Tiện lợi', GETDATE(), GETDATE()),
(35, N'B. Chờ xem số đông các bạn trong lớp làm gì rồi mới làm theo.', 1, N'Quan hệ', GETDATE(), GETDATE()),
(35, N'C. Lên mạng xã hội đăng bài than vãn để tìm sự đồng cảm.', 1, N'Hình ảnh', GETDATE(), GETDATE()),
(35, N'D. Nghiên cứu kỹ quy định xem việc này có thực sự bắt buộc hay không.', 2, N'Kỷ luật', GETDATE(), GETDATE());
GO

-- Seed: Participants
SET IDENTITY_INSERT [participants] ON;
INSERT INTO [participants] ([id], [student_code], [fullname], [faculty], [created_at], [updated_at]) VALUES
(101, '26A4010101', N'Nguyễn Văn Anh', N'Khoa Công nghệ thông tin và Kinh tế số', GETDATE(), GETDATE()),
(102, '26A4010102', N'Trần Thị Bình', N'Khoa Tài Chính', GETDATE(), GETDATE()),
(103, '26A4010103', N'Lê Hoàng Cường', N'Khoa Ngân Hàng', GETDATE(), GETDATE()),
(104, '26A4010104', N'Phạm Minh Đức', N'Khoa Kế toán - Kiểm toán', GETDATE(), GETDATE()),
(105, '26A4010105', N'Đỗ Thanh Hải', N'Khoa Quản trị kinh doanh', GETDATE(), GETDATE()),
(106, '26A4010106', N'Hoàng Thị Yến', N'Khoa Kinh doanh quốc tế', GETDATE(), GETDATE()),
(107, '26A4010107', N'Bùi Xuân Huấn', N'Khoa Luật Kinh tế', GETDATE(), GETDATE()),
(108, '26A4010108', N'Vũ Kim Liên', N'Khoa Ngoại ngữ', GETDATE(), GETDATE()),
(109, '26A4010109', N'Lý Tiểu Long', N'Khoa Kinh tế', GETDATE(), GETDATE()),
(110, '26A4010110', N'Ngô Kiến Huy', N'Khoa Công nghệ thông tin và Kinh tế số', GETDATE(), GETDATE()),
(111, '26A4010111', N'Phan Mạnh Quỳnh', N'Khoa Tài Chính', GETDATE(), GETDATE()),
(112, '26A4010112', N'Sơn Tùng M-TP', N'Khoa Ngân Hàng', GETDATE(), GETDATE()),
(113, '26A4010113', N'Đen Vâu', N'Khoa Kế toán - Kiểm toán', GETDATE(), GETDATE()),
(114, '26A4010114', N'Hòa Minzy', N'Khoa Quản trị kinh doanh', GETDATE(), GETDATE()),
(115, '26A4010115', N'Erik', N'Khoa Kinh doanh quốc tế', GETDATE(), GETDATE()),
(116, '26A4010116', N'Đức Phúc', N'Khoa Luật Kinh tế', GETDATE(), GETDATE()),
(117, '26A4010117', N'Suboi', N'Khoa Ngoại ngữ', GETDATE(), GETDATE()),
(118, '26A4010118', N'Karik', N'Khoa Kinh tế', GETDATE(), GETDATE()),
(119, '26A4010119', N'Binz', N'Khoa Công nghệ thông tin và Kinh tế số', GETDATE(), GETDATE()),
(120, '26A4010120', N'JustaTee', N'Khoa Tài Chính', GETDATE(), GETDATE());
SET IDENTITY_INSERT [participants] OFF;
GO

-- Seed: Attempts
SET IDENTITY_INSERT [attempts] ON;
INSERT INTO [attempts] ([id], [participant_id], [survey_id], [current_batch], [total_score], [status], [created_at], [updated_at]) VALUES
(101, 101, 1, '3A1', 52.0, 'completed', GETDATE(), GETDATE()),
(102, 102, 1, '3A2', 45.0, 'completed', GETDATE(), GETDATE()),
(103, 103, 1, '3B1', 30.0, 'completed', GETDATE(), GETDATE()),
(104, 104, 1, '3B2', 22.0, 'completed', GETDATE(), GETDATE()),
(105, 105, 1, '3A1', 58.0, 'completed', GETDATE(), GETDATE()),
(106, 106, 1, '3A2', 41.0, 'completed', GETDATE(), GETDATE()),
(107, 107, 1, '3B1', 28.0, 'completed', GETDATE(), GETDATE()),
(108, 108, 1, '3B2', 15.0, 'completed', GETDATE(), GETDATE()),
(109, 109, 1, '3A1', 60.0, 'completed', GETDATE(), GETDATE()),
(110, 110, 1, '3A2', 38.0, 'completed', GETDATE(), GETDATE()),
(111, 111, 1, '3B1', 33.0, 'completed', GETDATE(), GETDATE()),
(112, 112, 1, '3A1', 55.0, 'completed', GETDATE(), GETDATE()),
(113, 113, 1, '3A2', 47.0, 'completed', GETDATE(), GETDATE()),
(114, 114, 1, '3B2', 18.0, 'completed', GETDATE(), GETDATE()),
(115, 115, 1, '3B1', 31.0, 'completed', GETDATE(), GETDATE()),
(116, 116, 1, '3A1', 49.0, 'completed', GETDATE(), GETDATE()),
(117, 117, 1, '3A2', 42.0, 'completed', GETDATE(), GETDATE()),
(118, 118, 1, '3B1', 25.0, 'completed', GETDATE(), GETDATE()),
(119, 119, 1, '3A1', 56.0, 'completed', GETDATE(), GETDATE()),
(120, 120, 1, '3A2', 44.0, 'completed', GETDATE(), GETDATE());
SET IDENTITY_INSERT [attempts] OFF;
GO

-- Seed: attempt_answers
SET IDENTITY_INSERT [attempt_answers] ON;
-- Participant 101
INSERT INTO [attempt_answers] ([id], [attempt_id], [question_id], [created_at]) VALUES (101, 101, 1, GETDATE()), (102, 101, 2, GETDATE()), (103, 101, 5, GETDATE()), (104, 101, 16, GETDATE()), (105, 101, 17, GETDATE());
-- Participant 102
INSERT INTO [attempt_answers] ([id], [attempt_id], [question_id], [created_at]) VALUES (111, 102, 1, GETDATE()), (112, 102, 2, GETDATE()), (113, 102, 5, GETDATE()), (114, 102, 21, GETDATE()), (115, 102, 22, GETDATE());
-- Participant 103
INSERT INTO [attempt_answers] ([id], [attempt_id], [question_id], [created_at]) VALUES (121, 103, 1, GETDATE()), (122, 103, 2, GETDATE()), (123, 103, 5, GETDATE()), (124, 103, 26, GETDATE()), (125, 103, 27, GETDATE());
-- Participant 104
INSERT INTO [attempt_answers] ([id], [attempt_id], [question_id], [created_at]) VALUES (131, 104, 1, GETDATE()), (132, 104, 2, GETDATE()), (133, 104, 5, GETDATE()), (134, 104, 31, GETDATE()), (135, 104, 32, GETDATE());
SET IDENTITY_INSERT [attempt_answers] OFF;
GO

-- Seed: attempt_answer_options (Tự động Identity id)
-- Dữ liệu P101
INSERT INTO [attempt_answer_options] ([attempt_answer_id], [answer_option_id]) VALUES (101, 1), (102, 5), (103, 17), (104, 61), (105, 65);
-- Dữ liệu P102
INSERT INTO [attempt_answer_options] ([attempt_answer_id], [answer_option_id]) VALUES (111, 2), (112, 6), (113, 18), (114, 81), (115, 85);
-- Dữ liệu P103
INSERT INTO [attempt_answer_options] ([attempt_answer_id], [answer_option_id]) VALUES (121, 3), (122, 8), (123, 20), (124, 101), (125, 106);
-- Dữ liệu P104
INSERT INTO [attempt_answer_options] ([attempt_answer_id], [answer_option_id]) VALUES (131, 4), (132, 7), (133, 20), (134, 125), (135, 129);

-- Bổ sung dữ liệu phụ
INSERT INTO [attempt_answer_options] ([attempt_answer_id], [answer_option_id]) VALUES 
(101, 13), (101, 25), (111, 41), (112, 53), (121, 77), (122, 89), (131, 105), (131, 117), (131, 133), (131, 137);
GO
