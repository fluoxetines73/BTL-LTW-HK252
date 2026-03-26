# Hướng dẫn thiết lập Database và chạy test dự án BTL-LTW-HK252

## 1. Mục tiêu

Tài liệu này hướng dẫn đầy đủ từ bước nhập dữ liệu database đến bước chạy và test các chức năng chính của dự án trên môi trường local.

## 2. Yêu cầu môi trường

- PHP 8.x
- MySQL 8.x hoặc MySQL Community Server mới hơn
- Trình duyệt web
- Terminal

## 3. Đường dẫn dự án

Không dùng đường dẫn cố định theo tên máy. Bạn chỉ cần xác định thư mục gốc dự án `BTL-LTW-HK252` trên máy mình.

Ví dụ:

- macOS: `/Users/<your-username>/.../BTL-LTW-HK252`
- Windows: `C:\Users\<your-username>\...\BTL-LTW-HK252`

## 4. Tạo và nhập database

### 4.0 Xác định đường dẫn file SQL

Trong mọi lệnh import bên dưới, thay `<PROJECT_PATH>` bằng đường dẫn thực tế đến thư mục dự án `BTL-LTW-HK252`.

Ví dụ:

- macOS: `<PROJECT_PATH>/database/schema.sql`
- Windows: `<PROJECT_PATH>\\database\\schema.sql`

### Bước 4.1: Mở MySQL

macOS (Terminal):

    mysql -u root -p

Windows (PowerShell hoặc CMD):

    mysql -u root -p

Nếu báo `mysql is not recognized` trên Windows, mở MySQL Command Line Client hoặc thêm thư mục `bin` của MySQL vào `PATH`.

Nhập mật khẩu MySQL khi được hỏi.

### Bước 4.2: Tạo database

Trong màn hình MySQL, chạy:

    CREATE DATABASE IF NOT EXISTS cgv_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    EXIT;

### Bước 4.3: Import schema

macOS:

    mysql -u root -p cgv_booking < <PROJECT_PATH>/database/schema.sql

Windows (CMD):

    mysql -u root -p cgv_booking < <PROJECT_PATH>\database\schema.sql

Windows (PowerShell, nếu toán tử `<` gây lỗi):

    Get-Content <PROJECT_PATH>\database\schema.sql | mysql -u root -p cgv_booking

### Bước 4.4: Import seed

macOS:

    mysql -u root -p cgv_booking < <PROJECT_PATH>/database/seed.sql

Windows (CMD):

    mysql -u root -p cgv_booking < <PROJECT_PATH>\database\seed.sql

Windows (PowerShell, nếu toán tử `<` gây lỗi):

    Get-Content <PROJECT_PATH>\database\seed.sql | mysql -u root -p cgv_booking

## 5. Cấu hình kết nối database trong dự án

Mở file:

configs/database.php

Đặt giá trị đúng với máy của bạn:

    <?php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'cgv_booking');
    define('DB_USER', 'root');
    define('DB_PASS', 'mat_khau_mysql_cua_ban');

Lưu ý:

- DB_NAME phải là cgv_booking
- DB_USER và DB_PASS phải đúng tài khoản MySQL bạn đang dùng

## 6. Chạy ứng dụng local

### Bước 6.1: Vào thư mục dự án

macOS:

    cd <PROJECT_PATH>

Windows (CMD hoặc PowerShell):

    cd <PROJECT_PATH>

### Bước 6.2: Chạy server PHP

    php -S localhost:8000

### Bước 6.3: Mở trình duyệt

Mở:

http://localhost:8000

## 7. Tài khoản test mẫu

Sau khi import seed, có thể dùng:

- Admin
  - Email: admin@example.com
  - Password: xem trong tài liệu nội bộ

- Member mẫu
  - test@example.com / password
  - test2@example.com / password
  - member@test.com / password

## 8. Checklist test nhanh

### 8.1 Test đăng nhập

1. Vào trang đăng nhập.
2. Nhập tài khoản admin.
3. Đăng nhập thành công và điều hướng đúng khu vực quản trị.

### 8.2 Test quản lý người dùng cho Admin

1. Vào trang danh sách user.
2. Thử các tác vụ:
   - Xem danh sách
   - Tìm kiếm
   - Sửa thông tin
   - Reset mật khẩu
   - Khóa hoặc mở khóa
   - Xóa user
3. Kiểm tra thông báo thành công hoặc lỗi phù hợp.

### 8.3 Test validate input form

1. Thử email sai định dạng.
2. Thử để trống tên.
3. Thử số điện thoại sai định dạng.
4. Kiểm tra hệ thống chặn dữ liệu không hợp lệ.

### 8.4 Test phân trang

1. Vào danh sách quản lý user.
2. Chuyển trang next, prev.
3. Kiểm tra số trang và dữ liệu hiển thị đúng theo từng trang.

### 8.5 Test upload ảnh nội bộ server

1. Vào trang sửa user.
2. Upload avatar mới.
3. Lưu form.
4. Kiểm tra ảnh hiển thị sau khi lưu.
5. Kiểm tra file ảnh đã có trong thư mục:

public/uploads/avatars

Yêu cầu đạt:

- Không dùng URL ảnh ngoài
- Ảnh hiển thị từ server local

## 9. Câu lệnh kiểm tra database

### Kiểm tra bảng đã tạo:

    mysql -u root -p -e "USE cgv_booking; SHOW TABLES;"

### Kiểm tra dữ liệu user:

    mysql -u root -p -e "USE cgv_booking; SELECT id,email,full_name,role,status,avatar FROM users;"

## 10. Lỗi thường gặp và cách xử lý

### 10.1 Không đăng nhập được

- Kiểm tra DB_NAME trong configs/database.php có đúng cgv_booking không
- Kiểm tra đã import seed.sql chưa
- Kiểm tra password tài khoản seed có đúng password không

### 10.2 Trang không chuyển đúng route khi bấm menu

- Tắt server đang chạy
- Chạy lại php -S localhost:8000
- Hard refresh trình duyệt

### 10.3 Lỗi bảng không tồn tại, ví dụ products

- Nguyên nhân: schema mới không có bảng products
- Cách xử lý: dùng đúng route/chức năng đã map với schema mới, hoặc cập nhật module cũ cho phù hợp schema hiện tại

### 10.4 Upload ảnh xong nhưng không hiển thị

- Kiểm tra file có xuất hiện trong public/uploads/avatars hay chưa
- Hard refresh trình duyệt
- Kiểm tra quyền ghi thư mục upload

## 11. Reset lại database khi cần test lại từ đầu

Chạy lại 2 lệnh import theo đúng thứ tự:

macOS:

    mysql -u root -p cgv_booking < <PROJECT_PATH>/database/schema.sql
    mysql -u root -p cgv_booking < <PROJECT_PATH>/database/seed.sql

Windows (CMD):

    mysql -u root -p cgv_booking < <PROJECT_PATH>\database\schema.sql
    mysql -u root -p cgv_booking < <PROJECT_PATH>\database\seed.sql

Windows (PowerShell):

    Get-Content <PROJECT_PATH>\database\schema.sql | mysql -u root -p cgv_booking
    Get-Content <PROJECT_PATH>\database\seed.sql | mysql -u root -p cgv_booking

Schema đã có câu lệnh drop table nên có thể reset dữ liệu về trạng thái sạch.

data sai cho chó Danh
