-- Add Entra object id column to users (schema-safe / re-runnable)

SET @db_name = DATABASE();

SET @has_users = (
    SELECT COUNT(*)
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
);

SET @has_column = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'entra_object_id'
);

SET @add_column_sql = IF(
    @has_users = 1 AND @has_column = 0,
    'ALTER TABLE users ADD COLUMN entra_object_id VARCHAR(100) NULL AFTER usertype',
    'SELECT "users.entra_object_id already exists or users missing" AS message'
);

PREPARE stmt_add_col FROM @add_column_sql;
EXECUTE stmt_add_col;
DEALLOCATE PREPARE stmt_add_col;

SET @has_index = (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND INDEX_NAME = 'uq_users_entra_object_id'
);

SET @add_index_sql = IF(
    @has_users = 1 AND @has_index = 0,
    'ALTER TABLE users ADD UNIQUE KEY uq_users_entra_object_id (entra_object_id)',
    'SELECT "uq_users_entra_object_id already exists or users missing" AS message'
);

PREPARE stmt_add_idx FROM @add_index_sql;
EXECUTE stmt_add_idx;
DEALLOCATE PREPARE stmt_add_idx;
