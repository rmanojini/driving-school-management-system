-- Add username column to onlineapplication table
ALTER TABLE onlineapplication ADD COLUMN username VARCHAR(50) AFTER name;

-- Add username column to registration table
ALTER TABLE registration ADD COLUMN username VARCHAR(50) AFTER name;

-- Add UNIQUE constraint to username in registration to prevent duplicates
-- We might need to fix data first, so we'll do this carefully. 
-- First, populate existing username with NIC (assuming NIC is unique and present)
UPDATE onlineapplication SET username = nic WHERE username IS NULL OR username = '';
UPDATE registration SET username = nic WHERE username IS NULL OR username = '';

-- Now try to add constraints (optional, but good for data integrity)
-- ALTER TABLE registration ADD UNIQUE (username);
-- ALTER TABLE onlineapplication ADD UNIQUE (username);
