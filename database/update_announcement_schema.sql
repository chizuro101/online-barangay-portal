-- Add image column to announcement_post table
ALTER TABLE `announcement_post` ADD COLUMN `post_image` VARCHAR(255) NULL DEFAULT NULL AFTER `post_body`;

-- Add author tracking columns
ALTER TABLE `announcement_post` ADD COLUMN `author_id` INT(11) NULL DEFAULT NULL AFTER `post_image`;
ALTER TABLE `announcement_post` ADD COLUMN `author_type` ENUM('admin', 'resident') NULL DEFAULT NULL AFTER `author_id`;

-- Create uploads directory for announcement images
-- Note: This needs to be created manually in the filesystem
-- CREATE DIRECTORY IF NOT EXISTS 'uploads/announcements';
