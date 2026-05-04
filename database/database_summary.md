# 📊 Thống Kê Dữ Liệu Database `survey_system`

> **Database:** `survey_system` | **Charset:** `utf8mb4_unicode_ci`

---

## 1. Danh Sách Bảng (Tables)

| # | Tên Bảng | Mô Tả | Số Bản Ghi Mẫu |
|---|----------|-------|----------------|
| 1 | `surveys` | Thông tin bài khảo sát | 1 |
| 2 | `participants` | Người tham gia | 20 |
| 3 | `questions` | Câu hỏi trong khảo sát | 35 |
| 4 | `answer_options` | Đáp án cho mỗi câu hỏi | 140 |
| 5 | `attempts` | Lượt làm bài | 20 |
| 6 | `attempt_answers` | Chi tiết câu trả lời | 20+ |
| 7 | `attempt_answer_options` | Đáp án đã chọn | ~30+ |
| 8 | `results` | Kết quả cuối cùng | — |
| 9 | `result_metrics` | Điểm theo từng tag | — |

---

## 2. Câu Hỏi (questions) — 35 câu, phân theo Lô (Batch)

### 📦 Lô 1 — Sàng lọc ban đầu (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 1 | Q01 | SC | Bạn thấy một sinh viên khóa dưới đang loay hoay tìm phòng thi lúc sắp đến giờ thi. Nếu bạn dừng lại chỉ đường, bạn có thể bị muộn buổi thuyết trình của nhóm mình. Bạn sẽ làm gì? | Chỉ đường cặn kẽ và chấp nhận bị muộn thuyết trình (4·Dấn thân) | Báo giảng viên sẽ đến muộn do bận việc để tránh bị trừ điểm (2·Kỷ luật) | Chỉ qua loa rồi chạy đi vì không muốn nhóm phàn nàn (1·Quan hệ) | Giả vờ không nghe thấy vì thuyết trình quan trọng hơn (0·Tiện lợi) |
| 2 | Q02 | SC | Trong một cuộc khảo sát đánh giá giảng viên, phần lớn lớp thống nhất đánh giá Tốt để thầy dễ tính qua môn, dù thầy dạy rất hời hợt. Bạn sẽ đánh giá thế nào? | Đánh giá đúng sự thật để cải thiện chất lượng dạy học của trường (4·Dấn thân) | Đánh giá Tốt vì ai cũng làm thế, sợ đánh giá kém sẽ bị truy ra (1·Quan hệ) | Đánh giá Tốt để thầy vui vẻ cho điểm cao, đôi bên cùng lợi (0·Tiện lợi) | Để trống hoặc tick đại cho xong nhiệm vụ quy định (2·Kỷ luật) |
| 3 | Q03 | SC | Bạn vô tình phát hiện đề thi bị rò rỉ trong nhóm chat kín của lớp. Nếu báo cáo, bạn có thể bị tẩy chay. Bạn chọn cách nào? | Báo cáo với nhà trường để đảm bảo công bằng cho kỳ thi (4·Kỷ luật) | Nhắn tin riêng cho lớp trưởng yêu cầu giải quyết để tránh ồn ào (3·Hình ảnh) | Im lặng không nói gì vì sợ bị lớp tẩy chay (1·Quan hệ) | Im lặng và tải đề về học để được điểm cao (0·Tiện lợi) |
| 4 | Q04 | SC | Khi đang chạy deadline cá nhân sát giờ, một bạn cùng nhóm nhờ bạn hướng dẫn lại phần mềm do bạn đó bị ốm mấy hôm trước. Bạn phản ứng ra sao? | Thức khuya hướng dẫn tận tình để bạn ấy theo kịp tiến độ (4·Dấn thân) | Gửi tài liệu và bảo bạn ấy tự xem vì sợ trễ deadline của mình (2·Kỷ luật) | Cố giúp để bạn ấy thấy mình nhiệt tình, giỏi giang (3·Hình ảnh) | Lờ tin nhắn hoặc nói dối là mình không cầm máy tính (0·Tiện lợi) |
| 5 | Q05 | **MC** | Bạn sẽ quyết định đứng ra giải quyết một cuộc xung đột trong nhóm khi nào? (Chọn nhiều đáp án) | Khi thấy vấn đề vi phạm nghiêm trọng nội quy chung (4·Kỷ luật) | Khi hai bên tranh cãi đều là những người bạn rất thân (3·Quan hệ) | Khi không ai chịu làm và công việc chung sắp đổ vỡ (4·Dấn thân) | Khi bản thân có thể giải quyết nhanh mà không tốn công sức (1·Tiện lợi) |

### 📦 Lô 2A — Nhánh A (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 6 | Q06 | SC | Bạn nhận ra quy định mới của CLB có lỗ hổng gây bất công cho tân sinh viên, nhưng Chủ nhiệm CLB là một người rất bảo thủ và có quyền lực. Bạn xử lý thế nào? | Viết báo cáo phân tích rủi ro chi tiết gửi cho Ban Giám hiệu (4·Kỷ luật) | Dành thời gian nói chuyện riêng với Chủ nhiệm để thuyết phục (4·Dấn thân) | Đăng bài ẩn danh trên group trường để tạo sức ép (2·Hình ảnh) | Im lặng vì không muốn đụng chạm quyền lợi của Chủ nhiệm (1·Quan hệ) |
| 7 | Q07 | SC | Bạn được giao một công việc chung của nhóm nhưng lại thiếu chuyên môn về phần đó. Nếu làm sai sẽ ảnh hưởng cả nhóm. Bạn sẽ: | Báo ngay cho nhóm để phân lại việc, dù bị mắng là kém cỏi (4·Kỷ luật) | Thức đêm tự học cấp tốc để làm cho xong, không muốn ai thất vọng (3·Hình ảnh) | Nhờ một bạn khác trong nhóm làm hộ để giữ hòa khí (1·Quan hệ) | Cứ làm bừa, sai thì bảo do phần này khó quá (0·Tiện lợi) |
| 8 | Q08 | SC | Một sự kiện quan trọng do bạn tổ chức đang diễn ra thì có sự cố mất điện. Không ai biết nguyên nhân. Bạn là người đầu tiên phát hiện tủ điện bị chập. Bạn sẽ: | Lập tức chạy đi tìm đồ nghề sửa tạm hoặc gọi thợ khẩn cấp (4·Dấn thân) | Báo ngay cho Trưởng ban tổ chức để họ quyết định (3·Kỷ luật) | Giữ im lặng và vờ như mình không biết để tránh bị đổ lỗi (2·Hình ảnh) | Đi tìm nhóm bạn thân để rủ họ ra về sớm cho khỏe (0·Tiện lợi) |
| 9 | Q09 | SC | Đang thảo luận nhóm, hai thành viên xảy ra tranh cãi gay gắt dẫn đến công kích cá nhân. Cả nhóm đều im lặng. Bạn sẽ: | Chủ động đứng ra can ngăn và yêu cầu cả hai tập trung vào công việc (4·Dấn thân) | Báo cho lớp trưởng hoặc cố vấn học tập giải quyết theo quy định (3·Kỷ luật) | Cố gắng dỗ dành từng người để giữ hòa khí nhóm (2·Quan hệ) | Đeo tai nghe vào làm việc riêng, tranh cãi chán rồi họ tự im (0·Tiện lợi) |
| 10 | Q10 | **MC** | Những rủi ro nào khiến bạn e ngại nhất khi đóng góp một ý tưởng đột phá nhưng đi ngược lại truyền thống của khoa? (Chọn nhiều đáp án) | Sợ bị thầy cô lớn tuổi đánh giá là nổi loạn, chơi trội (2·Hình ảnh) | Sợ ý tưởng thất bại sẽ làm lãng phí quỹ thời gian cá nhân (1·Tiện lợi) | Lo rằng ý tưởng thiếu tính thực tiễn và vi phạm các quy chế (3·Kỷ luật) | Sợ bạn bè chê cười và xa lánh vì mình quá khác biệt (2·Quan hệ) |

### 📦 Lô 2B — Nhánh B (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 11 | Q11 | SC | Một dự án tình nguyện mời bạn tham gia với vai trò truyền thông. Công việc này chiếm khá nhiều thời gian nhưng có thể dùng để làm đẹp CV xin việc sau này. Bạn phản ứng sao? | Tham gia và cố gắng làm thật tốt để PR cho dự án (3·Dấn thân) | Tham gia chủ yếu để xin giấy chứng nhận làm đẹp CV (2·Hình ảnh) | Hỏi xem bạn bè có ai tham gia không thì mới đi cùng (1·Quan hệ) | Từ chối ngay vì tốn quá nhiều thời gian, làm việc khác sướng hơn (0·Tiện lợi) |
| 12 | Q12 | SC | Bạn thấy một người đánh rơi ví trên xe buýt. Có nhiều người xung quanh cũng nhìn thấy nhưng không ai lên tiếng. Bạn sẽ làm gì? | Chạy tới nhặt lên và hô to xem ai là người đánh rơi (4·Dấn thân) | Đưa ví cho tài xế xe buýt xử lý theo quy định (3·Kỷ luật) | Chờ xem có ai nhặt không, không thì mình mới nhặt đưa công an (1·Hình ảnh) | Vờ như không thấy vì sợ nhặt lên người ta lại bảo mình ăn cắp (0·Quan hệ) |
| 13 | Q13 | SC | Nhóm trưởng phân công một việc cho bạn nhưng lại là việc bạn rất ghét làm. Bạn xử lý thế nào? | Chấp nhận làm và tìm hiểu để làm thật tốt vì trách nhiệm nhóm (4·Dấn thân) | Cứ làm nhưng làm đối phó cho có lệ (1·Kỷ luật) | Cằn nhằn với các thành viên khác nhưng vẫn làm để khỏi bị nói (2·Hình ảnh) | Xin đổi việc hoặc kiếm cớ bận để thoái thác ngay lập tức (0·Tiện lợi) |
| 14 | Q14 | SC | Lớp trưởng kêu gọi đóng quỹ để giúp đỡ một sinh viên cùng khóa gặp tai nạn. Bạn không thân với người này lắm. Quyết định của bạn là: | Đóng góp trong khả năng và kêu gọi thêm trên trang cá nhân (4·Dấn thân) | Đóng mức cơ bản giống như đa số các bạn khác cho xong (2·Quan hệ) | Đóng mức thật cao để mọi người trầm trồ và khen ngợi mình (3·Hình ảnh) | Viện lý do đang hết tiền để trốn đóng quỹ (0·Tiện lợi) |
| 15 | Q15 | **MC** | Trong các tình huống sau, điều gì sẽ khiến bạn từ chối giúp đỡ một người xa lạ trên đường? (Chọn nhiều đáp án) | Người đó trông có vẻ khả nghi, sợ bị lừa đảo dàn cảnh (3·Kỷ luật) | Đang vội đi chơi hoặc có việc cá nhân cần ưu tiên (1·Tiện lợi) | Sợ giúp sai cách sẽ khiến mọi người xung quanh chỉ trích mình (2·Hình ảnh) | Bạn bè đi cùng khuyên không nên xen vào chuyện bao đồng (2·Quan hệ) |

### 📦 Lô 3A1 — Tầng cuối, Nhóm A cao (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 16 | Q16 | SC | Bạn đang làm Leader của một dự án lớn nhưng tiến độ đang chậm do một số bạn quá tải. Bạn sẽ làm gì để cứu dự án? | Nhận lại một phần việc của các bạn và cùng làm xuyên đêm (4·Dấn thân) | Đề nghị dời deadline với cấp trên theo đúng quy trình báo cáo (3·Kỷ luật) | Trách mắng và ép họ hoàn thành bằng mọi giá để mình không bị chê (2·Hình ảnh) | Để mặc dự án thất bại và đổ lỗi hoàn toàn cho sự yếu kém của các bạn (0·Tiện lợi) |
| 17 | Q17 | SC | Bạn đại diện lớp đi kiến nghị về cơ sở vật chất kém, nhưng nhà trường từ chối gặp và đưa ra những lý do vòng vo. Bạn sẽ hành động ra sao tiếp theo? | Viết thư tay gửi thẳng lên Hiệu trưởng hoặc Bộ Giáo dục (4·Dấn thân) | Làm đơn kiến nghị theo đúng form mẫu và thu thập đủ 1000 chữ ký (4·Kỷ luật) | Lên các trang Confession than thở ẩn danh để tạo dư luận (2·Hình ảnh) | Từ bỏ ý định vì nghĩ mình chỉ là sinh viên "thấp cổ bé họng" (1·Quan hệ) |
| 18 | Q18 | SC | Một bài đăng bôi nhọ sai sự thật về một giảng viên trong khoa lan truyền trên mạng. Mọi người đều chia sẻ. Dù không thân với thầy, bạn sẽ làm gì? | Lên tiếng đính chính ngay trên bài viết đó bằng nick thật (4·Dấn thân) | Chụp màn hình gửi cho Ban Cán sự lớp để họ báo cáo nhà trường (3·Kỷ luật) | Báo cáo bài viết ẩn danh rồi chặn trang đó lại (2·Hình ảnh) | Ngồi xem comment hóng hớt, coi như câu chuyện giải trí (0·Tiện lợi) |
| 19 | Q19 | SC | Dự án xã hội do bạn khởi xướng bị thất bại và gây thiệt hại một khoản tiền nhỏ của quỹ lớp. Bạn sẽ giải quyết thế nào? | Công khai nhận lỗi trước lớp và xin tự bỏ tiền túi ra đền bù (4·Dấn thân) | Cố gắng giấu nhẹm khoản thâm hụt và dùng tiền riêng lấp vào (2·Hình ảnh) | Lên nhóm xin lỗi qua loa và bảo mọi người thông cảm cùng chịu thiệt (1·Quan hệ) | Đổ lỗi cho các nguyên nhân khách quan để trốn tránh đền tiền (0·Tiện lợi) |
| 20 | Q20 | **MC** | Bạn thường áp dụng những cách thức nào để thúc đẩy nhóm làm việc hiệu quả? (Chọn nhiều đáp án) | Đưa ra các quy định thưởng phạt phân minh và giám sát chặt chẽ (4·Kỷ luật) | Chủ động làm mẫu và nhận những phần việc khó nhất về mình (4·Dấn thân) | Thường xuyên tổ chức ăn uống, khen ngợi để tạo không khí vui vẻ (3·Quan hệ) | Cho phép các thành viên linh hoạt thời gian, miễn là nộp bài (2·Tiện lợi) |

### 📦 Lô 3A2 — Tầng cuối, Nhóm A thấp (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 21 | Q21 | SC | Có người tố cáo nặc danh rằng quỹ CLB bị thâm hụt. Bạn là thủ quỹ nhưng bạn biết rõ số tiền đó được Chủ nhiệm tạm ứng cho việc chung (nhưng chưa có hóa đơn). Bạn sẽ: | Nộp đầy đủ sổ sách chứng minh và yêu cầu một buổi họp minh bạch (4·Kỷ luật) | Chủ động gặp Chủ nhiệm yêu cầu họ tự giải trình với mọi người (4·Dấn thân) | Im lặng chờ Chủ nhiệm tự xử lý, nếu làm căng sẽ mất lòng sếp (1·Quan hệ) | Xin nghỉ làm thủ quỹ để tránh xa rắc rối (0·Tiện lợi) |
| 22 | Q22 | SC | Nhóm trưởng đưa ra một quyết định mà bạn thấy có rủi ro pháp lý/nội quy, nhưng cả nhóm đều đã đồng ý vì nó mang lại lợi nhuận cao cho dự án môn học. Bạn sẽ: | Kiên quyết phản đối bằng văn bản, dù bị cả nhóm ghét bỏ (4·Kỷ luật) | Đưa ra các phân tích rủi ro để cả nhóm cùng đánh giá lại (3·Dấn thân) | Cứ đồng ý làm theo vì không muốn bị coi là người ngáng đường (1·Quan hệ) | Đồng ý làm nhưng cố gắng lấy nhiều lợi nhuận nhất có thể về mình (0·Tiện lợi) |
| 23 | Q23 | SC | Bạn muốn báo cáo một sinh viên thường xuyên nhờ người thi hộ, nhưng bạn không có bằng chứng cụ thể ngoài việc "nhìn thấy bằng mắt". Bạn sẽ: | Thu thập đủ bằng chứng rồi gửi lên Hội đồng kỷ luật (4·Kỷ luật) | Gặp trực tiếp bạn đó và khuyên họ tự thú hoặc dừng lại (3·Dấn thân) | Không báo cáo nhưng đem chuyện này đi kể xấu với bạn bè khác (1·Hình ảnh) | Không làm gì cả vì không ảnh hưởng trực tiếp đến điểm số của mình (0·Tiện lợi) |
| 24 | Q24 | SC | Bạn được mời làm diễn giả cho một chương trình từ thiện nhưng chưa hiểu rõ về tổ chức đằng sau nó. Bạn sẽ: | Dành 1 ngày tra cứu, thẩm định uy tín tổ chức trước khi nhận lời (4·Kỷ luật) | Đồng ý luôn vì có thể lấy hình ảnh sự kiện để đánh bóng tên tuổi (2·Hình ảnh) | Hỏi thăm ý kiến bạn bè, nếu họ khuyên đi thì đi (1·Quan hệ) | Từ chối luôn cho khỏe, ở nhà nghỉ ngơi tốt hơn (0·Tiện lợi) |
| 25 | Q25 | **MC** | Khi phát hiện sai sót nhỏ trong sổ sách quỹ lớp mà bạn là người kiểm duyệt, bạn sẽ hành động theo những hướng nào? (Chọn nhiều đáp án) | Yêu cầu người giữ quỹ giải trình ngay lập tức theo đúng quy định (4·Kỷ luật) | Nhắc nhở riêng tư để bạn đó sửa sai mà không bị bẽ mặt trước lớp (3·Hình ảnh) | Chủ động cùng bạn đó rà soát lại toàn bộ hóa đơn để tìm nguyên nhân (4·Dấn thân) | Nhờ một bạn khác xử lý giúp vì không muốn vướng rắc rối (1·Tiện lợi) |

### 📦 Lô 3B1 — Tầng cuối, Nhóm B cao (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 26 | Q26 | SC | Nhóm bạn hẹn nhau cùng cúp học để đi xem phim. Bạn thực sự không muốn cúp học nhưng cũng không muốn bị chê là "kẻ phá đám". Bạn chọn: | Kiên quyết từ chối và đi học, khuyên các bạn nên đi học cùng (4·Kỷ luật) | Từ chối đi nhưng bảo mình bị ốm để các bạn không giận (2·Hình ảnh) | Miễn cưỡng cúp học đi theo vì sợ lần sau các bạn không chơi cùng (0·Quan hệ) | Đồng ý đi luôn vì cũng đang lười học (0·Tiện lợi) |
| 27 | Q27 | SC | Khi thấy bạn bè mình liên tục xả rác tại khu dã ngoại, bạn cũng có rác trên tay. Hành động của bạn là: | Nhặt rác của mình bỏ vào thùng, và nhặt luôn cả phần của bạn bè (4·Dấn thân) | Bỏ rác của mình vào thùng, nhưng không nói gì các bạn (3·Kỷ luật) | Lén giấu rác vào bụi cây để không ai thấy (1·Hình ảnh) | Vứt luôn xuống đất giống bạn bè cho tiện (0·Quan hệ) |
| 28 | Q28 | SC | Trong buổi họp kín, mọi người đều đồng tình loại một thành viên ra khỏi nhóm dù người đó không có lỗi lớn. Bạn nghĩ người đó bị oan. Bạn sẽ: | Đứng lên phản đối mạnh mẽ và đưa ra lý lẽ bảo vệ người đó (4·Dấn thân) | Yêu cầu xem xét lại theo đúng quy định của nhóm (3·Kỷ luật) | Đồng tình theo số đông để không bị nhóm ghét lây (1·Quan hệ) | Im lặng không vote, kệ mọi chuyện ra sao thì ra (0·Tiện lợi) |
| 29 | Q29 | SC | Thấy một người bạn mượn xe của mình mà không đổ xăng nhiều lần, bạn rất khó chịu. Phản ứng của bạn: | Thẳng thắn đưa ra quy tắc mượn xe phải tự đổ xăng rõ ràng (4·Kỷ luật) | Nói đùa khéo léo để bạn ấy tự hiểu và tự đi đổ (3·Hình ảnh) | Cứ bực mình nhưng không dám nói vì sợ sứt mẻ tình bạn (1·Quan hệ) | Nói dối là xe bị hỏng để không cho mượn nữa (0·Tiện lợi) |
| 30 | Q30 | **MC** | Bạn thường bị thuyết phục tham gia các hoạt động ngoại khóa bởi những lý do nào? (Chọn nhiều đáp án) | Được bạn bè thân thiết rủ rê, đi cùng cho có phong trào (2·Quan hệ) | Có cơ hội nhận giấy khen hoặc làm đẹp hồ sơ cá nhân (2·Hình ảnh) | Nội dung chương trình thực sự mang lại giá trị giải quyết vấn đề (4·Dấn thân) | Hoạt động tổ chức gần nhà, thời gian ngắn, dễ dàng tham gia (1·Tiện lợi) |

### 📦 Lô 3B2 — Tầng cuối, Nhóm B thấp (5 câu)

| ID | Mã | Loại | Nội Dung Đầy Đủ | A (đ·tag) | B (đ·tag) | C (đ·tag) | D (đ·tag) |
|----|----|------|-----------------|-----------|-----------|-----------|-----------|
| 31 | Q31 | SC | Đi trên đường thấy một nắp cống bị mất, có thể gây nguy hiểm. Bạn có dừng lại báo cho cơ quan chức năng hoặc đặt vật cảnh báo không? | Chặn đường lấy cây làm vật cản và gọi ngay đường dây nóng (4·Dấn thân) | Chụp ảnh đăng lên mạng xã hội để cảnh báo mọi người (3·Hình ảnh) | Đứng xem có ai bị lọt cống không để quay clip câu view (1·Hình ảnh) | Lách xe né qua rồi đi thẳng về nhà ăn cơm cho nhanh (0·Tiện lợi) |
| 32 | Q32 | SC | Bạn nhặt được một chiếc điện thoại đắt tiền nhưng đã khóa màn hình. Xung quanh không có ai. Bạn sẽ làm gì? | Mang đến đồn Công an gần nhất để giao nộp (4·Kỷ luật) | Đăng lên Facebook trường để tìm người rơi và nhận lời cảm ơn (3·Hình ảnh) | Để lại chỗ cũ, không động vào vì sợ bị dàn cảnh tống tiền (1·Quan hệ) | Tháo sim, đem đi bẻ khóa bán lấy tiền xài (0·Tiện lợi) |
| 33 | Q33 | SC | Nhóm trưởng không phân chia công việc rõ ràng dẫn đến bạn phải làm quá nhiều. Khi nộp bài lấy điểm chung, bạn sẽ: | Đưa bảng đánh giá công việc rõ ràng để trừ điểm những người lười (4·Kỷ luật) | Nhận hết công lao về mình trước mặt giảng viên (2·Hình ảnh) | Phàn nàn nhưng cuối cùng vẫn cho mọi người điểm cao như nhau (1·Quan hệ) | Xóa bớt tên các bạn ra khỏi báo cáo mà không nói lời nào (0·Tiện lợi) |
| 34 | Q34 | SC | Thấy một đám đánh nhau ngoài cổng trường, có nguy cơ gây thương tích nghiêm trọng. Bạn phản ứng ra sao? | Hô hoán mọi người xung quanh cùng vào can ngăn (4·Dấn thân) | Báo ngay cho bảo vệ trường hoặc công an phường (3·Kỷ luật) | Đứng từ xa quay video để hóng biến (1·Hình ảnh) | Chạy nhanh đi đường khác kẻo bị vạ lây (0·Tiện lợi) |
| 35 | Q35 | **MC** | Khi đối mặt với yêu cầu sinh viên đóng góp thêm công sức, phản ứng thường thấy của bạn là gì? (Chọn nhiều đáp án) | Tìm cách né tránh hoặc làm ở mức tối thiểu để đối phó (0·Tiện lợi) | Chờ xem số đông các bạn trong lớp làm gì rồi mới làm theo (1·Quan hệ) | Lên mạng xã hội đăng bài than vãn để tìm sự đồng cảm (1·Hình ảnh) | Nghiên cứu kỹ quy định xem việc này có thực sự bắt buộc hay không (2·Kỷ luật) |

### 📊 Tổng Hợp Câu Hỏi

| Thống Kê | Giá Trị |
|----------|---------|
| Tổng số câu hỏi | **35** |
| Loại SC (Single Choice) | **28 câu** (80%) |
| Loại MC (Multiple Choice) | **7 câu** (20%) |
| Số lô (batch) | **7 lô** |
| Câu hỏi mỗi lô | **5 câu** |

---

## 3. Tags Hành Vi & Ý Nghĩa

| Tag | Ý Nghĩa | Điểm Điển Hình |
|-----|---------|----------------|
| 🔥 **Dấn thân** | Chủ động, nhiệt huyết, vì lợi ích chung | 3–4 |
| 📋 **Kỷ luật** | Tuân thủ quy tắc, nguyên tắc, trách nhiệm | 3–4 |
| 🌐 **Quan hệ** | Ưu tiên hòa khí, tập thể, cảm xúc nhóm | 1–3 |
| 🌟 **Hình ảnh** | Quan tâm đến danh tiếng cá nhân | 1–3 |
| 😴 **Tiện lợi** | Né tránh, ưu tiên lợi ích cá nhân tức thời | 0–1 |

---

## 4. Người Tham Gia (participants) — 20 người

| ID | MSSV | Họ Tên | Khoa |
|----|------|--------|------|
| 101 | 26A4010101 | Nguyễn Văn Anh | Công nghệ thông tin và Kinh tế số |
| 102 | 26A4010102 | Trần Thị Bình | Tài Chính |
| 103 | 26A4010103 | Lê Hoàng Cường | Ngân Hàng |
| 104 | 26A4010104 | Phạm Minh Đức | Kế toán - Kiểm toán |
| 105 | 26A4010105 | Đỗ Thanh Hải | Quản trị kinh doanh |
| 106 | 26A4010106 | Hoàng Thị Yến | Kinh doanh quốc tế |
| 107 | 26A4010107 | Bùi Xuân Huấn | Luật Kinh tế |
| 108 | 26A4010108 | Vũ Kim Liên | Ngoại ngữ |
| 109 | 26A4010109 | Lý Tiểu Long | Kinh tế |
| 110 | 26A4010110 | Ngô Kiến Huy | Công nghệ thông tin và Kinh tế số |
| 111 | 26A4010111 | Phan Mạnh Quỳnh | Tài Chính |
| 112 | 26A4010112 | Sơn Tùng M-TP | Ngân Hàng |
| 113 | 26A4010113 | Đen Vâu | Kế toán - Kiểm toán |
| 114 | 26A4010114 | Hòa Minzy | Quản trị kinh doanh |
| 115 | 26A4010115 | Erik | Kinh doanh quốc tế |
| 116 | 26A4010116 | Đức Phúc | Luật Kinh tế |
| 117 | 26A4010117 | Suboi | Ngoại ngữ |
| 118 | 26A4010118 | Karik | Kinh tế |
| 119 | 26A4010119 | Binz | Công nghệ thông tin và Kinh tế số |
| 120 | 26A4010120 | JustaTee | Tài Chính |

### Phân Bố Theo Khoa

| Khoa | Số Người |
|------|---------|
| Công nghệ thông tin và Kinh tế số | 3 |
| Tài Chính | 3 |
| Ngân Hàng | 2 |
| Kế toán - Kiểm toán | 2 |
| Quản trị kinh doanh | 2 |
| Kinh doanh quốc tế | 2 |
| Luật Kinh tế | 2 |
| Ngoại ngữ | 2 |
| Kinh tế | 2 |

---

## 5. Lượt Làm Bài (attempts) — 20 lượt

| ID | Họ Tên | Lô Kết Thúc | Điểm | Trạng Thái |
|----|--------|------------|------|------------|
| 101 | Nguyễn Văn Anh | 3A1 | 52.0 | ✅ Hoàn thành |
| 102 | Trần Thị Bình | 3A2 | 45.0 | ✅ Hoàn thành |
| 103 | Lê Hoàng Cường | 3B1 | 30.0 | ✅ Hoàn thành |
| 104 | Phạm Minh Đức | 3B2 | 22.0 | ✅ Hoàn thành |
| 105 | Đỗ Thanh Hải | 3A1 | 58.0 | ✅ Hoàn thành |
| 106 | Hoàng Thị Yến | 3A2 | 41.0 | ✅ Hoàn thành |
| 107 | Bùi Xuân Huấn | 3B1 | 28.0 | ✅ Hoàn thành |
| 108 | Vũ Kim Liên | 3B2 | 15.0 | ✅ Hoàn thành |
| 109 | Lý Tiểu Long | 3A1 | 60.0 | ✅ Hoàn thành |
| 110 | Ngô Kiến Huy | 3A2 | 38.0 | ✅ Hoàn thành |
| 111 | Phan Mạnh Quỳnh | 3B1 | 33.0 | ✅ Hoàn thành |
| 112 | Sơn Tùng M-TP | 3A1 | 55.0 | ✅ Hoàn thành |
| 113 | Đen Vâu | 3A2 | 47.0 | ✅ Hoàn thành |
| 114 | Hòa Minzy | 3B2 | 18.0 | ✅ Hoàn thành |
| 115 | Erik | 3B1 | 31.0 | ✅ Hoàn thành |
| 116 | Đức Phúc | 3A1 | 49.0 | ✅ Hoàn thành |
| 117 | Suboi | 3A2 | 42.0 | ✅ Hoàn thành |
| 118 | Karik | 3B1 | 25.0 | ✅ Hoàn thành |
| 119 | Binz | 3A1 | 56.0 | ✅ Hoàn thành |
| 120 | JustaTee | 3A2 | 44.0 | ✅ Hoàn thành |

### Tổng Hợp Điểm Số

| Thống Kê | Giá Trị |
|----------|---------|
| Điểm cao nhất | **60.0** (Lý Tiểu Long) |
| Điểm thấp nhất | **15.0** (Vũ Kim Liên) |
| Điểm trung bình | **38.7** |

### Phân Bố Lô Kết Thúc

| Lô | Số Người | % |
|----|---------|---|
| 3A1 | 6 | 30% |
| 3A2 | 6 | 30% |
| 3B1 | 5 | 25% |
| 3B2 | 3 | 15% |

---

## 6. Sơ Đồ Luồng Phân Nhánh (Adaptive Logic)

```
              [Lô 1: Q01–Q05]
              Điểm cao → 2A
              Điểm thấp → 2B
                    │
         ┌──────────┴──────────┐
      [Lô 2A: Q06–Q10]   [Lô 2B: Q11–Q15]
       Cao→3A1               Cao→3B1
       Thấp→3A2              Thấp→3B2
         │                        │
   ┌─────┴─────┐            ┌─────┴─────┐
[3A1]      [3A2]        [3B1]       [3B2]
Q16–Q20   Q21–Q25     Q26–Q30    Q31–Q35
```

---

*Nguồn: `database/database.sql` — Tạo lúc 2026-05-04*
