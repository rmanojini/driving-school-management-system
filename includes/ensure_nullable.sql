-- SAFETY FIX: Ensure all optional columns allow NULL values
-- This prevents "Field doesn't have a default value" errors when students register without these details.

-- 1. Document Uploads (Now optional/removed from student form)
ALTER TABLE `registration` MODIFY `doc_nic` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `registration` MODIFY `doc_address` VARCHAR(255) NULL DEFAULT NULL;

-- 2. Medical Details (Admin filled later)
ALTER TABLE `registration` MODIFY `medical_number` VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE `registration` MODIFY `medical_date` DATE NULL DEFAULT NULL;

-- 3. Photo (Optional)
ALTER TABLE `registration` MODIFY `photo` VARCHAR(255) NULL DEFAULT NULL;
