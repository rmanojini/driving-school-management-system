-- 1. Fix the Primary Key to be Auto-Increment (Fixes "Duplicate Entry 0" error)
ALTER TABLE `registration` MODIFY COLUMN `index` INT(11) NOT NULL AUTO_INCREMENT;

-- 2. Add the Password Column if it is missing (Fixes data saving issues)
-- Note: If this fails saying "Duplicate column", that's fine, it means you already have it.
ALTER TABLE `registration` ADD COLUMN `password` VARCHAR(255) NOT NULL DEFAULT '';

-- 3. Verify other columns exist (just in case)
-- ALTER TABLE `registration` ADD COLUMN `status` VARCHAR(20) DEFAULT 'pending';

-- 4. Check Unique Constraints (Prevent Duplicate NICs)
-- ALTER TABLE `registration` ADD UNIQUE (`nic`);
