CREATE TABLE IF NOT EXISTS `onlineapplication` (
    `app_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `dob` DATE NOT NULL,
    `age` INT(3) NOT NULL,
    `nic` VARCHAR(20) NOT NULL UNIQUE,
    `gender` VARCHAR(10) NOT NULL,
    `address` TEXT NOT NULL,
    `phone_number` VARCHAR(15) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `classofvehicle` VARCHAR(50) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `reg_date` DATE NOT NULL,
    `doc_nic` VARCHAR(255) DEFAULT NULL,
    `doc_address` VARCHAR(255) DEFAULT NULL
);
