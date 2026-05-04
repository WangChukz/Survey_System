# 📘 Hướng Dẫn Thao Tác Git — Survey System
> **Dự án:** Survey_System | **Nhóm:** Phuc · Khanh · TrangAnh · Hieu · Tu
> **Remote:** https://github.com/WangChukz/Survey_System.git

---

## 🌿 Sơ Đồ Nhánh

```
main  ← nhánh chính, chỉ merge khi hoàn thành
├── Phuc       ← TV1: Home & Participant
├── Khanh      ← TV2: Auth & UI/CSS
├── TrangAnh   ← TV3: Survey Frontend & JS
├── Hieu       ← TV4: Survey Backend & Models
└── Tu         ← TV5: Admin Panel & Analytics
```

> ⚠️ **KHÔNG bao giờ commit thẳng lên `main`**

---

## 🚀 Lần Đầu Cài Đặt (Chạy 1 lần duy nhất)

### 1. Clone dự án về máy
```bash
git clone https://github.com/WangChukz/Survey_System.git
cd Survey_System
```

### 2. Xem tất cả nhánh
```bash
git branch -a
```

### 3. Checkout về nhánh của mình
```bash
# Thay [TênMình] bằng: Phuc / Khanh / TrangAnh / Hieu / Tu
git checkout [TênMình]
```

Ví dụ:
```bash
git checkout TrangAnh
```

---

## 📅 Quy Trình Làm Việc Hàng Ngày

### Buổi sáng — Đồng bộ code mới nhất từ main

```bash
# 1. Về nhánh main để lấy code mới
git checkout main
git pull origin main

# 2. Quay về nhánh của mình
git checkout [TênMình]

# 3. Lấy code mới từ main vào nhánh mình
git merge main
```

### Làm việc — Commit thường xuyên

```bash
# Xem những file nào đã thay đổi
git status

# Thêm file vào staging (CHỈ file của mình)
git add tên-file.php

# Hoặc thêm tất cả thay đổi
git add .

# Commit với message mô tả rõ ràng
git commit -m "feat: thêm form đăng ký participant"
```

### Cuối ngày — Push lên GitHub

```bash
git push origin [TênMình]
```

Ví dụ:
```bash
git push origin Hieu
```

---

## 📝 Quy Tắc Viết Commit Message

**Format:** `[loại]: mô tả ngắn gọn bằng tiếng Việt`

| Loại | Dùng khi |
|------|---------|
| `feat:` | Thêm tính năng mới |
| `fix:` | Sửa lỗi |
| `style:` | Chỉnh CSS, giao diện |
| `refactor:` | Cải thiện code, không thay đổi tính năng |
| `docs:` | Cập nhật tài liệu |
| `test:` | Thêm/sửa dữ liệu test |

**Ví dụ:**
```bash
git commit -m "feat: thêm timer đếm ngược cho câu hỏi"
git commit -m "fix: sửa lỗi submit khi chọn MC"
git commit -m "style: cập nhật màu nút theo design system"
git commit -m "refactor: tách logic tính điểm ra ResultModel"
```

---

## 🔄 Gộp Code Vào Main (Khi Xong Feature)

### Cách 1 — Tạo Pull Request trên GitHub (Khuyên dùng)
1. Vào **GitHub → Survey_System → Pull requests**
2. Click **"New pull request"**
3. Chọn: `base: main` ← `compare: [TênMình]`
4. Điền mô tả → **"Create pull request"**
5. Nhờ **Phuc** (Team Lead) review và merge

### Cách 2 — Merge trực tiếp (Khi khẩn cấp, chỉ Phuc làm)
```bash
git checkout main
git merge [TênMình]
git push origin main
```

---

## 🆘 Xử Lý Các Tình Huống Thường Gặp

### ❓ Quên đang đứng ở nhánh nào
```bash
git branch
# Nhánh có dấu * là nhánh hiện tại
```

### ❓ Lỡ sửa file của người khác, muốn hoàn tác
```bash
# Hoàn tác 1 file cụ thể về trạng thái ban đầu
git checkout -- tên-file.php

# Hoàn tác TẤT CẢ thay đổi chưa commit
git checkout -- .
```

### ❓ Muốn xem lịch sử commit
```bash
git log --oneline -10
```

### ❓ Bị conflict khi merge
```bash
# 1. Git sẽ báo file nào bị conflict
git status

# 2. Mở file đó trong VS Code, tìm đoạn:
# <<<<<<< HEAD (code của mình)
# =======
# >>>>>>> main (code từ main)

# 3. Giữ lại phần nào đúng, xóa các dòng <<<, ===, >>>

# 4. Sau khi sửa xong:
git add tên-file-đã-sửa.php
git commit -m "fix: resolve conflict trong tên-file"
```

### ❓ Push bị từ chối (rejected)
```bash
# Pull code mới về trước
git pull origin [TênMình]
# Rồi push lại
git push origin [TênMình]
```

### ❓ Muốn xem code người khác đang làm
```bash
git fetch origin
git checkout Hieu   # xem code của Hieu
git checkout [TênMình]  # quay lại nhánh mình
```

---

## 🚫 Những Điều KHÔNG ĐƯỢC Làm

```bash
# ❌ KHÔNG commit thẳng lên main
git checkout main
git commit ...  # KHÔNG ĐƯỢC

# ❌ KHÔNG force push
git push --force  # KHÔNG ĐƯỢC

# ❌ KHÔNG xóa nhánh của người khác
git branch -d Khanh  # KHÔNG ĐƯỢC

# ❌ KHÔNG sửa file ngoài phạm vi của mình
# Xem bảng phân chia trong docs/vibe_code_plan.md
```

---

## 📞 Liên Hệ Khi Gặp Vấn Đề

| Vấn đề | Nhờ ai |
|--------|--------|
| Lỗi database / routes | Phuc |
| Lỗi CSS / layout | Khanh |
| Lỗi JS / survey UI | TrangAnh |
| Lỗi API / backend | Hieu |
| Lỗi admin / dashboard | Tu |
| Conflict không tự giải quyết được | **Họp nhóm nhanh** |

---

## 🔑 Lệnh Git Hay Dùng Nhất

```bash
git status              # Xem trạng thái
git branch              # Xem đang ở nhánh nào
git pull origin [nhánh] # Kéo code mới về
git add .               # Thêm tất cả thay đổi
git commit -m "..."     # Lưu thay đổi
git push origin [nhánh] # Đẩy lên GitHub
git log --oneline -5    # Xem 5 commit gần nhất
git checkout [nhánh]    # Chuyển nhánh
```

---

*Tài liệu này dành cho nhóm Survey_System — Cập nhật 2026-05-04*
