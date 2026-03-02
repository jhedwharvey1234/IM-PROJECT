-- Adds public share token support for assets (schema-safe / re-runnable)

SET @db_name = DATABASE();

SET @has_share_token = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'assets'
      AND COLUMN_NAME = 'share_token'
);

SET @has_unit_id = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'assets'
      AND COLUMN_NAME = 'unit_id'
);

SET @add_column_sql = IF(
    @has_share_token = 0,
    IF(
        @has_unit_id > 0,
        'ALTER TABLE assets ADD COLUMN share_token VARCHAR(64) NULL AFTER unit_id',
        'ALTER TABLE assets ADD COLUMN share_token VARCHAR(64) NULL'
    ),
    'SELECT "share_token column already exists" AS message'
);

PREPARE stmt_add_col FROM @add_column_sql;
EXECUTE stmt_add_col;
DEALLOCATE PREPARE stmt_add_col;

UPDATE assets
SET share_token = REPLACE(UUID(), '-', '')
WHERE share_token IS NULL OR share_token = '';

SET @has_share_token_index = (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'assets'
      AND INDEX_NAME = 'idx_assets_share_token'
);

SET @add_index_sql = IF(
    @has_share_token_index = 0,
    'ALTER TABLE assets ADD UNIQUE KEY idx_assets_share_token (share_token)',
    'SELECT "idx_assets_share_token already exists" AS message'
);

PREPARE stmt_add_idx FROM @add_index_sql;
EXECUTE stmt_add_idx;
DEALLOCATE PREPARE stmt_add_idx;
