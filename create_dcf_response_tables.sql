CREATE TABLE IF NOT EXISTS dcf_responses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dcf_id INT UNSIGNED NOT NULL,
    respondent_name VARCHAR(255) NOT NULL,
    respondent_mobile VARCHAR(50) NULL,
    respondent_email VARCHAR(255) NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    INDEX idx_dcf_responses_dcf_id (dcf_id),
    INDEX idx_dcf_responses_submitted_at (submitted_at),
    CONSTRAINT fk_dcf_responses_dcf FOREIGN KEY (dcf_id) REFERENCES dcfs(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS dcf_response_answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    response_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    answer_text TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_dcf_response_answers_response_id (response_id),
    INDEX idx_dcf_response_answers_question_id (question_id),
    CONSTRAINT fk_dcf_response_answers_response FOREIGN KEY (response_id) REFERENCES dcf_responses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_dcf_response_answers_question FOREIGN KEY (question_id) REFERENCES dcf_questions(id) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE dcfs ADD COLUMN share_token VARCHAR(64) UNIQUE NULL AFTER department_id;
CREATE INDEX idx_dcfs_share_token ON dcfs(share_token);