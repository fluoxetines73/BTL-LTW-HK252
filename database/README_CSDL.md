# HƯỚNG DẪN CƠ SỞ DỮ LIỆU - CGV BOOKING

> **Lưu ý**: Chạy các file theo đúng thứ tự số từ 01 đến 06

---

## 📋 TỔNG QUAN

Thư mục `database/` chứa các file SQL để thiết lập và khởi tạo dữ liệu cho hệ thống đặt vé CGV Cinema.

---

## 🚀 CÁCH CHẠY

### Bước 1: Tạo Database
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS cgv_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Bước 2: Chạy các file SQL theo thứ tự

**Tối thiểu (chỉ cần 2 file đầu):**
```bash
# 1. Tạo tất cả bảng (schema + comments table)
mysql -u root -p cgv_booking < database/01-schema.sql

# 2. Dữ liệu mẫu cơ bản (users, phim, rạp, booking, FAQ...)
mysql -u root -p cgv_booking < database/02-seed.sql
```

**Đầy đủ (tất cả các file):**
```bash
# 3. Thêm 25 phim mở rộng
mysql -u root -p cgv_booking < database/03-seed-movies.sql

# 4. Thêm 25 suất chiếu + 25 combo bắp nước
mysql -u root -p cgv_booking < database/04-seed-showtimes-combos.sql

# 5. Thêm 42 phim từ TMDB API (tháng 4-7/2026)
mysql -u root -p cgv_booking < database/05-seed-tmdb.sql

# 6. Thiết lập phòng chiếu và ghế (3 phòng: 60/120/150 ghế)
mysql -u root -p cgv_booking < database/06-setup-rooms-seats.sql
```

### Hoặc chạy tất cả một lần (Windows PowerShell):
```powershell
Get-ChildItem database\*.sql | Sort-Object Name | ForEach-Object {
    Get-Content $_.FullName | & mysql -u root -p cgv_booking
}

```

---

## 📁 CHI TIẾT CÁC FILE

| File | Mô tả | Bắt buộc |
|------|-------|----------|
| `01-schema.sql` | Tạo 31+ bảng: users, movies, bookings, showtimes, comments... | ✅ Có |
| `02-seed.sql` | Dữ liệu mẫu cơ bản: 4 users, 6 phim, 2 rạp, booking, FAQ... | ✅ Có |
| `03-seed-movies.sql` | Thêm 25 phim mở rộng | ❌ Tùy chọn |
| `04-seed-showtimes-combos.sql` | 25 suất chiếu + 25 combo bắp nước | ❌ Tùy chọn |
| `05-seed-tmdb.sql` | 42 phim từ TMDB (tháng 4-7/2026) | ❌ Tùy chọn |
| `06-setup-rooms-seats.sql` | Thiết lập 3 phòng: 60, 120, 150 ghế | ❌ Tùy chọn |
| `generate_seeds.php` | Script tạo password hash | ❌ Hỗ trợ |

---

## 👤 TÀI KHOẢN TEST

Sau khi chạy `02-seed.sql`:

| Email | Mật khẩu | Vai trò |
|-------|----------|---------|
| admin@cgv.vn | password | Admin |
| test@example.com | password | Member |
| test2@example.com | password | Member |
| member@test.com | password | Member |

---

## 📊 THỐNG KÊ DỮ LIỆU

| Giai đoạn | Users | Movies | Cinemas | Rooms | Seats | Showtimes | Combos |
|-----------|-------|--------|---------|-------|-------|-----------|--------|
| Sau 02-seed.sql | 4 | 6 | 2 | 7 | ~30 | 6 | 5 |
| Sau 03-seed-movies.sql | 4 | 31 | 2 | 7 | ~30 | 6 | 5 |
| Sau 04-seed-showtimes-combos.sql | 4 | 31 | 2 | 7 | ~30 | 25 | 25 |
| Sau 05-seed-tmdb.sql | 4 | 73 | 2 | 7 | ~30 | 25 | 25 |
| Sau 06-setup-rooms-seats.sql | 4 | 73 | 2 | 3 | 330 | 25 | 25 |

---

## ⚠️ LƯU Ý QUAN TRỌNG

1. **Luôn chạy 01-schema.sql trước** - File này tạo cấu trúc bảng
2. **Chạy theo đúng thứ tự số** - 01 → 02 → 03 → ... → 06
3. **02-seed.sql chứa dữ liệu cơ bản** - Nên chạy để có dữ liệu test
4. **Các file 03-06 là tùy chọn** - Chạy nếu cần thêm dữ liệu
5. **Không chạy lại seed** nếu đã có dữ liệu quan trọng (sẽ bị xóa)

---

## 🔧 XỬ LÝ LỖI

### Lỗi "Unknown database"
→ Chạy lệnh tạo database trước (Bước 1)

### Lỗi "Duplicate entry"
→ Đã có dữ liệu, không cần chạy seed lại

### Lỗi "Cannot add foreign key"
→ Chạy sai thứ tự, phải chạy 01-schema.sql trước

---

## 📝 THÔNG TIN KỸ THUẬT

- **Charset**: utf8mb4 (hỗ trợ tiếng Việt đầy đủ)
- **Engine**: InnoDB (hỗ trợ transaction)
- **PHP**: 8.x
- **MySQL**: 8.x

---

*Cập nhật: Tháng 5/2026 - Nhóm BTL LTW HK252*
