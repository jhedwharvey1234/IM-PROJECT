-- Add respondent_role field to dcf_responses (schema-safe / re-runnable)

SET @db_name = DATABASE();

SET @has_table = (
    SELECT COUNT(*)
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_responses'
);

SET @has_column = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_responses'
      AND COLUMN_NAME = 'respondent_role'
);

SET @sql = IF(
    @has_table = 1 AND @has_column = 0,
    'ALTER TABLE dcf_responses ADD COLUMN respondent_role VARCHAR(50) NULL AFTER respondent_email',
    'SELECT "dcf_responses.respondent_role already exists or table missing" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
