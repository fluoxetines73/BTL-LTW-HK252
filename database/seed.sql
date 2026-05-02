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
('Lịch phim mới tháng 4', 'lich-phim-moi-thang-4', 'Cập nhật danh sách phim mới trong tháng 4.', 'tin-tuc', 1, 'published', NOW());

-- ============================================================
-- STATIC CONTENT TABLES
-- ============================================================
INSERT INTO `pages` (`title`, `slug`, `content`, `status`) VALUES
('Giới thiệu', 'gioi-thieu', '<section class="container mb-5"><div class="row align-items-center"><div class="col-md-6"><div class="about-content"><h2 class="mb-4" style="color: #E71A0F;">Tổ hợp Văn hóa - Cultureplex</h2><p><strong>CJ CGV</strong> là một trong top 5 cụm rạp chiếu phim lớn nhất toàn cầu và là nhà phát hành, cụm rạp chiếu phim lớn nhất Việt Nam. Chúng tôi tự hào là đơn vị tiên phong mang đến khái niệm độc đáo <strong>Cultureplex</strong> (Tổ hợp Văn hóa), nơi khán giả không chỉ đến để xem phim mà còn để trải nghiệm các dịch vụ giải trí, ẩm thực và mua sắm đẳng cấp.</p><p>Tại Việt Nam, CGV luôn nỗ lực xây dựng các chương trình Trách nhiệm xã hội như <em>"Điện ảnh cho mọi ngườii"</em>, <em>"Dự án phim ngắn CJ"</em> nhằm đồng hành và đóng góp cho sự phát triển chung của nền công nghiệp điện ảnh nước nhà.</p></div></div><div class="col-md-6"><div class="text-center p-4"><div class="about-img bg-light rounded shadow overflow-hidden" style="height: 420px;"><img src="<?= BASE_URL ?>public/images/about/about-6.png" alt="Giới thiệu CJ CGV Việt Nam" class="about-feature-image"></div></div></div></div></section>', 'published'),
('Điều khoản sử dụng', 'dieu-khoan-su-dung', '<div class="container mt-5"><div class="row"><div class="col-lg-8 mx-auto"><h1 class="mb-4">Điều Khoản Sử Dụng</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Chấp Nhận Điều Khoản</h5><p>Bằng cách sử dụng website hoặc ứng dụng CGV Cinema, bạn đồng ý tuân thủ các Điều Khoản Sử Dụng này. Nếu bạn không đồng ý, vui lòng không sử dụng dịch vụ của chúng tôi.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Tài Khoản Ngườii Dùng</h5><p>Khi tạo tài khoản, bạn phải cung cấp thông tin chính xác và cập nhật. Bạn chịu trách nhiệm bảo mật mật khẩu của mình và tất cả hoạt động xảy ra dưới tài khoản của bạn.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Vé và Đặt Chỗ</h5><p>Vé được cung cấp trên cơ sở "còn sẵn có". Vé không chuyển nhượng được và chỉ có giá trị cho suất chiếu được chỉ định.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Quyền Bản Quyền</h5><p>Tất cả nội dung trên website CGV Cinema được bảo vệ bởi quyền tác giả. Bạn không được phép sao chép hoặc phân phối nội dung mà không có sự cho phép.</p></div></div></div></div></div>', 'published'),
('Chính sách bảo mật', 'chinh-sach-bao-mat', '<div class="container mt-5"><div class="row"><div class="col-lg-8 mx-auto"><h1 class="mb-4">Chính Sách Bảo Mật</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Thu Thập Thông Tin</h5><p>CGV Cinema thu thập thông tin cá nhân của bạn khi bạn đăng ký tài khoản, mua vé, hoặc liên hệ với chúng tôi. Thông tin này bao gồm: họ tên, địa chỉ email, số điện thoại.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Sử Dụng Thông Tin</h5><p>Chúng tôi sử dụng thông tin của bạn để: xử lý đơn hàng, gửi xác nhận vé, cung cấp hỗ trợ khách hàng, và gửi thông tin khuyến mãi (nếu bạn đồng ý).</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Bảo Vệ Dữ Liệu</h5><p>Thông tin cá nhân của bạn được lưu trữ trên máy chủ được bảo vệ bằng mã hóa SSL. Chúng tôi tuân thủ các tiêu chuẩn bảo mật quốc tế.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Chia Sẻ Thông Tin</h5><p>Chúng tôi KHÔNG bán hoặc chia sẻ thông tin cá nhân của bạn với bên thứ ba mà không có sự đồng ý của bạn.</p></div></div></div></div></div>', 'published'),
('Quy định rạp chiếu', 'quy-dinh-rap-chieu', '<div class="container mt-5"><div class="row"><div class="col-lg-8 mx-auto"><h1 class="mb-4">Quy Định Rạp Chiếu</h1><div class="card mb-3"><div class="card-body"><h5 class="card-title">1. Giờ Mở Cửa</h5><p>Rạp mở cửa 30 phút trước suất chiếu đầu tiên. Quầy vé đóng cửa 15 phút sau khi suất chiếu bắt đầu.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">2. Chính Sách Điện Thoại</h5><p>Điện thoại phải để ở chế độ im lặng trong phòng chiếu. Chụp ảnh hoặc quay video bộ phim là hoàn toàn bị cấm.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">3. Chính Sách Thức Ăn</h5><p>Khách hàng chỉ được mua thức ăn và đồ uống tại quầy bắp nước của rạp. Mang thức ăn từ bên ngoài vào rạp là bị cấm.</p></div></div><div class="card mb-3"><div class="card-body"><h5 class="card-title">4. Trẻ Em</h5><p>Trẻ em dưới 3 tuổi không cần mua vé nếu ngồi chung ghế với phụ huynh. Trẻ em từ 3 tuổi trở lên cần mua vé.</p></div></div></div></div></div>', 'published');

-- ============================================================
-- FAQS
-- ============================================================
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

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'CGV Booking Demo'),
('support_email', 'support@cgv.vn'),
('support_phone', '1900 6017');

INSERT INTO `contacts` (`name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
('Nguyen Van A', 'a@example.com', '0909999999', 'Ho tro dat ve', 'Toi can ho tro khi thanh toan.', 'new');

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
