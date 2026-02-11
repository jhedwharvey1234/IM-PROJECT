-- Update peripherals table to add new fields and remove maintenance fields
-- Run this in phpMyAdmin or your MySQL client

-- Add new columns
ALTER TABLE `peripherals` 
ADD COLUMN `device_image` VARCHAR(255) NULL AFTER `serial_number`,
ADD COLUMN `model_number` VARCHAR(100) NULL AFTER `model`,
ADD COLUMN `purchase_cost` DECIMAL(10,2) NULL AFTER `purchase_date`,
ADD COLUMN `order_number` VARCHAR(100) NULL AFTER `purchase_cost`,
ADD COLUMN `supplier` VARCHAR(255) NULL AFTER `order_number`,
ADD COLUMN `qty` INT DEFAULT 1 AFTER `supplier`,
ADD COLUMN `requestable` TINYINT(1) DEFAULT 0 AFTER `qty`,
ADD COLUMN `byod` TINYINT(1) DEFAULT 0 AFTER `requestable`;

-- Remove maintenance-related columns (backup data first if needed!)
-- Uncomment the lines below if you want to remove these columns
-- ALTER TABLE `peripherals` DROP COLUMN `last_maintenance_date`;
-- ALTER TABLE `peripherals` DROP COLUMN `next_maintenance_due`;  
-- ALTER TABLE `peripherals` DROP COLUMN `notes`;
