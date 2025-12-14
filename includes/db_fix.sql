-- FIXED SCRIPT
-- Use this to create the missing tables.

-- Ensure registration index is the Primary Key (Type is INT)
-- (No action needed as inspection confirmed it is INT Primary Key)

-- New Table: Payments (Fixed student_id to INT)
CREATE TABLE IF NOT EXISTS `payments` (
    `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT(10) NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `payment_type` ENUM('1st_installment', '2nd_installment', 'full_payment', 'exam_fee') NOT NULL,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `remarks` TEXT,
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);

-- New Table: Schedules (Fixed student_id to INT)
CREATE TABLE IF NOT EXISTS `schedules` (
    `schedule_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT(10) NOT NULL,
    `schedule_type` ENUM('lesson', 'theory_test', 'practical_test') NOT NULL,
    `scheduled_datetime` DATETIME NOT NULL,
    `instructor_id` INT NULL,
    `status` ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);

-- New Table: Exam Results (Fixed student_id to INT)
CREATE TABLE IF NOT EXISTS `results` (
    `result_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT(10) NOT NULL,
    `exam_type` ENUM('theory', 'practical') NOT NULL,
    `marks` INT,
    `result_status` ENUM('pass', 'fail', 'pending') DEFAULT 'pending',
    `exam_date` DATE NOT NULL,
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);
