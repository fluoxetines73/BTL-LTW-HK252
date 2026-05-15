-- Partial seed derived from seed.sql (safe to run on current schema)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

-- USERS
DELETE FROM `users`;
INSERT INTO `users` (`email`, `password`, `full_name`, `phone`, `role`, `points`, `status`) VALUES
('admin@cgv.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyen Van Admin', '0901234567', 'admin', 1000, 'active'),
('test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tran Thi Lan', '0912345678', 'member', 250, 'active'),
('test2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Le Van Minh', '0923456789', 'member', 180, 'active'),
('member@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pham Thi Huong', '0934567890', 'member', 420, 'active');

-- GENRES
DELETE FROM `genres`;
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

-- CINEMAS
DELETE FROM `cinemas`;
INSERT INTO `cinemas` (`name`, `slug`, `address`, `city`, `phone`, `description`, `status`) VALUES
('CGV Vincom Dong Khoi', 'cgv-vincom-dong-khoi', '72 Le Thanh Ton, Quan 1, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap chieu phim trung tam Quan 1 voi am thanh Dolby Atmos.', 'active'),
('CGV Aeon Binh Tan', 'cgv-aeon-binh-tan', 'Tang 3 AEON Mall Binh Tan, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap phim khu Tay Sai Gon, co phong chieu 4DX.', 'active');

-- SEAT TYPES
DELETE FROM `seat_types`;
INSERT INTO `seat_types` (`name`, `price_multiplier`, `color_code`, `col_span`) VALUES
('Standard', 1.00, '#808080', 1),
('VIP', 1.50, '#D4A843', 1),
('Couple', 2.00, '#E71A0F', 2),
('SweetBox', 2.50, '#FF1493', 2);

-- COMBOS + COMBO ITEMS
DELETE FROM `combos`;
INSERT INTO `combos` (`name`, `description`, `price`, `is_active`) VALUES
('Combo 1 Bap Nuoc', '1 Bap (M) + 1 Nuoc (M)', 85000.00, TRUE),
('Combo Couple', '1 Bap (L) + 2 Nuoc (M)', 130000.00, TRUE),
('CGV Combo Dac Biet', '2 Bap (L) + 2 Nuoc (L) + 1 Snack', 220000.00, TRUE),
('Combo Solo', '1 Bap (S) + 1 Nuoc (S)', 65000.00, TRUE),
('Combo Gia Dinh', '3 Bap (L) + 4 Nuoc (L) + 2 Snack', 380000.00, TRUE);

DELETE FROM `combo_items`;
INSERT INTO `combo_items` (`combo_id`, `item_name`, `quantity`) VALUES
(1, 'Bap Rang Bo (M)', 1), (1, 'Nuoc Ngot (M)', 1),
(2, 'Bap Rang Bo (L)', 1), (2, 'Nuoc Ngot (M)', 2),
(3, 'Bap Rang Bo (L)', 2), (3, 'Nuoc Ngot (L)', 2), (3, 'Snack Mix', 1),
(4, 'Bap Rang Bo (S)', 1), (4, 'Nuoc Ngot (S)', 1),
(5, 'Bap Rang Bo (L)', 3), (5, 'Nuoc Ngot (L)', 4), (5, 'Snack Mix', 2);

-- PROMOTIONS
DELETE FROM `promotions`;
INSERT INTO `promotions` (`code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `usage_limit`, `valid_from`, `valid_to`, `is_active`) VALUES
('WELCOME10', 'Chao mung thanh vien moi', 'Giam 10% cho lan dat ve dau tien', 'percent', 10.00, 50000.00, 30000.00, NULL, '2026-01-01', '2026-12-31', TRUE),
('MONDAY50', 'Thu Hai vui ve', 'Giam 50000 cho don tu 200000 vao thu Hai', 'fixed', 50000.00, 200000.00, NULL, 500, '2026-03-01', '2026-06-30', TRUE),
('VIP100', 'Uu dai VIP', 'Giam 100000 cho don tu 500000', 'fixed', 100000.00, 500000.00, NULL, 100, '2026-03-10', '2026-04-30', TRUE);

-- MOVIES + MOVIE GENRES
DELETE FROM `movies`;
INSERT INTO `movies` (`title`, `slug`, `description`, `director`, `cast`, `duration_min`, `release_date`, `end_date`, `age_rating`, `status`, `language`, `subtitle`, `country`) VALUES
('Mai', 'mai', 'Cau chuyen ve cuoc doi cua Mai.', 'Tran Thanh', 'Phuong Anh Dao, Tuan Tran', 131, '2026-02-10', NULL, 'C16', 'now_showing', 'Tieng Viet', 'Phu de Anh', 'Viet Nam'),
('Dune: Hanh Tinh Cat - Phan Hai', 'dune-hanh-tinh-cat-phan-hai', 'Paul Atreides cung Fremen chong lai the luc toi.', 'Denis Villeneuve', 'Timothee Chalamet, Zendaya', 166, '2026-03-01', NULL, 'C13', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Lat Mat 7: Mot Dieu Uoc', 'lat-mat-7-mot-dieu-uoc', 'Phan tiep theo cua series Lat Mat.', 'Ly Hai', 'Ly Hai, Dieu Nhi', 138, '2026-02-20', NULL, 'C16', 'now_showing', 'Tieng Viet', 'Phu de Anh', 'Viet Nam'),
('Kung Fu Panda 4', 'kung-fu-panda-4', 'Po doi mat thu thach moi.', 'Mike Mitchell', 'Jack Black, Awkwafina', 94, '2026-03-08', NULL, 'P', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Godzilla x Kong: The New Empire', 'godzilla-x-kong-the-new-empire', 'Hai huyen thoai hop luc.', 'Adam Wingard', 'Rebecca Hall, Dan Stevens', 115, '2026-03-29', NULL, 'C13', 'now_showing', 'Tieng Anh', 'Phu de Viet', 'My'),
('Joker: Folie a Deux', 'joker-folie-a-deux', 'Arthur Fleck va Harley Quinn.', 'Todd Phillips', 'Joaquin Phoenix, Lady Gaga', 138, '2026-10-04', NULL, 'C18', 'coming_soon', 'Tieng Anh', 'Phu de Viet', 'My');

DELETE FROM `movie_genres`;
INSERT INTO `movie_genres` (`movie_id`, `genre_id`) VALUES
(1, 7),
(2, 6), (2, 8), (2, 1),
(3, 1), (3, 3),
(4, 5), (4, 3), (4, 8), (4, 10),
(5, 1), (5, 6),
(6, 7), (6, 2);

-- PAGES
DELETE FROM `pages`;
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('Giới thiệu', 'gioi-thieu', '<section class="container mb-5"><div class="row align-items-center"><div class="col-md-6"><div class="about-content"><h2 class="mb-4" style="color: #E71A0F;">Tổ hợp Văn hóa - Cultureplex</h2><p><strong>CJ CGV</strong> là một trong top 5 cụm rạp chiếu phim lớn nhất toàn cầu và là nhà phát hành, cụm rạp chiếu phim lớn nhất Việt Nam. Chúng tôi tự hào là đơn vị tiên phong mang đến khái niệm độc đáo <strong>Cultureplex</strong> (Tổ hợp Văn hóa), nơi khán giả không chỉ đến để xem phim mà còn để trải nghiệm các dịch vụ giải trí, ẩm thực và mua sắm đẳng cấp.</p><p>Tại Việt Nam, CGV luôn nỗ lực xây dựng các chương trình Trách nhiệm xã hội như <em>"Điện ảnh cho mọi ngườii"</em>, <em>"Dự án phim ngắn CJ"</em> nhằm đồng hành và đóng góp cho sự phát triển chung của nền công nghiệp điện ảnh nước nhà.</p></div></div><div class="col-md-6"><div class="text-center p-4"><div class="about-img bg-light rounded shadow overflow-hidden" style="height: 420px;"><img src="<?= BASE_URL ?>public/images/about/about-6.png" alt="Giới thiệu CJ CGV Việt Nam" class="about-feature-image"></div></div></div></div></section>', 'published'),
('Điều khoản sử dụng', 'dieu-khoan-su-dung', '<h1 class="mb-4">Điều Khoản Sử Dụng</h1>...', 'published');

-- FAQS (already present in file, truncated here for brevity)
DELETE FROM `faqs`;
-- (skipping explicit faq rows in partial seed to avoid large insert in this partial file)

-- SETTINGS & CONTACTS
DELETE FROM `settings`;
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'CGV Booking Demo'),
('support_email', 'support@cgv.vn'),
('support_phone', '1900 6017');

DELETE FROM `contacts`;
INSERT INTO `contacts` (`name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
('Nguyen Van A', 'a@example.com', '0909999999', 'Ho tro dat ve', 'Toi can ho tro khi thanh toan.', 'new');

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
