-- Convert users.usertype from ENUM to VARCHAR(50) so custom user_roles.role_key values are allowed
-- Safe to run multiple times

SET @db_name = DATABASE();

SET @has_users_table = (
    SELECT COUNT(*)
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
);

SET @has_usertype_column = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'usertype'
);

SET @column_data_type = (
    SELECT DATA_TYPE
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'usertype'
    LIMIT 1
);

SET @alter_sql = IF(
    @has_users_table = 1 AND @has_usertype_column = 1 AND @column_data_type = 'enum',
    'ALTER TABLE users MODIFY COLUMN usertype VARCHAR(50) NOT NULL',
    'SELECT "users.usertype is already VARCHAR or users/usertype missing" AS message'
);

PREPARE stmt_alter_usertype FROM @alter_sql;
EXECUTE stmt_alter_usertype;
DEALLOCATE PREPARE stmt_alter_usertype;
