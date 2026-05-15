-- ============================================================================
-- 06-seed-showtimes-combos.sql
-- Thêm suất chiếu và combo bắp nước (25 suất chiếu + 25 combo)
-- Chạy sau: 05-seed-movies-extended.sql
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- THÊM 25 SUẤT CHIẾU MỚI
-- ============================================
TRUNCATE TABLE `showtimes`;

INSERT INTO `showtimes` (`movie_id`, `room_id`, `start_time`, `end_time`, `base_price`, `status`) VALUES
(1, 1, '2026-05-10 18:00:00', '2026-05-10 20:11:00', 100000.00, 'scheduled'),
(2, 2, '2026-05-10 19:00:00', '2026-05-10 21:18:00', 120000.00, 'scheduled'),
(3, 3, '2026-05-10 20:30:00', '2026-05-10 22:25:00', 150000.00, 'scheduled'),
(4, 1, '2026-05-11 09:00:00', '2026-05-11 10:34:00', 80000.00, 'scheduled'),
(5, 2, '2026-05-11 14:00:00', '2026-05-11 16:46:00', 130000.00, 'scheduled'),
(6, 3, '2026-05-11 21:00:00', '2026-05-11 23:14:00', 110000.00, 'scheduled'),
(1, 2, '2026-05-12 15:00:00', '2026-05-12 17:11:00', 120000.00, 'scheduled'),
(7, 1, '2026-05-12 19:30:00', '2026-05-12 21:34:00', 95000.00, 'scheduled'),
(3, 2, '2026-05-13 10:00:00', '2026-05-13 11:55:00', 110000.00, 'scheduled'),
(2, 3, '2026-05-13 22:00:00', '2026-05-14 00:18:00', 140000.00, 'scheduled'),
(8, 1, '2026-05-14 14:00:00', '2026-05-14 15:50:00', 90000.00, 'scheduled'),
(9, 2, '2026-05-14 16:30:00', '2026-05-14 18:11:00', 85000.00, 'scheduled'),
(10, 3, '2026-05-14 20:00:00', '2026-05-14 22:13:00', 110000.00, 'scheduled'),
(11, 1, '2026-05-15 08:30:00', '2026-05-15 10:42:00', 75000.00, 'scheduled'),
(12, 2, '2026-05-15 13:00:00', '2026-05-15 16:00:00', 120000.00, 'scheduled'),
(13, 3, '2026-05-15 19:00:00', '2026-05-15 21:20:00', 105000.00, 'scheduled'),
(14, 1, '2026-05-16 10:00:00', '2026-05-16 11:42:00', 90000.00, 'scheduled'),
(15, 2, '2026-05-16 15:00:00', '2026-05-16 17:07:00', 130000.00, 'scheduled'),
(18, 3, '2026-05-16 21:00:00', '2026-05-16 23:25:00', 120000.00, 'scheduled'),
(19, 1, '2026-05-17 14:30:00', '2026-05-17 16:58:00', 110000.00, 'scheduled'),
(20, 2, '2026-05-17 18:00:00', '2026-05-17 20:06:00', 95000.00, 'scheduled'),
(21, 3, '2026-05-17 21:00:00', '2026-05-17 22:50:00', 100000.00, 'scheduled'),
(22, 1, '2026-05-18 09:00:00', '2026-05-18 10:40:00', 80000.00, 'scheduled'),
(23, 2, '2026-05-18 16:00:00', '2026-05-18 18:10:00', 140000.00, 'scheduled'),
(25, 3, '2026-05-18 20:30:00', '2026-05-18 22:15:00', 110000.00, 'scheduled');

-- ============================================
-- THÊM 25 COMBO BẮP NƯỚC
-- ============================================
TRUNCATE TABLE `combo_items`;
TRUNCATE TABLE `combos`;

INSERT INTO `combos` (`id`, `name`, `description`, `price`, `image`, `is_active`) VALUES
(1, 'Combo Đơn', '1 Bắp lớn + 1 Nước ngọt lớn', 85000, 'default-combo.png', 1),
(2, 'Combo Đôi', '1 Bắp lớn + 2 Nước ngọt lớn', 115000, 'default-combo.png', 1),
(3, 'Combo Gia Đình', '2 Bắp lớn + 4 Nước ngọt lớn', 195000, 'default-combo.png', 1),
(4, 'Combo Trẻ Em', '1 Bắp vừa + 1 Sữa tươi', 65000, 'default-combo.png', 1),
(5, 'Combo Phô Mai', '1 Bắp phô mai + 1 Nước ngọt', 95000, 'default-combo.png', 1),
(6, 'Combo Caramel', '1 Bắp Caramel + 1 Nước ngọt', 95000, 'default-combo.png', 1),
(7, 'Combo Xúc Xích', '1 Xúc xích + 1 Nước ngọt', 55000, 'default-combo.png', 1),
(8, 'Combo Nachos', '1 Bánh Nachos + 1 Nước ngọt', 75000, 'default-combo.png', 1),
(9, 'Combo Tiết Kiệm', '1 Bắp vừa + 1 Nước vừa', 70000, 'default-combo.png', 1),
(10, 'Combo VIP', 'Bắp lớn + Nước + Quà tặng', 150000, 'default-combo.png', 1),
(11, 'Combo Mix 1', 'Bắp Phô mai & Caramel mix', 105000, 'default-combo.png', 1),
(12, 'Combo Mix 2', 'Bắp Mặn & Ngọt mix', 105000, 'default-combo.png', 1),
(13, 'Nước ngọt Lớn', 'Ly 32oz', 35000, 'default-combo.png', 1),
(14, 'Bắp lớn Phô mai', 'Xô bắp phô mai', 75000, 'default-combo.png', 1),
(15, 'Bắp lớn Caramel', 'Xô bắp Caramel', 75000, 'default-combo.png', 1),
(16, 'Snack Khoai tây', 'Gói snack 100g', 30000, 'default-combo.png', 1),
(17, 'Kẹo dẻo Trái cây', 'Gói kẹo 150g', 40000, 'default-combo.png', 1),
(18, 'Nước suối', 'Chai 500ml', 20000, 'default-combo.png', 1),
(19, 'Trà đào túi lọc', 'Ly trà đào lớn', 45000, 'default-combo.png', 1),
(20, 'Combo Couple Plus', '2 Bắp + 2 Nước + 1 Snack', 145000, 'default-combo.png', 1),
(21, 'Combo Sinh nhật', 'Bắp + Nước + Bánh ngọt', 120000, 'default-combo.png', 1),
(22, 'Combo Học sinh', 'Giảm giá cho thẻ HSSV', 60000, 'default-combo.png', 1),
(23, 'Combo Cuối tuần', 'Giá ưu đãi T7&CN', 90000, 'default-combo.png', 1),
(24, 'Combo Phim Việt', 'Dành riêng cho phim Việt', 80000, 'default-combo.png', 1),
(25, 'Combo Phim Bom Tấn', 'Dành riêng cho phim Marvel/DC', 130000, 'default-combo.png', 1);

SET FOREIGN_KEY_CHECKS = 1;
