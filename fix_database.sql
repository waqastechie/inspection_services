-- Add tracking columns to inspections table
ALTER TABLE inspections ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER status;
ALTER TABLE inspections ADD COLUMN completed_at TIMESTAMP NULL AFTER created_by;  
ALTER TABLE inspections ADD COLUMN completed_by BIGINT UNSIGNED NULL AFTER completed_at;

-- Show table structure to verify
DESCRIBE inspections;
