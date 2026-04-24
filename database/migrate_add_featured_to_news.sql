-- Migration: Add 'featured' column to news table and 'phim-hay-thang' category
-- Run this if you already have data in the news table

ALTER TABLE `news` 
ADD COLUMN `featured` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`,
ADD COLUMN `highlight_title` VARCHAR(255) DEFAULT NULL AFTER `title`,
ADD COLUMN `detail_content` LONGTEXT DEFAULT NULL AFTER `content`,
ADD INDEX `idx_featured` (`featured`),
MODIFY COLUMN `category` ENUM('tin-tuc','khuyen-mai','su-kien','phim-hay-thang') NOT NULL;
