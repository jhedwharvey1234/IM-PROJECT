-- Add consent field to dcf_responses table
ALTER TABLE dcf_responses ADD COLUMN user_consent TINYINT(1) DEFAULT 0 AFTER respondent_email;
ALTER TABLE dcf_responses ADD COLUMN consent_timestamp DATETIME NULL AFTER user_consent;

CREATE INDEX idx_dcf_responses_user_consent ON dcf_responses(user_consent);
