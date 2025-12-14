-- Phase 1 Updates (Already applied? If not, run these)
ALTER TABLE `registration` 
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `email`,
ADD COLUMN `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER `password`;

-- Phase 2 Updates: Document Uploads
ALTER TABLE `registration`
ADD COLUMN `doc_nic` VARCHAR(255) NULL AFTER `photo`,
ADD COLUMN `doc_address` VARCHAR(255) NULL AFTER `doc_nic`;

-- New Table: Payments
CREATE TABLE `payments` (
    `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `payment_type` ENUM('1st_installment', '2nd_installment', 'full_payment', 'exam_fee') NOT NULL,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `remarks` TEXT,
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);

-- New Table: Schedules (Lessons & Tests)
CREATE TABLE `schedules` (
    `schedule_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(50) NOT NULL,
    `schedule_type` ENUM('lesson', 'theory_test', 'practical_test') NOT NULL,
    `scheduled_datetime` DATETIME NOT NULL,
    `instructor_id` INT NULL, -- Can be linked to an instructors table later
    `status` ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);

-- New Table: Exam Results
CREATE TABLE `results` (
    `result_id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(50) NOT NULL,
    `exam_type` ENUM('theory', 'practical') NOT NULL,
    `marks` INT,
    `result_status` ENUM('pass', 'fail', 'pending') DEFAULT 'pending',
    `exam_date` DATE NOT NULL,
    FOREIGN KEY (`student_id`) REFERENCES `registration`(`index`) ON DELETE CASCADE
);
