-- ============================================================================
-- 05-seed-movies-extended.sql
-- Thêm 25 bộ phim mở rộng (tổng cộng 31 phim với seed-main.sql)
-- Chạy sau: 04-seed-main.sql
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Xóa dữ liệu phim cũ để tránh trùng lặp
TRUNCATE TABLE `movie_genres`;
TRUNCATE TABLE `movies`;

-- Thêm 25 bộ phim mở rộng
INSERT INTO `movies` (`id`, `title`, `slug`, `description`, `duration_min`, `release_date`, `age_rating`, `status`) VALUES
(1, 'Mai', 'mai', 'Phim tâm lý tình cảm Việt Nam', 131, '2026-02-10', 'C16', 'now_showing'),
(2, 'Lật Mặt 7: Một Điều Ước', 'lat-mat-7', 'Hành trình gia đình cảm động', 138, '2026-04-26', 'C13', 'now_showing'),
(3, 'Godzilla x Kong', 'godzilla-x-kong', 'Đại chiến quái vật khổng lồ', 115, '2026-03-29', 'C13', 'now_showing'),
(4, 'Kung Fu Panda 4', 'kung-fu-panda-4', 'Gấu Po trở lại lợi hại hơn', 94, '2026-03-08', 'P', 'now_showing'),
(5, 'Dune: Hành Tinh Cát 2', 'dune-2', 'Sử thi khoa học viễn tưởng', 166, '2026-03-01', 'C13', 'now_showing'),
(6, 'Exhuma: Quật Mộ Trùng Tang', 'exhuma', 'Phim kinh dị bí ẩn Hàn Quốc', 134, '2026-03-15', 'C16', 'now_showing'),
(7, 'Thanh Xuân 18x2', 'thanh-xuan-18x2', 'Câu chuyện tình yêu lãng mạn', 124, '2026-04-05', 'C13', 'now_showing'),
(8, 'Bầu Trởi Rực Đỏ', 'bau-troi-ruc-do', 'Phim hành động kịch tính', 110, '2026-06-01', 'C18', 'coming_soon'),
(9, 'Mèo Béo Siêu Quậy', 'meo-beo-sieu-quay', 'Hoạt hình gia đình vui nhộn', 101, '2026-05-20', 'P', 'coming_soon'),
(10, 'Kẻ Kiến Tạo', 'the-creator', 'Tương lai giữa ngườii và AI', 133, '2026-05-30', 'C13', 'coming_soon'),
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

SET FOREIGN_KEY_CHECKS = 1;
