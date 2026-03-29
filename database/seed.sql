-- CGV Cinemas Vietnam - Sample Seed Data
-- Password for all sample users: password

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

-- ============================================================
-- USERS
-- ============================================================
INSERT INTO `users` (`email`, `password`, `full_name`, `phone`, `role`, `points`, `status`) VALUES
('admin@cgv.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyen Van Admin', '0901234567', 'admin', 1000, 'active'),
('test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tran Thi Lan', '0912345678', 'member', 250, 'active'),
('test2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Le Van Minh', '0923456789', 'member', 180, 'active'),
('member@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pham Thi Huong', '0934567890', 'member', 420, 'active');

-- ============================================================
-- GENRES
-- ============================================================
INSERT INTO `genres` (`name`, `slug`) VALUES
('Hanh Dong', 'hanh-dong'),
('Kinh Di', 'kinh-di'),
('Hai', 'hai'),
('Lang Man', 'lang-man'),
('Hoat Hinh', 'hoat-hinh'),
('Khoa Hoc Vien Tuong', 'khoa-hoc-vien-tuong'),
('Tam Ly', 'tam-ly'),
('Phieu Luu', 'phieu-luu'),
('Tai Lieu', 'tai-lieu'),
('Gia Dinh', 'gia-dinh');

-- ============================================================
-- CINEMAS
-- ============================================================
INSERT INTO `cinemas` (`name`, `slug`, `address`, `city`, `phone`, `description`, `status`) VALUES
('CGV Vincom Dong Khoi', 'cgv-vincom-dong-khoi', '72 Le Thanh Ton, Quan 1, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap chieu phim trung tam Quan 1 voi am thanh Dolby Atmos.', 'active'),
('CGV Aeon Binh Tan', 'cgv-aeon-binh-tan', 'Tang 3 AEON Mall Binh Tan, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap phim khu Tay Sai Gon, co phong chieu 4DX.', 'active');

-- ============================================================
-- SEAT TYPES
-- ============================================================
INSERT INTO `seat_types` (`name`, `price_multiplier`, `color_code`, `col_span`) VALUES
('Standard', 1.00, '#808080', 1),
('VIP', 1.50, '#D4A843', 1),
('Couple', 2.00, '#E71A0F', 2),
('SweetBox', 2.50, '#FF1493', 2);

-- ============================================================
-- COMBOS + COMBO ITEMS
-- ============================================================
INSERT INTO `combos` (`name`, `description`, `price`, `is_active`) VALUES
('Combo 1 Bap Nuoc', '1 Bap (M) + 1 Nuoc (M)', 85000.00, TRUE),
('Combo Couple', '1 Bap (L) + 2 Nuoc (M)', 130000.00, TRUE),
('CGV Combo Dac Biet', '2 Bap (L) + 2 Nuoc (L) + 1 Snack', 220000.00, TRUE),
('Combo Solo', '1 Bap (S) + 1 Nuoc (S)', 65000.00, TRUE),
('Combo Gia Dinh', '3 Bap (L) + 4 Nuoc (L) + 2 Snack', 380000.00, TRUE);

INSERT INTO `combo_items` (`combo_id`, `item_name`, `quantity`) VALUES
(1, 'Bap Rang Bo (M)', 1), (1, 'Nuoc Ngot (M)', 1),
(2, 'Bap Rang Bo (L)', 1), (2, 'Nuoc Ngot (M)', 2),
(3, 'Bap Rang Bo (L)', 2), (3, 'Nuoc Ngot (L)', 2), (3, 'Snack Mix', 1),
(4, 'Bap Rang Bo (S)', 1), (4, 'Nuoc Ngot (S)', 1),
(5, 'Bap Rang Bo (L)', 3), (5, 'Nuoc Ngot (L)', 4), (5, 'Snack Mix', 2);

-- ============================================================
-- PROMOTIONS
-- ============================================================
INSERT INTO `promotions` (`code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `usage_limit`, `valid_from`, `valid_to`, `is_active`) VALUES
('WELCOME10', 'Chao mung thanh vien moi', 'Giam 10% cho lan dat ve dau tien', 'percent', 10.00, 50000.00, 30000.00, NULL, '2026-01-01', '2026-12-31', TRUE),
('MONDAY50', 'Thu Hai vui ve', 'Giam 50000 cho don tu 200000 vao thu Hai', 'fixed', 50000.00, 200000.00, NULL, 500, '2026-03-01', '2026-06-30', TRUE),
('VIP100', 'Uu dai VIP', 'Giam 100000 cho don tu 500000', 'fixed', 100000.00, 500000.00, NULL, 100, '2026-03-10', '2026-04-30', TRUE);

-- ============================================================
-- MOVIES + MOVIE GENRES
-- ============================================================
INSERT INTO `movies` (`title`, `slug`, `description`, `director`, `cast`, `duration_min`, `release_date`, `end_date`, `age_rating`, `status`, `language`, `subtitle`, `country`) VALUES
('Mai', 'mai', 'Cau chuyen ve cuoc doi cua Mai.', 'Tran Thanh', 'Phuong Anh Dao, Tuan Tran', 131, '2026-02-10', NULL, 'C16', 'now_showing', 'Tieng Viet', 'Phu de Anh', 'Viet Nam'),
('Dune: Hanh Tinh Cat - Phan Hai', 'dune-hanh-tinh-cat-phan-hai', 'Paul Atreides cung Fremen chong lai the luc toi.', 'Denis Villeneuve', 'Timothee Chalamet, Zendaya', 166, '2026-03-01', NULL, 'C13', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Lat Mat 7: Mot Dieu Uoc', 'lat-mat-7-mot-dieu-uoc', 'Phan tiep theo cua series Lat Mat.', 'Ly Hai', 'Ly Hai, Dieu Nhi', 138, '2026-02-20', NULL, 'C16', 'now_showing', 'Tieng Viet', 'Phu de Anh', 'Viet Nam'),
('Kung Fu Panda 4', 'kung-fu-panda-4', 'Po doi mat thu thach moi.', 'Mike Mitchell', 'Jack Black, Awkwafina', 94, '2026-03-08', NULL, 'P', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Godzilla x Kong: The New Empire', 'godzilla-x-kong-the-new-empire', 'Hai huyen thoai hop luc.', 'Adam Wingard', 'Rebecca Hall, Dan Stevens', 115, '2026-03-29', NULL, 'C13', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Joker: Folie a Deux', 'joker-folie-a-deux', 'Arthur Fleck va Harley Quinn.', 'Todd Phillips', 'Joaquin Phoenix, Lady Gaga', 138, '2026-10-04', NULL, 'C18', 'coming_soon', 'Tieng Anh', 'Phu de Viet', 'My');

INSERT INTO `movie_genres` (`movie_id`, `genre_id`) VALUES
(1, 7),
(2, 6), (2, 8), (2, 1),
(3, 1), (3, 3),
(4, 5), (4, 3), (4, 8), (4, 10),
(5, 1), (5, 6),
(6, 7), (6, 2);

-- ============================================================
-- ROOMS
-- ============================================================
INSERT INTO `rooms` (`cinema_id`, `name`, `total_rows`, `total_cols`, `screen_type`, `status`) VALUES
(1, 'Phong 1', 10, 12, '2D', 'active'),
(1, 'Phong 2', 12, 14, '3D', 'active'),
(1, 'Phong 3', 8, 10, '2D', 'active'),
(1, 'Phong 4', 11, 13, 'IMAX', 'active'),
(2, 'Phong 1', 10, 12, '2D', 'active'),
(2, 'Phong 2', 12, 14, '4DX', 'active'),
(2, 'Phong 3', 9, 11, '3D', 'active');

-- ============================================================
-- SEATS (sample seats for each room, enough for booking flows)
-- ============================================================
INSERT INTO `seats` (`room_id`, `seat_type_id`, `row_label`, `col_number`) VALUES
(1, 1, 'A', 1), (1, 1, 'A', 2), (1, 1, 'A', 3), (1, 1, 'A', 4),
(1, 2, 'E', 1), (1, 2, 'E', 2),
(1, 3, 'J', 1), (1, 3, 'J', 3),

(2, 1, 'A', 1), (2, 1, 'A', 2), (2, 1, 'A', 3), (2, 1, 'A', 4),
(2, 2, 'E', 1), (2, 2, 'E', 2),
(2, 4, 'L', 1), (2, 4, 'L', 3),

(3, 1, 'A', 1), (3, 1, 'A', 2),
(4, 2, 'E', 1), (4, 2, 'E', 2),
(5, 1, 'A', 1), (5, 1, 'A', 2),
(6, 1, 'A', 1), (6, 1, 'A', 2),
(7, 4, 'I', 1), (7, 4, 'I', 3);

-- ============================================================
-- SHOWTIMES
-- ============================================================
INSERT INTO `showtimes` (`movie_id`, `room_id`, `start_time`, `end_time`, `base_price`, `status`) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 10 HOUR, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 12 HOUR + INTERVAL 11 MINUTE, 75000.00, 'scheduled'),
(2, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 15 HOUR, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 17 HOUR + INTERVAL 46 MINUTE, 120000.00, 'scheduled'),
(3, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY) + INTERVAL 18 HOUR, DATE_ADD(CURDATE(), INTERVAL 2 DAY) + INTERVAL 20 HOUR + INTERVAL 18 MINUTE, 80000.00, 'scheduled'),
(4, 5, DATE_ADD(CURDATE(), INTERVAL 3 DAY) + INTERVAL 10 HOUR, DATE_ADD(CURDATE(), INTERVAL 3 DAY) + INTERVAL 11 HOUR + INTERVAL 34 MINUTE, 70000.00, 'scheduled'),
(5, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY) + INTERVAL 21 HOUR, DATE_ADD(CURDATE(), INTERVAL 3 DAY) + INTERVAL 22 HOUR + INTERVAL 55 MINUTE, 95000.00, 'scheduled'),
(1, 7, DATE_ADD(CURDATE(), INTERVAL 5 DAY) + INTERVAL 20 HOUR, DATE_ADD(CURDATE(), INTERVAL 5 DAY) + INTERVAL 22 HOUR + INTERVAL 11 MINUTE, 90000.00, 'scheduled');

-- ============================================================
-- BOOKINGS + TICKETS + BOOKING COMBOS
-- ============================================================
INSERT INTO `bookings` (`booking_code`, `user_id`, `showtime_id`, `total_amount`, `discount_amount`, `final_amount`, `promotion_id`, `payment_method`, `payment_status`, `status`) VALUES
('CGV20260314001', 2, 1, 280000.00, 28000.00, 252000.00, 1, 'vnpay', 'paid', 'confirmed'),
('CGV20260314002', 3, 2, 360000.00, 0.00, 360000.00, NULL, 'cash', 'paid', 'confirmed'),
('CGV20260314003', 4, 3, 540000.00, 0.00, 540000.00, NULL, 'vnpay', 'paid', 'confirmed'),
('CGV20260314004', 2, 4, 205000.00, 0.00, 205000.00, NULL, 'vnpay', 'pending', 'pending');

INSERT INTO `tickets` (`booking_id`, `seat_id`, `price`) VALUES
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 1), 75000.00),
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 2), 75000.00),
(2, (SELECT id FROM seats WHERE room_id = 4 AND row_label = 'E' AND col_number = 1), 180000.00),
(2, (SELECT id FROM seats WHERE room_id = 4 AND row_label = 'E' AND col_number = 2), 180000.00),
(3, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'J' AND col_number = 1), 160000.00),
(4, (SELECT id FROM seats WHERE room_id = 5 AND row_label = 'A' AND col_number = 1), 70000.00),
(4, (SELECT id FROM seats WHERE room_id = 5 AND row_label = 'A' AND col_number = 2), 70000.00);

INSERT INTO `booking_combos` (`booking_id`, `combo_id`, `quantity`, `price`) VALUES
(1, 2, 1, 130000.00),
(3, 5, 1, 380000.00),
(4, 1, 1, 85000.00);

-- ============================================================
-- SEAT RESERVATIONS
-- ============================================================
INSERT INTO `seat_reservations` (`showtime_id`, `seat_id`, `user_id`, `status`, `locked_until`) VALUES
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 3), 2, 'locked', DATE_ADD(NOW(), INTERVAL 10 MINUTE)),
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 4), 2, 'booked', NULL);

-- ============================================================
-- REVIEWS
-- ============================================================
INSERT INTO `reviews` (`user_id`, `movie_id`, `rating`, `comment`, `status`) VALUES
(2, 1, 5, 'Phim cam dong, dien xuat tot.', 'approved'),
(3, 2, 4, 'Hinh anh dep, noi dung hap dan.', 'approved'),
(4, 4, 4, 'Phu hop cho gia dinh.', 'approved');

-- ============================================================
-- NEWS
-- ============================================================
INSERT INTO `news` (`title`, `slug`, `content`, `category`, `author_id`, `status`, `published_at`) VALUES
('CGV khai truong uu dai mua he', 'cgv-khai-truong-uu-dai-mua-he', 'Thong tin chuong trinh uu dai mua he tai he thong rap CGV.', 'khuyen-mai', 1, 'published', NOW()),
('Lich phim moi thang 4', 'lich-phim-moi-thang-4', 'Cap nhat danh sach phim moi trong thang 4.', 'tin-tuc', 1, 'published', NOW());

-- ============================================================
-- STATIC CONTENT TABLES
-- ============================================================
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('Gioi thieu', 'gioi-thieu', 'Noi dung gioi thieu he thong dat ve.', 'published'),
('Dieu khoan su dung', 'dieu-khoan', 'Noi dung dieu khoan su dung dich vu.', 'published');

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'CGV Booking Demo'),
('support_email', 'support@cgv.vn'),
('support_phone', '1900 6017');

INSERT INTO `contacts` (`name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
('Nguyen Van A', 'a@example.com', '0909999999', 'Ho tro dat ve', 'Toi can ho tro khi thanh toan.', 'new');

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
