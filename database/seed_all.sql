-- Bước 1: Dọn dẹp dữ liệu cũ để tránh lỗi trùng lặp (Duplicate entry)[cite: 5]
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `movie_genres`;
TRUNCATE TABLE `movies`;
TRUNCATE TABLE `combos`;
TRUNCATE TABLE `combo_items`;
-- Đảm bảo showtimes và booking_combos cũng sạch để không lỗi logic[cite: 5]
TRUNCATE TABLE `showtimes`;
TRUNCATE TABLE `booking_combos`;
SET FOREIGN_KEY_CHECKS = 1;

-- Bước 2: THÊM 10 BỘ PHIM MỚI[cite: 5]
INSERT INTO `movies` (`id`, `title`, `slug`, `description`, `duration_min`, `release_date`, `age_rating`, `status`) VALUES
(1, 'Mai', 'mai', 'Phim tâm lý tình cảm Việt Nam', 131, '2026-02-10', 'C16', 'now_showing'),
(2, 'Lật Mặt 7: Một Điều Ước', 'lat-mat-7', 'Hành trình gia đình cảm động', 138, '2026-04-26', 'C13', 'now_showing'),
(3, 'Godzilla x Kong', 'godzilla-x-kong', 'Đại chiến quái vật khổng lồ', 115, '2026-03-29', 'C13', 'now_showing'),
(4, 'Kung Fu Panda 4', 'kung-fu-panda-4', 'Gấu Po trở lại lợi hại hơn', 94, '2026-03-08', 'P', 'now_showing'),
(5, 'Dune: Hành Tinh Cát 2', 'dune-2', 'Sử thi khoa học viễn tưởng', 166, '2026-03-01', 'C13', 'now_showing'),
(6, 'Exhuma: Quật Mộ Trùng Tang', 'exhuma', 'Phim kinh dị bí ẩn Hàn Quốc', 134, '2026-03-15', 'C16', 'now_showing'),
(7, 'Thanh Xuân 18x2', 'thanh-xuan-18x2', 'Câu chuyện tình yêu lãng mạn', 124, '2026-04-05', 'C13', 'now_showing'),
(8, 'Bầu Trời Rực Đỏ', 'bau-troi-ruc-do', 'Phim hành động kịch tính', 110, '2026-06-01', 'C18', 'coming_soon'),
(9, 'Mèo Béo Siêu Quậy', 'meo-beo-sieu-quay', 'Hoạt hình gia đình vui nhộn', 101, '2026-05-20', 'P', 'coming_soon'),
(10, 'Kẻ Kiến Tạo', 'the-creator', 'Tương lai giữa người và AI', 133, '2026-05-30', 'C13', 'coming_soon');

-- Bước 3: THÊM 10 SUẤT CHIẾU (Sử dụng đúng ID phòng 1, 2, 3 đã tạo)[cite: 5]
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
(2, 3, '2026-05-13 22:00:00', '2026-05-14 00:18:00', 140000.00, 'scheduled');

-- Bước 4: THÊM 10 COMBO BẮP NƯỚC[cite: 5]
INSERT INTO `combos` (`id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 'Combo Solo', '1 Bắp (S) + 1 Nước (S)', 65000.00, 1),
(2, 'Combo Couple', '1 Bắp (L) + 2 Nước (M)', 115000.00, 1),
(3, 'Combo Gia Đình', '2 Bắp (L) + 4 Nước (M)', 195000.00, 1),
(4, 'Combo Tiết Kiệm', '1 Bắp (M) + 1 Nước (M)', 85000.00, 1),
(5, 'Combo Phô Mai', '1 Bắp Phô Mai (L) + 1 Nước (L)', 99000.00, 1),
(6, 'Combo Caramel', '1 Bắp Caramel (L) + 1 Nước (L)', 99000.00, 1),
(7, 'Combo Snack', '1 Bắp (M) + 1 Nước (M) + 1 Snack', 105000.00, 1),
(8, 'Combo Bạn Thân', '2 Bắp (M) + 2 Nước (M)', 150000.00, 1),
(9, 'Combo My Melody', '1 Bắp (L) + 1 Ly nhân vật My Melody', 250000.00, 1),
(10, 'Combo Kuromi', '1 Bắp (L) + 1 Ly nhân vật Kuromi', 250000.00, 1);