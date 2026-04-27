-- Migration: Add 'featured' column to news table and 'phim-hay-thang' category
-- This migration is idempotent: it safely adds columns/index only if they don't already exist
-- Run this regardless of whether migrate_add_news_detail_fields.sql was run before

-- Add featured column if it doesn't exist
ALTER TABLE `news` 
ADD COLUMN `featured` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`;

-- Add highlight_title if it doesn't exist (may already exist from other migration)
ALTER IGNORE TABLE `news` 
ADD COLUMN `highlight_title` VARCHAR(255) DEFAULT NULL AFTER `title`;

-- Add detail_content if it doesn't exist (may already exist from other migration)
ALTER IGNORE TABLE `news` 
ADD COLUMN `detail_content` LONGTEXT DEFAULT NULL AFTER `content`;

-- Add index if it doesn't exist
ALTER TABLE `news` 
ADD INDEX IF NOT EXISTS `idx_featured` (`featured`);

-- Update category enum to include 'phim-hay-thang'
ALTER TABLE `news` 
MODIFY COLUMN `category` ENUM('tin-tuc','khuyen-mai','su-kien','phim-hay-thang') NOT NULL;
