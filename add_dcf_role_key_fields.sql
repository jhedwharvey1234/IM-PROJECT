-- Adds role targeting fields to DCF parts/questions (schema-safe / re-runnable)

SET @db_name = DATABASE();

SET @has_dcf_parts = (
    SELECT COUNT(*)
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_parts'
);

SET @has_dcf_questions = (
    SELECT COUNT(*)
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_questions'
);

SET @has_part_role_key = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_parts'
      AND COLUMN_NAME = 'role_key'
);

SET @has_question_role_key = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'dcf_questions'
      AND COLUMN_NAME = 'role_key'
);

SET @add_part_role_sql = IF(
    @has_dcf_parts = 1 AND @has_part_role_key = 0,
    'ALTER TABLE dcf_parts ADD COLUMN role_key VARCHAR(50) NOT NULL DEFAULT ''all'' AFTER description',
    'SELECT "dcf_parts.role_key already exists or dcf_parts missing" AS message'
);

PREPARE stmt_add_part_role FROM @add_part_role_sql;
EXECUTE stmt_add_part_role;
DEALLOCATE PREPARE stmt_add_part_role;

SET @add_question_role_sql = IF(
    @has_dcf_questions = 1 AND @has_question_role_key = 0,
  'ALTER TABLE dcf_questions ADD COLUMN role_key VARCHAR(50) NULL AFTER question_text',
    'SELECT "dcf_questions.role_key already exists or dcf_questions missing" AS message'
);

PREPARE stmt_add_question_role FROM @add_question_role_sql;
EXECUTE stmt_add_question_role;
DEALLOCATE PREPARE stmt_add_question_role;

UPDATE dcf_parts
SET role_key = 'all'
WHERE role_key IS NULL OR role_key = '';

UPDATE dcf_questions
SET role_key = NULL
WHERE role_key = '';
