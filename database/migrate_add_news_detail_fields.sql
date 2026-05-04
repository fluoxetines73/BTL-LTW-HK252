-- Migration: Add highlighted title and separate detail content for news detail page
-- Run this for existing databases

ALTER TABLE `news`
ADD COLUMN `highlight_title` VARCHAR(255) DEFAULT NULL AFTER `title`,
ADD COLUMN `detail_content` LONGTEXT DEFAULT NULL AFTER `content`;
