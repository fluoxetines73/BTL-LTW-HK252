-- Cinema Booking Database Schema
-- Engine: InnoDB, Charset: utf8mb4_unicode_ci

SET FOREIGN_KEY_CHECKS=0;

-- Drop tables in reverse FK order (dependent tables first)
DROP TABLE IF EXISTS `booking_combos`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `seat_reservations`;
DROP TABLE IF EXISTS `showtimes`;
DROP TABLE IF EXISTS `seats`;
DROP TABLE IF EXISTS `movie_genres`;
DROP TABLE IF EXISTS `combo_items`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `movies`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `promotions`;
DROP TABLE IF EXISTS `combos`;
DROP TABLE IF EXISTS `seat_types`;
DROP TABLE IF EXISTS `cinemas`;
DROP TABLE IF EXISTS `genres`;
DROP TABLE IF EXISTS `user_registration_otps`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('admin','member') NOT NULL DEFAULT 'member',
    `points` INT NOT NULL DEFAULT 0,
    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: user_registration_otps
-- --------------------------------------------------------
CREATE TABLE `user_registration_otps` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `otp_hash` VARCHAR(255) NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `expires_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_otp_email` (`email`),
    INDEX `idx_otp_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: genres
-- --------------------------------------------------------
CREATE TABLE `genres` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: cinemas
-- --------------------------------------------------------
CREATE TABLE `cinemas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_slug` (`slug`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: seat_types
-- --------------------------------------------------------
CREATE TABLE `seat_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` ENUM('Standard','VIP','Couple','SweetBox') NOT NULL,
    `price_multiplier` DECIMAL(3,2) NOT NULL,
    `color_code` VARCHAR(7) DEFAULT NULL,
    `col_span` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: combos
-- --------------------------------------------------------
CREATE TABLE `combos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: promotions
-- --------------------------------------------------------
CREATE TABLE `promotions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `discount_type` ENUM('percent','fixed') NOT NULL,
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_order_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `max_discount` DECIMAL(10,2) NULL,
    `usage_limit` INT NULL,
    `used_count` INT NOT NULL DEFAULT 0,
    `valid_from` DATE NOT NULL,
    `valid_to` DATE NOT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: contacts
-- --------------------------------------------------------
CREATE TABLE `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `subject` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new','read','replied') NOT NULL DEFAULT 'new',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: pages
-- --------------------------------------------------------
CREATE TABLE `pages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `content` TEXT NOT NULL,
    `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: movies
-- --------------------------------------------------------
CREATE TABLE `movies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(300) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `director` VARCHAR(255) DEFAULT NULL,
    `cast` TEXT DEFAULT NULL,
    `duration_min` INT NOT NULL,
    `release_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `poster` VARCHAR(255) DEFAULT NULL,
    `banner` VARCHAR(255) DEFAULT NULL,
    `trailer_url` VARCHAR(255) DEFAULT NULL,
    `age_rating` ENUM('P','C13','C16','C18') NOT NULL,
    `status` ENUM('now_showing','coming_soon','ended') NOT NULL,
    `language` VARCHAR(50) DEFAULT NULL,
    `subtitle` VARCHAR(50) DEFAULT NULL,
    `country` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_slug` (`slug`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: rooms
-- --------------------------------------------------------
CREATE TABLE `rooms` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cinema_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `total_rows` INT NOT NULL,
    `total_cols` INT NOT NULL,
    `screen_type` ENUM('2D','3D','IMAX','4DX') NOT NULL,
    `status` ENUM('active','maintenance','inactive') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_cinema` (`cinema_id`),
    INDEX `idx_status` (`status`),
    CONSTRAINT `fk_r_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: combo_items
-- --------------------------------------------------------
CREATE TABLE `combo_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `combo_id` INT NOT NULL,
    `item_name` VARCHAR(255) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_combo` (`combo_id`),
    CONSTRAINT `fk_ci_combo` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: movie_genres
-- --------------------------------------------------------
CREATE TABLE `movie_genres` (
    `movie_id` INT NOT NULL,
    `genre_id` INT NOT NULL,
    PRIMARY KEY (`movie_id`, `genre_id`),
    CONSTRAINT `fk_mg_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_mg_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: seats
-- --------------------------------------------------------
CREATE TABLE `seats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `room_id` INT NOT NULL,
    `seat_type_id` INT NOT NULL,
    `row_label` CHAR(2) NOT NULL,
    `col_number` INT NOT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY `uk_seat` (`room_id`, `row_label`, `col_number`),
    INDEX `idx_room` (`room_id`),
    INDEX `idx_seat_type` (`seat_type_id`),
    CONSTRAINT `fk_s_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_s_seat_type` FOREIGN KEY (`seat_type_id`) REFERENCES `seat_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: showtimes
-- --------------------------------------------------------
CREATE TABLE `showtimes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `movie_id` INT NOT NULL,
    `room_id` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `base_price` DECIMAL(10,2) NOT NULL,
    `status` ENUM('scheduled','cancelled','completed') NOT NULL DEFAULT 'scheduled',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_movie` (`movie_id`),
    INDEX `idx_room` (`room_id`),
    INDEX `idx_start_time` (`start_time`),
    INDEX `idx_status` (`status`),
    CONSTRAINT `fk_st_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_st_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: seat_reservations
-- --------------------------------------------------------
CREATE TABLE `seat_reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `showtime_id` INT NOT NULL,
    `seat_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `status` ENUM('locked','booked','expired') NOT NULL DEFAULT 'locked',
    `locked_until` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_showtime_seat` (`showtime_id`, `seat_id`),
    INDEX `idx_showtime` (`showtime_id`),
    INDEX `idx_seat` (`seat_id`),
    INDEX `idx_user` (`user_id`),
    CONSTRAINT `fk_sr_showtime` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sr_seat` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: bookings
-- --------------------------------------------------------
CREATE TABLE `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_code` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT NOT NULL,
    `showtime_id` INT NOT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `final_amount` DECIMAL(10,2) NOT NULL,
    `promotion_id` INT NULL,
    `payment_method` ENUM('vnpay','cash') NOT NULL,
    `payment_status` ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    `vnp_transaction_no` VARCHAR(255) NULL,
    `status` ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_showtime` (`showtime_id`),
    INDEX `idx_booking_code` (`booking_code`),
    INDEX `idx_payment_status` (`payment_status`),
    INDEX `idx_status` (`status`),
    CONSTRAINT `fk_b_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_b_showtime` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_b_promotion` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: tickets
-- --------------------------------------------------------
CREATE TABLE `tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `seat_id` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_booking` (`booking_id`),
    INDEX `idx_seat` (`seat_id`),
    CONSTRAINT `fk_t_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_t_seat` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: reviews
-- --------------------------------------------------------
CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `movie_id` INT NOT NULL,
    `rating` TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    `comment` TEXT DEFAULT NULL,
    `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_movie` (`movie_id`),
    INDEX `idx_status` (`status`),
    CONSTRAINT `fk_r_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_r_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: news
-- --------------------------------------------------------
CREATE TABLE `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(300) NOT NULL,
    `content` TEXT NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `category` ENUM('tin-tuc','khuyen-mai','su-kien') NOT NULL,
    `author_id` INT NOT NULL,
    `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
    `published_at` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_slug` (`slug`),
    INDEX `idx_category` (`category`),
    INDEX `idx_author` (`author_id`),
    INDEX `idx_status` (`status`),
    CONSTRAINT `fk_n_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: booking_combos
-- --------------------------------------------------------
CREATE TABLE `booking_combos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `combo_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_booking` (`booking_id`),
    INDEX `idx_combo` (`combo_id`),
    CONSTRAINT `fk_bc_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bc_combo` FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
