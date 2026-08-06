ALTER TABLE `comments` 
ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_approved`;
