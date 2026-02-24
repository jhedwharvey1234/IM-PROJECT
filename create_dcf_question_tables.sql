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

CREATE TABLE IF NOT EXISTS dcf_questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dcf_id INT UNSIGNED NOT NULL,
    part_id INT UNSIGNED NULL,
    question_text TEXT NOT NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    answer_type ENUM('multiple_choice','checkbox','dropdown','short_answer','paragraph','rate_me','wysiwyg','advance_checkbox') NOT NULL,
    rate_min INT NULL,
    rate_max INT NULL,
    grid_rows TEXT NULL,
    grid_columns TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dcf_questions_dcf_id (dcf_id),
    INDEX idx_dcf_questions_part_id (part_id),
    INDEX idx_dcf_questions_sort_order (sort_order),
    CONSTRAINT fk_dcf_questions_dcf FOREIGN KEY (dcf_id) REFERENCES dcfs(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_dcf_questions_part FOREIGN KEY (part_id) REFERENCES dcf_parts(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS dcf_question_options (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id INT UNSIGNED NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dcf_question_options_question_id (question_id),
    INDEX idx_dcf_question_options_sort_order (sort_order),
    CONSTRAINT fk_dcf_question_options_question FOREIGN KEY (question_id) REFERENCES dcf_questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);