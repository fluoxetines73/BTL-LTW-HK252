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

-- Bước 2: THÊM 25 BỘ PHIM DUY NHẤT (ĐÃ FIX TRÙNG LẶP)
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
(10, 'Kẻ Kiến Tạo', 'the-creator', 'Tương lai giữa người và AI', 133, '2026-05-30', 'C13', 'coming_soon'),
(11, 'Lật Mặt 6: Tấm Vé Định Mệnh', 'lat-mat-6', 'Hành trình tìm kho báu nhóm bạn', 132, '2024-04-28', 'C13', 'ended'),
(12, 'Oppenheimer', 'oppenheimer', 'Cha đẻ bom nguyên tử', 180, '2023-08-11', 'C18', 'ended'),
(13, 'Spider-Man: Across the Spider-Verse', 'spider-verse', 'Hành trình qua đa vũ trụ nhện', 140, '2023-06-01', 'P', 'ended'),
(14, 'Nhà Bà Nữ', 'nha-ba-nu', 'Câu chuyện gia đình Việt nhiều mâu thuẫn', 102, '2023-01-22', 'C16', 'ended'),
(15, 'Deadpool & Wolverine', 'deadpool-wolverine', 'Siêu anh hùng lầy lội nhất MCU', 127, '2024-07-26', 'C18', 'coming_soon'),
(16, 'Inside Out 2', 'inside-out-2', 'Những mảnh ghép cảm xúc mới', 96, '2024-06-14', 'P', 'coming_soon'),
(17, 'Despicable Me 4', 'despicable-me-4', 'Gia đình Gru và binh đoàn Minions', 95, '2024-07-03', 'P', 'coming_soon'),
(18, 'Kingdom of the Planet of the Apes', 'apes-2024', 'Sự trỗi dậy của vương quốc khỉ', 145, '2024-05-10', 'C13', 'now_showing'),
(19, 'Furiosa: A Mad Max Saga', 'furiosa', 'Huyền thoại sa mạc hậu tận thế', 148, '2024-05-24', 'C18', 'now_showing'),
(20, 'The Fall Guy', 'fall-guy', 'Kẻ thế thân hành động hài', 126, '2024-05-03', 'C13', 'now_showing'),
(21, 'Bà Bầu Siêu Quậy', 'ba-bau-sieu-quay', 'Hài hước tình cảm Hàn Quốc', 110, '2024-05-15', 'C16', 'now_showing'),
(22, 'A Quiet Place: Day One', 'quiet-place-day-one', 'Ngày đầu vùng đất câm lặng', 100, '2024-06-28', 'C16', 'coming_soon'),
(23, 'Joker: Folie à Deux', 'joker-2-folie', 'Gã hề điên loạn cùng đồng bọn', 130, '2024-10-04', 'C18', 'coming_soon'),
(24, 'Gladiator II', 'gladiator-2-vosi', 'Võ sĩ giác đấu phần tiếp theo', 140, '2024-11-22', 'C18', 'coming_soon'),
(25, 'Moana 2', 'moana-2-bienca', 'Hành trình biển cả vẫy gọi', 105, '2024-11-27', 'P', 'coming_soon');

-- Bước 3: THÊM 15 SUẤT CHIẾU MỚI (TỔNG CỘNG 25 SUẤT CHIẾU ĐỂ TEST PHÂN TRANG)
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

-- TRUNCATE TABLE `combos`; -- Hãy cẩn thận nếu bạn muốn giữ data cũ
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