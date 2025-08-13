-- Add google_id column to users table for Google OAuth integration
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL AFTER mail;

-- Add index for better performance when searching by google_id
CREATE INDEX idx_users_google_id ON users(google_id);

-- Add unique constraint to prevent duplicate google_id entries
ALTER TABLE users ADD UNIQUE KEY unique_google_id (google_id);
