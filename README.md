# 🎯 Adaptive SJT Survey System

> Hệ thống Khảo sát Trắc nghiệm Tình huống Thích ứng (Adaptive Situational Judgment Test) phân tích hành vi và xu hướng ra quyết định của sinh viên.

---

## 📖 Overview

- **Problem:** Khảo sát truyền thống thường tuyến tính và nhàm chán, không đánh giá đúng phản xạ thực tế của người tham gia khi đối mặt với các tình huống phức tạp.
- **Solution:** Hệ thống áp dụng phương pháp SJT kết hợp logic rẽ nhánh thích ứng (Adaptive Branching). Căn cứ vào các lựa chọn trước đó của sinh viên, hệ thống sẽ tự động điều chỉnh độ khó và nội dung các câu hỏi tiếp theo, sau đó đúc kết ra "chân dung hành vi" (Archetype).
- **Đối tượng sử dụng:** 
  - **Sinh viên:** Tham gia khảo sát và nhận phân tích cá nhân.
  - **Admin / Nhà trường:** Thu thập dữ liệu thống kê tổng quan toàn trường.

## ✨ Features

- 🧠 **Adaptive Branching Engine:** Luồng câu hỏi thay đổi động dựa trên điểm số (Cost-tag) của các câu trả lời trước đó.
- 📊 **Cá nhân hóa kết quả (Personal Dashboard):** Vẽ biểu đồ Radar phân tích 4 khía cạnh hành vi và xếp loại người dùng vào 4 Archetypes (Nhà Quản Trị, Người Đồng Hành, v.v.).
- 📈 **Admin Analytics:** Dashboard quản trị thống kê phân bổ sinh viên theo khoa, điểm trung bình, và xu hướng hành vi (sử dụng Chart.js).
- ⚡ **Kiến trúc MVC tối giản:** Framework tự build bằng Pure PHP, siêu nhẹ và dễ mở rộng

## 🛠 Tech Stack

- **Frontend:** HTML5, CSS3, TailwindCSS, ES6+
- **Biểu đồ:** Chart.js v4
- **Backend:** Pure PHP 8.0+ (Custom MVC Framework)
- **Database:** MySQL 5.7+ / MariaDB 10.3+ (PDO)
- **Server:** Apache (XAMPP/WAMP) với `mod_rewrite`

## 📁 Project Structure

Dự án áp dụng kiến trúc **Module-Based** để các thành viên phát triển Fullstack:

```text
Survey_System/
├── Core/                        # [M1] Mini-framework & Router
├── app/                         # Logic ứng dụng chính
│   ├── Controllers/             # [M3, M5] Điều hướng luồng Survey, Admin
│   ├── Models/                  # [M3, M4, M5] Logic DB (Question, Result, Analytics)
│   ├── Services/                # [M2] AdaptiveLogic Engine
│   └── Views/                   # [M3, M4, M5] Giao diện người dùng
├── config/                      # [M6] File cấu hình ứng dụng
├── database/                    # [M6] File backup database.sql
├── docs/                        # [M6] Tài liệu thiết kế & quản lý dự án
└── public/
    ├── index.php                # Front Controller duy nhất
    └── .htaccess                # Cấu hình Apache mod_rewrite
```

## 🚀 Installation & Setup

**1. Clone project và thiết lập thư mục**
Đặt source code vào thư mục webroot của XAMPP:
```bash
git clone https://github.com/your-repo/Survey_System.git
# Chuyển vào htdocs: C:\xampp\htdocs\KT2\Survey_System\
```

**2. Setup Database**
- Khởi động **Apache** và **MySQL** trên XAMPP.
- Mở `http://localhost/phpmyadmin`
- Import file cơ sở dữ liệu có sẵn tại: `database/database.sql`

**3. Setup Môi trường**
Mở file `config/app.php` và cấu hình các biến cơ bản:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'survey_system');
define('BASE_URL', 'http://localhost/KT2/Survey_System/public');
```

**4. Khởi chạy**
- Trang Sinh viên: `http://localhost/KT2/Survey_System/public/`
- Trang Admin: `http://localhost/KT2/Survey_System/public/admin`

## 🗄 Database Design

Hệ thống xoay quanh 5 bảng chính:
- `questions` & `answer_options`: Lưu trữ câu hỏi theo lô (batch) và trọng số điểm (cost).
- `users`: Thông tin sinh viên (MSSV, Tên, Khoa).
- `attempts`: Lưu lượt làm bài khảo sát (thời gian, điểm tổng, archetype kết quả).
- `attempt_answers`: Chi tiết từng câu trả lời của sinh viên.

## 💻 Usage

1. **Sinh viên:** Truy cập trang chủ -> Điền thông tin -> Làm Lô 1 (5 câu). Tùy vào điểm Lô 1, hệ thống rẽ nhánh sang Lô 2A/2B -> Nhận kết quả Radar Chart ngay lập tức.
2. **Admin:** Truy cập route `/admin` để xem Doughnut chart thống kê phân bổ khoa và Radar chart tổng thể.

## 📸 Screenshots / Demo

*(Thêm hình ảnh thực tế của dự án tại đây)*
- ![Trang Chủ](placeholder_home.png)
- ![Dashboard Sinh Viên](placeholder_result.png)
- ![Admin Analytics](placeholder_admin.png)

## 👥 Team & Contribution

Dự án áp dụng phương pháp **Quản lý theo Module** (Fullstack ownership):
- **Phúc (Lead):** Core Framework & Thuật toán AdaptiveLogic
- **Hiếu:** Module Khảo sát (Question/Response Flow)
- **Khanh:** Module Kết quả (Tính điểm & Vẽ Radar)
- **Trang Anh:** Module Quản trị (Admin Analytics & Dashboard)
- **Tú:** Nền tảng Vận hành (DB Config, DevOps, Testing)

## 🔄 Git Workflow

Dự án tuân thủ **GitHub Flow**:
- `main`: Code ổn định, production-ready.
- `develop`: Nhánh tích hợp chính.
- `feature/[ten-ban]-[ten-task]`: Nhánh phát triển tính năng riêng của từng thành viên (VD: `feature/khanh-result-dashboard`).

Quy tắc: **KHÔNG** push trực tiếp lên `main` hoặc `develop`. Mọi cập nhật phải qua Pull Request.

## ⚠️ Known Issues / Limitations

- Trang `/admin` hiện tại chỉ dành cho mục đích demo nội bộ, chưa tích hợp Middleware xác thực (Authentication).
- Dữ liệu khảo sát ban đầu (Seed data) là dữ liệu giả lập, cần cập nhật ngân hàng câu hỏi chuẩn tâm lý học trước khi ra thực tế.

## 🔮 Future Improvements

- Tích hợp đăng nhập SSO với tài khoản nhà trường.
- Xuất báo cáo PDF tự động cho sinh viên.
- Thêm module phân tích AI để đọc và phân loại dữ liệu dạng text tự do.

## 📄 License
Đồ án môn học --- Phát triển nội bộ.
