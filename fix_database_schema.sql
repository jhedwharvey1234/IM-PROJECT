-- Fix database schema issues
-- Add missing phone column to application_contacts table

ALTER TABLE `application_contacts` 
ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL AFTER `email`;

-- Verify the fix
DESCRIBE application_contacts;
