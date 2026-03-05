-- Split users main usertype and added custom role into separate fields

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS user_role_id INT(11) UNSIGNED NULL AFTER usertype;

CREATE INDEX IF NOT EXISTS idx_users_user_role_id ON users (user_role_id);

-- Ensure custom role keys from users.usertype exist in user_roles
INSERT INTO user_roles (role_name, role_key, description)
SELECT DISTINCT
  CONCAT(UCASE(LEFT(REPLACE(u.usertype, '_', ' '), 1)), SUBSTRING(REPLACE(u.usertype, '_', ' '), 2)) AS role_name,
  u.usertype AS role_key,
  'Migrated from users.usertype' AS description
FROM users u
LEFT JOIN user_roles ur ON ur.role_key = u.usertype
WHERE u.usertype IS NOT NULL
  AND u.usertype <> ''
  AND u.usertype NOT IN ('superadmin', 'readandwrite', 'readonly')
  AND ur.id IS NULL;

-- Move custom role key mapping to users.user_role_id and normalize main usertype to readonly
UPDATE users u
JOIN user_roles ur ON ur.role_key = u.usertype
SET u.user_role_id = ur.id,
    u.usertype = 'readonly'
WHERE u.usertype IS NOT NULL
  AND u.usertype <> ''
  AND u.usertype NOT IN ('superadmin', 'readandwrite', 'readonly');

-- Optional FK (skip if it fails due existing constraints engine/version)
ALTER TABLE users
  ADD CONSTRAINT fk_users_user_role_id
  FOREIGN KEY (user_role_id) REFERENCES user_roles(id)
  ON DELETE SET NULL
  ON UPDATE CASCADE;
