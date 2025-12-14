-- The table is missing a Primary Key. We need to add 'app_id'.
ALTER TABLE `onlineapplication` ADD COLUMN `app_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
