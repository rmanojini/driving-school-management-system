-- Run these commands to fix the missing columns in your database.
-- It is safe to run them; if a column exists, it might give a warning but won't break data.

ALTER TABLE `onlineapplication` ADD COLUMN `status` VARCHAR(20) DEFAULT 'pending';
ALTER TABLE `onlineapplication` ADD COLUMN `doc_nic` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `onlineapplication` ADD COLUMN `doc_address` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `onlineapplication` ADD COLUMN `classofvehicle` VARCHAR(50) NOT NULL DEFAULT '';
