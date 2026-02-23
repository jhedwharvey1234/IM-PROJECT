CREATE TABLE IF NOT EXISTS dcf_parts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dcf_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dcf_parts_dcf_id (dcf_id),
    INDEX idx_dcf_parts_sort_order (sort_order),
    CONSTRAINT fk_dcf_parts_dcf FOREIGN KEY (dcf_id) REFERENCES dcfs(id) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE dcf_questions
    ADD COLUMN IF NOT EXISTS part_id INT UNSIGNED NULL AFTER dcf_id;

ALTER TABLE dcf_questions
    ADD INDEX IF NOT EXISTS idx_dcf_questions_part_id (part_id);

SET @fk_exists := (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'dcf_questions'
      AND CONSTRAINT_NAME = 'fk_dcf_questions_part'
      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);

SET @sql := IF(
    @fk_exists = 0,
    'ALTER TABLE dcf_questions ADD CONSTRAINT fk_dcf_questions_part FOREIGN KEY (part_id) REFERENCES dcf_parts(id) ON DELETE CASCADE ON UPDATE CASCADE',
    'SELECT "fk_dcf_questions_part already exists"'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE dcf_questions
    MODIFY COLUMN answer_type ENUM('multiple_choice','checkbox','dropdown','short_answer','paragraph','rate_me') NOT NULL;

ALTER TABLE dcf_questions
    ADD COLUMN IF NOT EXISTS rate_min INT NULL AFTER answer_type;

ALTER TABLE dcf_questions
    ADD COLUMN IF NOT EXISTS rate_max INT NULL AFTER rate_min;
