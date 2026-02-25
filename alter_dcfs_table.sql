SET @db := DATABASE();

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND COLUMN_NAME = 'title') = 0,
    'ALTER TABLE dcfs ADD COLUMN title VARCHAR(255) NULL AFTER id',
    'SELECT ''Column title already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND COLUMN_NAME = 'due_date') = 0,
    'ALTER TABLE dcfs ADD COLUMN due_date DATE NULL AFTER description',
    'SELECT ''Column due_date already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND COLUMN_NAME = 'respondents_needed') = 0,
    'ALTER TABLE dcfs ADD COLUMN respondents_needed INT UNSIGNED NULL AFTER due_date',
    'SELECT ''Column respondents_needed already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND COLUMN_NAME = 'department_id') = 0,
    'ALTER TABLE dcfs ADD COLUMN department_id BIGINT(20) NULL AFTER due_date',
    'SELECT ''Column department_id already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE departments ENGINE=InnoDB;
ALTER TABLE dcfs ENGINE=InnoDB;
ALTER TABLE dcfs MODIFY COLUMN department_id BIGINT(20) NULL;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND INDEX_NAME = 'idx_dcfs_due_date') = 0,
    'CREATE INDEX idx_dcfs_due_date ON dcfs(due_date)',
    'SELECT ''Index idx_dcfs_due_date already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND INDEX_NAME = 'idx_dcfs_department_id') = 0,
    'CREATE INDEX idx_dcfs_department_id ON dcfs(department_id)',
    'SELECT ''Index idx_dcfs_department_id already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
     WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'dcfs' AND CONSTRAINT_NAME = 'fk_dcfs_department') = 0,
    'ALTER TABLE dcfs ADD CONSTRAINT fk_dcfs_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE RESTRICT ON UPDATE CASCADE',
    'SELECT ''Constraint fk_dcfs_department already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;