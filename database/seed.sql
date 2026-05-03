-- CGV Cinemas Vietnam - Sample Seed Data
-- Password for all sample users: password

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

-- ============================================================
-- USERS
-- ============================================================
DELETE FROM `users`;
INSERT INTO `users` (`email`, `password`, `full_name`, `phone`, `role`, `points`, `status`) VALUES
('admin@cgv.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyen Van Admin', '0901234567', 'admin', 1000, 'active'),
('test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tran Thi Lan', '0912345678', 'member', 250, 'active'),
('test2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Le Van Minh', '0923456789', 'member', 180, 'active'),
('member@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pham Thi Huong', '0934567890', 'member', 420, 'active');

-- ============================================================
-- GENRES
-- ============================================================
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

-- ============================================================
-- CINEMAS
-- ============================================================
DELETE FROM `cinemas`;
INSERT INTO `cinemas` (`name`, `slug`, `address`, `city`, `phone`, `description`, `status`) VALUES
('CGV Vincom Dong Khoi', 'cgv-vincom-dong-khoi', '72 Le Thanh Ton, Quan 1, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap chieu phim trung tam Quan 1 voi am thanh Dolby Atmos.', 'active'),
('CGV Aeon Binh Tan', 'cgv-aeon-binh-tan', 'Tang 3 AEON Mall Binh Tan, TP.HCM', 'Ho Chi Minh', '1900 6017', 'Rap phim khu Tay Sai Gon, co phong chieu 4DX.', 'active');

-- ============================================================
-- SEAT TYPES
-- ============================================================
DELETE FROM `seat_types`;
INSERT INTO `seat_types` (`name`, `price_multiplier`, `color_code`, `col_span`) VALUES
('Standard', 1.00, '#808080', 1),
('VIP', 1.50, '#D4A843', 1),
('Couple', 2.00, '#E71A0F', 2),
('SweetBox', 2.50, '#FF1493', 2);

-- ============================================================
-- COMBOS + COMBO ITEMS
-- ============================================================
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

-- ============================================================
-- PROMOTIONS
-- ============================================================
DELETE FROM `promotions`;
INSERT INTO `promotions` (`code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `usage_limit`, `valid_from`, `valid_to`, `is_active`) VALUES
('WELCOME10', 'Chao mung thanh vien moi', 'Giam 10% cho lan dat ve dau tien', 'percent', 10.00, 50000.00, 30000.00, NULL, '2026-01-01', '2026-12-31', TRUE),
('MONDAY50', 'Thu Hai vui ve', 'Giam 50000 cho don tu 200000 vao thu Hai', 'fixed', 50000.00, 200000.00, NULL, 500, '2026-03-01', '2026-06-30', TRUE),
('VIP100', 'Uu dai VIP', 'Giam 100000 cho don tu 500000', 'fixed', 100000.00, 500000.00, NULL, 100, '2026-03-10', '2026-04-30', TRUE);

-- ============================================================
-- MOVIES + MOVIE GENRES
-- ============================================================
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

-- ============================================================
-- ROOMS
-- ============================================================
DELETE FROM `rooms`;
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
DELETE FROM `seats`;
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
DELETE FROM `showtimes`;
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
DELETE FROM `bookings`;
INSERT INTO `bookings` (`booking_code`, `user_id`, `showtime_id`, `total_amount`, `discount_amount`, `final_amount`, `promotion_id`, `payment_method`, `payment_status`, `status`) VALUES
('CGV20260314001', 2, 1, 280000.00, 28000.00, 252000.00, 1, 'vnpay', 'paid', 'confirmed'),
('CGV20260314002', 3, 2, 360000.00, 0.00, 360000.00, NULL, 'cash', 'paid', 'confirmed'),
('CGV20260314003', 4, 3, 540000.00, 0.00, 540000.00, NULL, 'vnpay', 'paid', 'confirmed'),
('CGV20260314004', 2, 4, 205000.00, 0.00, 205000.00, NULL, 'vnpay', 'pending', 'pending');

DELETE FROM `tickets`;
INSERT INTO `tickets` (`booking_id`, `seat_id`, `price`) VALUES
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 1), 75000.00),
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 2), 75000.00),
(2, (SELECT id FROM seats WHERE room_id = 4 AND row_label = 'E' AND col_number = 1), 180000.00),
(2, (SELECT id FROM seats WHERE room_id = 4 AND row_label = 'E' AND col_number = 2), 180000.00),
(3, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'J' AND col_number = 1), 160000.00),
(4, (SELECT id FROM seats WHERE room_id = 5 AND row_label = 'A' AND col_number = 1), 70000.00),
(4, (SELECT id FROM seats WHERE room_id = 5 AND row_label = 'A' AND col_number = 2), 70000.00);

DELETE FROM `booking_combos`;
INSERT INTO `booking_combos` (`booking_id`, `combo_id`, `quantity`, `price`) VALUES
(1, 2, 1, 130000.00),
(3, 5, 1, 380000.00),
(4, 1, 1, 85000.00);

-- ============================================================
-- SEAT RESERVATIONS
-- ============================================================
DELETE FROM `seat_reservations`;
INSERT INTO `seat_reservations` (`showtime_id`, `seat_id`, `user_id`, `status`, `locked_until`) VALUES
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 3), 2, 'locked', DATE_ADD(NOW(), INTERVAL 10 MINUTE)),
(1, (SELECT id FROM seats WHERE room_id = 1 AND row_label = 'A' AND col_number = 4), 2, 'booked', NULL);

-- ============================================================
-- REVIEWS
-- ============================================================
DELETE FROM `reviews`;
INSERT INTO `reviews` (`user_id`, `movie_id`, `rating`, `comment`, `status`) VALUES
(2, 1, 5, 'Phim cam dong, dien xuat tot.', 'approved'),
(3, 2, 4, 'Hinh anh dep, noi dung hap dan.', 'approved'),
(4, 4, 4, 'Phu hop cho gia dinh.', 'approved');

-- ============================================================
-- NEWS
-- ============================================================
DELETE FROM `news`;
INSERT INTO `news` (`title`, `slug`, `content`, `category`, `author_id`, `status`, `published_at`) VALUES
('CGV khai truong uu dai mua he', 'cgv-khai-truong-uu-dai-mua-he', 'Thong tin chuong trinh uu dai mua he tai he thong rap CGV.', 'khuyen-mai', 1, 'published', NOW()),
('Lịch phim mới tháng 4', 'lich-phim-moi-thang-4', 'Cập nhật danh sách phim mới trong tháng 4.', 'tin-tuc', 1, 'published', NOW());

-- ============================================================
-- STATIC CONTENT TABLES
-- ============================================================
DELETE FROM `pages`;
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('Giới thiệu', 'gioi-thieu', '<section class="container mb-5"><div class="row align-items-center"><div class="col-md-6"><div class="about-content"><h2 class="mb-4" style="color: #E71A0F;">Tổ hợp Văn hóa - Cultureplex</h2><p><strong>CJ CGV</strong> là một trong top 5 cụm rạp chiếu phim lớn nhất toàn cầu và là nhà phát hành, cụm rạp chiếu phim lớn nhất Việt Nam. Chúng tôi tự hào là đơn vị tiên phong mang đến khái niệm độc đáo <strong>Cultureplex</strong> (Tổ hợp Văn hóa), nơi khán giả không chỉ đến để xem phim mà còn để trải nghiệm các dịch vụ giải trí, ẩm thực và mua sắm đẳng cấp.</p><p>Tại Việt Nam, CGV luôn nỗ lực xây dựng các chương trình Trách nhiệm xã hội như <em>"Điện ảnh cho mọi ngườii"</em>, <em>"Dự án phim ngắn CJ"</em> nhằm đồng hành và đóng góp cho sự phát triển chung của nền công nghiệp điện ảnh nước nhà.</p></div></div><div class="col-md-6"><div class="text-center p-4"><div class="about-img bg-light rounded shadow overflow-hidden" style="height: 420px;"><img src="<?= BASE_URL ?>public/images/about/about-6.png" alt="Giới thiệu CJ CGV Việt Nam" class="about-feature-image"></div></div></div></div></section>', 'published'),
('Điều khoản sử dụng', 'dieu-khoan-su-dung', '<h1 class="mb-4">Điều Khoản Sử Dụng</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Chấp Nhận Điều Khoản</h5><p>Bằng cách sử dụng website hoặc ứng dụng CGV Cinema, bạn đồng ý tuân thủ các Điều Khoản Sử Dụng này. Nếu bạn không đồng ý, vui lòng không sử dụng dịch vụ của chúng tôi. CGV Cinema có quyền thay đổi các điều khoản này bất kỳ lúc nào.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Tài Khoản Người Dùng</h5><p>Khi tạo tài khoản, bạn phải cung cấp thông tin chính xác và cập nhật. Bạn chịu trách nhiệm bảo mật mật khẩu của mình và tất cả hoạt động xảy ra dưới tài khoản của bạn. Bạn phải thông báo cho chúng tôi ngay lập tức nếu phát hiện truy cập trái phép.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Vé và Đặt Chỗ</h5><p>Vé được cung cấp trên cơ sở "còn sẵn có". Giá vé có thể thay đổi mà không cần thông báo trước. Vé không chuyển nhượng được và chỉ có giá trị cho suất chiếu được chỉ định. Bạn không được phép bán lại vé hoặc sử dụng vé cho mục đích thương mại.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Chính Sách Hoàn Tiền</h5><p>Vé không hoàn tiền sau khi được mua. Tuy nhiên, bạn có thể đổi vé sang một suất chiếu khác miễn phí nếu còn chỗ trống và yêu cầu được thực hiện trước ít nhất 1 giờ trước suất chiếu gốc.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">5. Thanh Toán</h5><p>Tất cả thanh toán phải được thực hiện trước khi vé được phát hành. Chúng tôi chấp nhận các thẻ tín dụng chính, chuyển khoản ngân hàng, và các phương thức thanh toán điện tử khác. Bạn chịu trách nhiệm về mọi khoản phí ngân hàng liên quan.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">6. Hành Vi của Khách Hàng</h5><p>Bạn đồng ý không: (1) quay phim hoặc chụp ảnh bộ phim; (2) mang thức ăn từ bên ngoài vào rạp; (3) gây phiền hà hoặc làm phiền khách hàng khác; (4) sử dụng điện thoại trong phòng chiếu; (5) vi phạm các quy định khác của rạp.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">7. Trách Nhiệm Hạn Chế</h5><p>CGV Cinema KHÔNG chịu trách nhiệm về: (1) những sự cố kỹ thuật hoặc mất điện; (2) thất thoát hoặc hư hỏng tài sản cá nhân; (3) thương tích cá nhân; (4) những mất mát gián tiếp hoặc không dự tính. Bạn sử dụng dịch vụ của chúng tôi với rủi ro của riêng bạn.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">8. Quyền Bản Quyền</h5><p>Tất cả nội dung trên website CGV Cinema, bao gồm văn bản, hình ảnh, video, và logo, được bảo vệ bởi quyền tác giả. Bạn không được phép sao chép, phân phối, hoặc sửa đổi nội dung này mà không có sự cho phép bằng văn bản.</p></div></div><p class="text-muted mt-4">Những Điều Khoản Sử Dụng này có hiệu lực từ tháng 1 năm 2025. Chúng tôi sẽ thông báo cho bạn về bất kỳ thay đổi nào thông qua trang web hoặc email.</p>', 'published'),
('Chính sách bảo mật', 'chinh-sach-bao-mat', '<h1 class="mb-4">Chính Sách Bảo Mật</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Thu Thập Thông Tin</h5><p>CGV Cinema thu thập thông tin cá nhân của bạn khi bạn đăng ký tài khoản, mua vé, hoặc liên hệ với chúng tôi. Thông tin này bao gồm: họ tên, địa chỉ email, số điện thoại, địa chỉ giao hàng, và thông tin thanh toán (được mã hóa an toàn).</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Sử Dụng Thông Tin</h5><p>Chúng tôi sử dụng thông tin của bạn để: (1) xử lý đơn hàng và thanh toán; (2) gửi xác nhận vé và thông báo quan trọng; (3) cung cấp hỗ trợ khách hàng; (4) cải thiện dịch vụ dựa trên phản hồi; (5) gửi tin khuyến mãi và ưu đãi (nếu bạn chấp thuận).</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Bảo Vệ Dữ Liệu</h5><p>Thông tin cá nhân của bạn được lưu trữ trên máy chủ được bảo vệ bằng mã hóa SSL 256-bit. Chúng tôi tuân thủ các tiêu chuẩn bảo mật quốc tế để đảm bảo thông tin của bạn an toàn khỏi truy cập trái phép.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Chia Sẻ Thông Tin</h5><p>Chúng tôi KHÔNG bán hoặc chia sẻ thông tin cá nhân của bạn với bên thứ ba mà không có sự đồng ý của bạn, ngoại trừ những nhà cung cấp dịch vụ cần thiết để xử lý đơn hàng (ví dụ: cổng thanh toán, công ty giao hàng).</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">5. Quyền Của Bạn</h5><p>Bạn có quyền yêu cầu xem, chỉnh sửa, hoặc xóa thông tin cá nhân của mình bất kỳ lúc nào. Để thực hiện điều này, vui lòng liên hệ với bộ phận Hỗ Trợ Khách Hàng của chúng tôi.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">6. Cookie</h5><p>Website của chúng tôi sử dụng cookie để cải thiện trải nghiệm của bạn. Cookie giúp chúng tôi ghi nhớ sở thích của bạn và cung cấp nội dung được cá nhân hóa. Bạn có thể tắt cookie trong cài đặt trình duyệt của mình.</p></div></div><p class="text-muted mt-4">Chính sách bảo mật này có hiệu lực từ tháng 1 năm 2025 và có thể cập nhật bất kỳ lúc nào. Chúng tôi sẽ thông báo cho bạn về bất kỳ thay đổi nào thông qua email.</p>', 'published'),
('Quy định rạp chiếu', 'quy-dinh-rap-chieu', '<h1 class="mb-4">Quy Định Rạp Chiếu</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Giờ Mở Cửa và Nhập Vào</h5><p>Rạp mở cửa 30 phút trước suất chiếu đầu tiên. Quầy vé và cửa hàng bắp nước đóng cửa 15 phút sau khi suất chiếu bắt đầu. Khách hàng chậm đến sẽ không được hoàn tiền hoặc đổi vé.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Trang Phục và Vệ Sinh</h5><p>Khách hàng phải mặc trang phục thích hợp (áo sơ mi, quần dài hoặc tương đương). Giày phải được mang. Chúng tôi bảo lưu quyền từ chối dịch vụ cho bất kỳ ai không tuân thủ quy định vệ sinh hoặc hành vi.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Chính Sách Điện Thoại và Thiết Bị</h5><p>Điện thoại phải để ở chế độ im lặng trong phòng chiếu. Chụp ảnh, quay video hoặc ghi âm bộ phim là HOÀN TOÀN BỊ CẤM. Vi phạm sẽ bị xử lý theo luật pháp. Sử dụng máy ảnh hành động (GoPro) hoặc các thiết bị ghi hình tương tự cũng bị cấm.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Chính Sách Thức Ăn và Đồ Uống</h5><p>Khách hàng chỉ được mua thức ăn và đồ uống tại quầy bắp nước của rạp. Mang thức ăn từ bên ngoài vào rạp là HOÀN TOÀN BỊ CẤM (nước uống không có khí được phép). Các loại rượu hoặc thức uống có cồn không được phép.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">5. Trẻ Em và Người Giám Hộ</h5><p>Trẻ em dưới 3 tuổi không cần mua vé nếu ngồi chung ghế với phụ huynh. Trẻ em từ 3-12 tuổi phải được giám sát liên tục bởi phụ huynh hoặc người chăm sóc. Những phim được xếp hạng K (dưới 13) không được cho phép bọn trẻ dưới tuổi xem.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">6. Hành Vi Gây Phiền Hà</h5><p>Nói chuyện to, cười ồn ào, hoặc những hành vi khác gây phiền hà cho khán giả khác sẽ bị nhân viên rạp nhắc nhở. Hành vi liên tục sẽ dẫn đến bị yêu cầu rời khỏi rạp mà không hoàn tiền. Bạo lực, tục tĩu, hoặc hành vi công cộng dâm ô sẽ bị báo cảnh sát.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">7. Tài Sản Cá Nhân</h5><p>CGV Cinema KHÔNG chịu trách nhiệm về những mất mát, thất thoát, hoặc hư hỏng tài sản cá nhân. Vui lòng giữ gìn tài sản của bạn. Khách hàng lưu lại vé hoặc tài sản khác sẽ được lưu giữ tại quầy tiếp tân trong vòng 30 ngày rồi được xử lý.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">8. Thương Tích và Sự Cố</h5><p>Khách hàng chấp nhận toàn bộ rủi ro về thương tích hoặc mất mát tài sản. CGV Cinema KHÔNG chịu trách nhiệm về: sự cố cấu trúc, sự cố điện, tai nạn, hoặc bất kỳ sự cố không lường trước nào khác.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">9. Hỗ Trợ Khách Hàng Khuyết Tật</h5><p>CGV Cinema cung cấp hỗ trợ cho khách hàng khuyết tật, bao gồm khu vực ghế xe lăn, dịch vụ âm thanh phụ, và nhân viên hỗ trợ. Vui lòng liên hệ trước ít nhất 24 giờ để sắp xếp hỗ trợ cần thiết.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">10. Quyền Từ Chối Dịch Vụ</h5><p>CGV Cinema bảo lưu quyền từ chối dịch vụ cho bất kỳ khách hàng nào không tuân thủ quy định này hoặc gây phiền hà cho khách hàng khác, nhân viên, hoặc tài sản của rạp. Vi phạm có thể dẫn đến bị cấm vào rạp vĩnh viễn.</p></div></div><p class="text-muted mt-4">Những quy định này có hiệu lực từ tháng 1 năm 2025 và có thể được cập nhật bất kỳ lúc nào. Chúng tôi bảo lưu quyền giải thích cuối cùng về tất cả quy định.</p>', 'published');

-- ============================================================
-- FAQS
-- ============================================================
DELETE FROM `faqs`;
INSERT INTO `faqs` (`question`, `answer`, `category`, `sort_order`, `status`) VALUES
-- Ve & Dat cho
('Làm sao để đặt vé xem phim trực tuyến?', 'Bạn có thể truy cập website hoặc ứng dụng CGV Cinema, chọn phim, giờ chiếu, ghế ngồi và thanh toán. Vé điện tử sẽ được gửi qua email.', 'Vé & Đặt chỗ', 1, 'active'),
('Vé có những loại nào?', 'CGV Cinema cung cấp vé cho khán giả P, K (dưới 13), T13 (từ 13+), T16 (từ 16+), T18 (từ 18+). Ngoài ra, có vé VIP Suite cho trải nghiệm cao cấp.', 'Vé & Đặt chỗ', 2, 'active'),
('Có chiết khấu cho nhóm đoàn không?', 'Có. Đặt vé từ 10 vé trở lên, khách hàng được giảm giá 15%. Liên hệ bộ phận bán vé nhóm tại các rạp để được tư vấn chi tiết.', 'Vé & Đặt chỗ', 3, 'active'),
('Tôi đã mua vé nhưng muốn đổi giờ chiếu, có được không?', 'Có thể đổi vé miễn phí nếu còn ghế trống ở suất chiếu mong muốn. Yêu cầu phải được thực hiện trước 1 giờ chiếu.', 'Vé & Đặt chỗ', 4, 'active'),
('Có chính sách hoàn tiền vé không?', 'Vé không hoàn tiền nhưng có thể đổi sang suất chiếu khác miễn phí. Trường hợp đặc biệt liên hệ với bộ phận chăm sóc khách hàng.', 'Vé & Đặt chỗ', 5, 'active'),
-- Thanh vien & Rewards
('Chương trình thành viên của CGV Cinema hoạt động như thế nào?', 'Thành viên nhận điểm thưởng khi mua vé, bắp nước, combo. Tích lũy điểm để đổi vé miễn phí, đồ ăn hoặc các ưu đãi đặc biệt.', 'Thành viên & Rewards', 1, 'active'),
('Cách đăng ký thành viên?', 'Đăng ký miễn phí trên website, ứng dụng, hoặc tại quầy vé các rạp. Cung cấp email, số điện thoại, và tên để tạo tài khoản.', 'Thành viên & Rewards', 2, 'active'),
('Thành viên được ưu đãi gì?', 'Ưu đãi bao gồm: giảm giá vé, ưu tiên mua vé sớm, khuyến mãi cho bắp nước, sự kiện VIP dành riêng cho thành viên VIP.', 'Thành viên & Rewards', 3, 'active'),
('Điểm thưởng có hạn sử dụng không?', 'Điểm có hiệu lực 1 năm kể từ ngày tích lũy. Khuyến khích sử dụng đều đặn để không bị mất điểm.', 'Thành viên & Rewards', 4, 'active'),
-- Thong tin Rap
('CGV Cinema có những công nghệ gì?', 'CGV Cinema sở hữu công nghệ 4K Laser, Dolby Atmos, 4DX (ghế rung động với hiệu ứng), ScreenX (màn hình xung quanh 270 độ). Không phải tất cả phòng chiếu đều có công nghệ này.', 'Thông tin Rạp', 1, 'active'),
('Các rạp CGV Cinema nằm ở những đâu?', 'CGV Cinema có 8 rạp tại 4 thành phố lớn: Hà Nội, TP. Hồ Chí Minh, Hải Phòng, Đà Nẵng. Chi tiết địa chỉ xem trên website.', 'Thông tin Rạp', 2, 'active'),
('Rạp có phòng chờ tiện nghi không?', 'Tất cả rạp CGV Cinema đều có phòng chờ thoải mái với WiFi miễn phí, bàn ghế, nước uống. VIP Suite có lounge riêng với tiện nghi cao cấp.', 'Thông tin Rạp', 3, 'active'),
('Có hỗ trợ cho khách hàng khuyết tật không?', 'Có. CGV Cinema cung cấp khu vực chuyên dành cho người khuyết tật, nhân viên hỗ trợ, và các tiện nghi phù hợp. Vui lòng thông báo trước khi mua vé.', 'Thông tin Rạp', 4, 'active'),
-- Chinh sach & Quy dinh
('Có thể mang thức ăn từ bên ngoài vào rạp không?', 'Không. Theo chính sách, khách hàng phải mua bắp nước tại cửa hàng trong rạp. Tuy nhiên, nước uống không có khí được phép mang vào.', 'Chính sách & Quy định', 1, 'active'),
('Trẻ em dưới 3 tuổi có cần mua vé không?', 'Trẻ em dưới 3 tuổi không cần mua vé riêng nếu ngồi chung ghế với phụ huynh. Từ 3 tuổi trở lên cần mua vé.', 'Chính sách & Quy định', 2, 'active'),
('Rạp có quy định về sử dụng điện thoại không?', 'Yêu cầu tắt điện thoại hoặc chế độ im lặng trong phòng chiếu. Chụp ảnh hoặc quay phim không được phép để bảo vệ bản quyền.', 'Chính sách & Quy định', 3, 'active'),
('Có thể hủy chương trình tổ chức sinh nhật tại rạp không?', 'Có thể hủy nếu thông báo 7 ngày trước. Hủy muộn hơn sẽ bị tính phí huỷ bỏ 50% chi phí.', 'Chính sách & Quy định', 4, 'active'),
('Rạp có cho phép quay video bộ phim để chia sẻ không?', 'Không. Quay phim bộ đồ hoặc bất kỳ nội dung nào từ rạp chiếu là vi phạm bản quyền và sẽ bị xử lý theo pháp luật.', 'Chính sách & Quy định', 5, 'active'),
-- Bap & Do an
('Menu bắp nước của CGV Cinema có gì?', 'Menu bao gồm: bắp rang bơ/mặn, nước ngọt, trà/cà phê, cam ép, kem, bánh ngọt, và các combo ưu đãi giá tốt.', 'Bắp & Đồ ăn', 1, 'active'),
('Combo bắp nước rẻ hơn bao nhiêu so với mua lẻ?', 'Combo tiết kiệm khoảng 20-30% so với mua từng loại. Ví dụ: combo bắp + nước lớn có giá ưu đãi từ 79.000đ.', 'Bắp & Đồ ăn', 2, 'active'),
('Có thể nâng cỡ bắp/nước không?', 'Có. Khách hàng có thể nâng cỡ với giá thêm cho từng loại. Chi tiết giá xem tại quầy bắp hoặc website.', 'Bắp & Đồ ăn', 3, 'active'),
('Bắp có các hương vị nào?', 'Bắp ring bơ (butter), mặn (salt), phô mai (cheese), caramel, và một số hương vị đặc biệt theo mùa.', 'Bắp & Đồ ăn', 4, 'active'),
('Người ăn chay có thể chọn đồ gì?', 'Bắp mặn không dùng bơ, nước ngọt, nước ép trái cây, cà phê đen, trà. Vui lòng hỏi nhân viên quầy để chắc chắn.', 'Bắp & Đồ ăn', 5, 'active'),
-- Cong nghe & Dinh dang
('4DX là gì?', '4DX là công nghệ phòng chiếu với ghế chuyển động, phun nước, khí, mưa, và các hiệu ứng khác để tăng cảm giác thực tế khi xem phim.', 'Công nghệ & Định dạng', 1, 'active'),
('Dolby Atmos mang lại trải nghiệm gì khác?', 'Dolby Atmos cung cấp hệ thống âm thanh 3D đắm đuối với loa phía trên tạo cảm giác âm thanh bao quanh tuyệt vờii.', 'Công nghệ & Định dạng', 2, 'active'),
('ScreenX là gì và khác gì so với phòng chiếu thường?', 'ScreenX có màn hình xung quanh 270 độ (thay vì 170 độ). Bạn sẽ nhìn thấy hình ảnh trên tường hai bên tạo cảm giác bao quanh hoàn toàn.', 'Công nghệ & Định dạng', 3, 'active'),
('4K Laser có ưu điểm gì?', '4K Laser cho độ sáng, độ tương phản, và sắc độ màu tuyệt vờii hơn so với chiếu phim thường, mang lại hình ảnh sắc nét và chân thực.', 'Công nghệ & Định dạng', 4, 'active'),
-- Su kien & Chuong trinh dac biet
('CGV Cinema có tổ chức sự kiện gì?', 'CGV Cinema tổ chức: buổi chiếu phim độc quyền, gặp gỡ diễn viên, buổi tiệc phim, sự kiện cộng đồng, và các chương trình khuyến mãi theo mùa.', 'Sự kiện & Chương trình đặc biệt', 1, 'active'),
('Có thể thuê rạp tổ chức sự kiện riêng không?', 'Có. CGV Cinema cho phép thuê phòng chiếu để tổ chức sinh nhật, team building, họp công ty, hoặc sự kiện riêng. Liên hệ 1900xxxx để tư vấn.', 'Sự kiện & Chương trình đặc biệt', 2, 'active'),
('Gói sinh nhật tại CGV Cinema bao gồm những gì?', 'Gói bao gồm: vé xem phim, phòng chờ riêng, combo bắp nước, bánh sinh nhật, và dịch vụ phục vụ chuyên nghiệp trong 2-3 giờ.', 'Sự kiện & Chương trình đặc biệt', 3, 'active');

DELETE FROM `settings`;
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'CGV Booking Demo'),
('support_email', 'support@cgv.vn'),
('support_phone', '1900 6017');

DELETE FROM `contacts`;
INSERT INTO `contacts` (`name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
('Nguyen Van A', 'a@example.com', '0909999999', 'Ho tro dat ve', 'Toi can ho tro khi thanh toan.', 'new');

-- ============================================================
-- ABOUT PAGE STRUCTURED CONTENT
-- ============================================================
DELETE FROM `about_leadership`;
DELETE FROM `about_core_values`;
DELETE FROM `about_statistics`;
DELETE FROM `about_timeline_items`;
DELETE FROM `about_page_settings`;

-- About page main settings
INSERT INTO `about_page_settings` (
    `hero_title`, `hero_kicker`, 
    `intro_heading`, `intro_paragraph_1`, `intro_paragraph_2`, `intro_image`,
    `vision_title`, `vision_icon`, `vision_content`,
    `mission_title`, `mission_icon`, `mission_content`
) VALUES (
    'Về CJ CGV Việt Nam',
    'Thông tin & hỗ trợ',
    'Tổ hợp Văn hóa - Cultureplex',
    '<strong>CJ CGV</strong> là một trong top 5 cụm rạp chiếu phim lớn nhất toàn cầu và là nhà phát hành, cụm rạp chiếu phim lớn nhất Việt Nam. Chúng tôi tự hào là đơn vị tiên phong mang đến khái niệm độc đáo <strong>Cultureplex</strong> (Tổ hợp Văn hóa), nơi khán giả không chỉ đến để xem phim mà còn để trải nghiệm các dịch vụ giải trí, ẩm thực và mua sắm đẳng cấp.',
    'Tại Việt Nam, CGV luôn nỗ lực xây dựng các chương trình Trách nhiệm xã hội như <em>"Điện ảnh cho mọi ngườii"</em>, <em>"Dự án phim ngắn CJ"</em> nhằm đồng hành và đóng góp cho sự phát triển chung của nền công nghiệp điện ảnh nước nhà.',
    'public/images/about/about-6.png',
    'Tầm nhìn',
    'fa-solid fa-globe',
    'Trở thành công ty giải trí và truyền thông phong cách sống toàn cầu (Global Lifestyle Entertainment Company). CGV hướng tới việc tạo ra những giá trị vượt ra ngoài những giới hạn của rạp chiếu phim truyền thống.',
    'Sứ mệnh',
    'fa-solid fa-bullseye',
    'Tiên phong mang đến những công nghệ điện ảnh hiện đại nhất thế giới. Không ngừng hỗ trợ các nhà làm phim trẻ và đóng góp vào sự phát triển mạnh mẽ của nền điện ảnh Việt Nam.'
);

-- Timeline items
INSERT INTO `about_timeline_items` (`year_label`, `content`, `sort_order`) VALUES
('2011', 'CJ Group (Hàn Quốc) chính thức tiếp quản hệ thống rạp MegaStar tại Việt Nam, đặt nền móng đầu tiên cho hành trình nâng tầm trải nghiệm điện ảnh tiêu chuẩn quốc tế.', 1),
('2014', 'Chính thức chuyển đổi thương hiệu thành <strong>CGV Cinemas</strong>. Ra mắt công nghệ chiếu phim 4DX và IMAX lần đầu tiên tại Việt Nam, tạo nên cơn sốt phòng vé.', 2),
('2017 - 2019', 'Giới thiệu công nghệ ScreenX – phòng chiếu phim đa diện đầu tiên, và Starium Laser. Liên tục ra mắt các phòng chiếu cao cấp như Gold Class, L\'Amour, Premium.', 3),
('Hiện nay', 'Khẳng định vị thế nhà phát hành và cụm rạp số 1 Việt Nam với mạng lưới phủ sóng khắp các tỉnh thành, trở thành điểm đến văn hóa quen thuộc của hàng triệu khán giả.', 4);

-- Statistics
INSERT INTO `about_statistics` (`number_display`, `label`, `sort_order`) VALUES
('80+', 'Cụm rạp toàn quốc', 1),
('470+', 'Phòng chiếu hiện đại', 2),
('30+', 'Tỉnh / Thành phố', 3),
('#1', 'Thị phần tại Việt Nam', 4);

-- Core values
INSERT INTO `about_core_values` (`icon_class`, `title`, `description`, `sort_order`) VALUES
('fa-solid fa-film', 'Công nghệ đỉnh cao', 'Độc quyền mang đến các định dạng chiếu phim tiên tiến nhất như IMAX, 4DX, ScreenX, Starium mang lại trải nghiệm nhập vai hoàn hảo.', 1),
('fa-solid fa-shapes', 'Cultureplex', 'Mô hình Tổ hợp văn hóa kết hợp đa dạng các dịch vụ mua sắm, ăn uống và giải trí ngay trong cùng một không gian rạp chiếu.', 2),
('fa-solid fa-seedling', 'Nuôi dưỡng tài năng', 'Tổ chức và tài trợ các cuộc thi phim ngắn, lớp học làm phim nhằm ươm mầm cho các đạo diễn, biên kịch trẻ của Việt Nam.', 3),
('fa-solid fa-heart', 'Trách nhiệm xã hội', 'Cam kết tổ chức định kỳ các chương trình chiếu phim miễn phí cho trẻ em vùng sâu, vùng xa có hoàn cảnh khó khăn.', 4);

-- Leadership team
INSERT INTO `about_leadership` (`name`, `role`, `avatar_type`, `avatar_value`, `avatar_color_class`, `status`, `sort_order`) VALUES
('Nguyễn Tấn Đạt', 'CEO & Founder', 'icon', 'fa-solid fa-user-tie', 'team-avatar-1', 'retired', 1),
('Nguyễn Thành Danh', 'Giám đốc vận hành', 'icon', 'fa-solid fa-user-tie', 'team-avatar-2', 'active', 2),
('Nguyễn Nhất Duy', 'Giám đốc kỹ thuật', 'icon', 'fa-solid fa-user-secret', 'team-avatar-3', 'active', 3),
('Hồ Bá Khang', 'Giám đốc sáng tạo', 'icon', 'fa-solid fa-user-secret', 'team-avatar-4', 'active', 4);

COMMIT;
SET FOREIGN_KEY_CHECKS=1;