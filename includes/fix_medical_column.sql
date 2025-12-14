-- Fix duplicate entry issue by making medical columns nullable
-- This allows multiple students to have NULL (pending) medical numbers

ALTER TABLE `registration` MODIFY `medical_number` VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE `registration` MODIFY `medical_date` DATE NULL DEFAULT NULL;

-- If there's a unique constraint on medical_number causing issues with empty strings/0, we drop it (or ensuring NULLs works if it handles distinct NULLs)
-- Note: In MySQL, multiple NULLs are allowed in UNIQUE index. But multiple '' or '0' are not.
-- So shifting to NULL is the correct fix.
