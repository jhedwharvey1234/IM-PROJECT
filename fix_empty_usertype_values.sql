-- One-time cleanup for existing users with blank/NULL usertype
-- Sets them to 'readonly' by default

UPDATE users
SET usertype = 'readonly'
WHERE usertype IS NULL OR TRIM(usertype) = '';
