-- ============================================================
-- SETTINGS TABLE & FEATURED MOVIE INITIALIZATION SCRIPT
-- ============================================================
-- Purpose: Ensure settings table exists and featured_movie_id is configured
-- Run this after database/schema.sql is imported
--
-- IMAGE PATH CONVENTION:
-- Store image paths relative to BASE_URL (defined in index.php).
-- Use format: public/images/... (no leading slash)
-- At runtime, controllers/views prepend BASE_URL to these paths.
-- This ensures compatibility with subdirectory deployments.

-- ============================================================
-- 1. Ensure Settings Table Exists
-- ============================================================
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. Ensure At Least One Movie Exists (for featured movie)
-- ============================================================
INSERT INTO `movies` (
    `title`, `slug`, `description`, `director`, `duration_min`, 
    `release_date`, `poster`, `banner`, `age_rating`, `status`
) 
SELECT 
    'The Inception', 
    'the-inception', 
    'A skilled thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
    'Christopher Nolan',
    148,
    DATE_SUB(CURDATE(), INTERVAL 30 DAY),
    'public/images/movies/inception-poster.jpg',
    'public/images/movies/inception-banner.jpg',
    'C13',
    'now_showing'
WHERE NOT EXISTS (SELECT 1 FROM movies LIMIT 1);

-- ============================================================
-- 3. Set featured_movie_id to First Movie
-- ============================================================
INSERT INTO `settings` (`setting_key`, `setting_value`) 
VALUES (
    'featured_movie_id', 
    (SELECT MIN(id) FROM movies)
)
ON DUPLICATE KEY UPDATE 
    `setting_value` = (SELECT MIN(id) FROM movies),
    `updated_at` = NOW();

-- ============================================================
-- 4. Verification Queries
-- ============================================================

-- Check: Settings table exists and has featured_movie_id
-- Expected: 1 row with setting_key='featured_movie_id' and valid movie ID
SELECT 
    'featured_movie_id' as test_name,
    s.setting_key,
    s.setting_value,
    m.title as featured_movie_title,
    'PASS' as status
FROM settings s
LEFT JOIN movies m ON m.id = s.setting_value
WHERE s.setting_key = 'featured_movie_id'
AND s.setting_value IS NOT NULL;

-- Check: Featured movie exists
-- Expected: at least 1 row
SELECT 
    'Featured Movie Exists' as test_name,
    m.id,
    m.title,
    m.status,
    'PASS' as status
FROM movies m
WHERE m.id = (
    SELECT COALESCE(
        (SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'),
        (SELECT MIN(id) FROM movies)
    )
)
LIMIT 1;

-- ============================================================
-- Notes for Controller Implementation:
-- ============================================================
-- Query 1: Get featured movie ID (with fallback)
-- SELECT COALESCE(
--     (SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'),
--     (SELECT MIN(id) FROM movies)
-- )
--
-- Query 2: Get featured movie details
-- SELECT m.* FROM movies m
-- WHERE m.id = (
--     SELECT COALESCE(
--         (SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'),
--         (SELECT MIN(id) FROM movies)
--     )
-- )
-- LIMIT 1;
