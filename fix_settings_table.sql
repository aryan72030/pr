-- Run this SQL to fix existing settings table
ALTER TABLE `settings` MODIFY `value` VARCHAR(255) NULL;
