-- Since the table already exists, we just need to add the missing columns.
-- Run these lines one by one or together.

ALTER TABLE `onlineapplication` ADD COLUMN `password` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `onlineapplication` ADD COLUMN `status` VARCHAR(20) DEFAULT 'pending';

-- Optional: Ensure other columns exist if they are missing
-- ALTER TABLE `onlineapplication` ADD COLUMN `classofvehicle` VARCHAR(50) NOT NULL;
