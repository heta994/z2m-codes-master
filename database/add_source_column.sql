-- Add 'source' column to submissions_pending (for admin vs contributor)
USE z2m_codes;
ALTER TABLE submissions_pending ADD COLUMN source VARCHAR(20) DEFAULT 'contributor' AFTER contributor_email;
