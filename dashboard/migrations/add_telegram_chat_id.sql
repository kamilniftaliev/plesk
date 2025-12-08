-- Migration: Add telegram_chat_id column to user table
-- Date: 2025-12-08
-- Description: Add Telegram Chat ID field for OTP authentication via Telegram

-- Add telegram_chat_id column after email column
ALTER TABLE `user` ADD COLUMN IF NOT EXISTS `telegram_chat_id` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;

-- Add index for faster lookups
ALTER TABLE `user` ADD INDEX IF NOT EXISTS `idx_telegram_chat_id` (`telegram_chat_id`);

-- Display confirmation
SELECT 'Migration completed successfully!' AS status;
