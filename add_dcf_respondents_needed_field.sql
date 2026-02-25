SET @db := DATABASE();

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND COLUMN_NAME = 'respondents_needed') = 0,
    'ALTER TABLE dcfs ADD COLUMN respondents_needed INT UNSIGNED NULL AFTER due_date',
    'SELECT ''Column respondents_needed already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
