-- Add manager fields only (idempotent checks)
SET @dbname = DATABASE();
SET @tablename = "users";
SET @columnname = "entra_manager_object_id";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE
    (COLUMN_NAME = @columnname) AND (TABLE_NAME = @tablename) AND (TABLE_SCHEMA = @dbname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN entra_manager_object_id VARCHAR(64) NULL AFTER entra_mail")
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @columnname = "entra_manager_display_name";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE
    (COLUMN_NAME = @columnname) AND (TABLE_NAME = @tablename) AND (TABLE_SCHEMA = @dbname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN entra_manager_display_name VARCHAR(191) NULL AFTER entra_manager_object_id")
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @columnname = "entra_manager_user_principal_name";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE
    (COLUMN_NAME = @columnname) AND (TABLE_NAME = @tablename) AND (TABLE_SCHEMA = @dbname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN entra_manager_user_principal_name VARCHAR(191) NULL AFTER entra_manager_display_name")
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @columnname = "entra_manager_mail";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE
    (COLUMN_NAME = @columnname) AND (TABLE_NAME = @tablename) AND (TABLE_SCHEMA = @dbname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN entra_manager_mail VARCHAR(191) NULL AFTER entra_manager_user_principal_name")
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
