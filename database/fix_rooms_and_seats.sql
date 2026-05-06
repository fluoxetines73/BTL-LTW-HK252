
-- Bước 1: Tắt kiểm tra khóa ngoại để dọn dẹp dữ liệu cũ an toàn[cite: 9]
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `tickets`;
TRUNCATE TABLE `booking_combos`;
TRUNCATE TABLE `bookings`;
TRUNCATE TABLE `showtimes`;
TRUNCATE TABLE `seats`;
TRUNCATE TABLE `rooms`;
SET FOREIGN_KEY_CHECKS = 1;

-- Bước 2: Thiết lập lại 3 phòng chiếu theo yêu cầu của bạn[cite: 10]
-- Phòng 1: 60 ghế (6 hàng x 10 cột)
-- Phòng 2: 120 ghế (10 hàng x 12 cột)
-- Phòng 3: 150 ghế (10 hàng x 15 cột)
INSERT INTO `rooms` (`id`, `cinema_id`, `name`, `total_rows`, `total_cols`, `screen_type`, `status`) VALUES
(1, 1, 'Phòng 1 (60 Ghế)', 6, 10, '2D', 'active'),
(2, 1, 'Phòng 2 (120 Ghế)', 10, 12, '3D', 'active'),
(3, 1, 'Phòng 3 (150 Ghế)', 10, 15, '2D', 'active');

-- Bước 3: Tự động tạo dữ liệu Ghế cho từng phòng (Feed data)
-- Tạo ghế cho Phòng 1 (Hàng A-F, Cột 1-10)
INSERT INTO `seats` (`room_id`, `seat_type_id`, `row_label`, `col_number`)
SELECT 1, 1, r.r_label, c.c_num
FROM 
    (SELECT 'A' as r_label UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' UNION SELECT 'F') r
CROSS JOIN 
    (SELECT 1 as c_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
     SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10) c;

-- Tạo ghế cho Phòng 2 (Hàng A-J, Cột 1-12)
INSERT INTO `seats` (`room_id`, `seat_type_id`, `row_label`, `col_number`)
SELECT 2, 1, r.r_label, c.c_num
FROM 
    (SELECT 'A' as r_label UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' 
     UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H' UNION SELECT 'I' UNION SELECT 'J') r
CROSS JOIN 
    (SELECT 1 as c_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
     SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) c;

-- Tạo ghế cho Phòng 3 (Hàng A-J, Cột 1-15)
INSERT INTO `seats` (`room_id`, `seat_type_id`, `row_label`, `col_number`)
SELECT 3, 1, r.r_label, c.c_num
FROM 
    (SELECT 'A' as r_label UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E' 
     UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H' UNION SELECT 'I' UNION SELECT 'J') r
CROSS JOIN 
    (SELECT 1 as c_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
     SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION 
     SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15) c;
