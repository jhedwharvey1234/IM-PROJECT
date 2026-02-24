<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'im';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlParts = "CREATE TABLE IF NOT EXISTS dcf_parts (
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
)";

$sqlQuestions = "CREATE TABLE IF NOT EXISTS dcf_questions (
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
)";

$sqlAlterPartId = "ALTER TABLE dcf_questions ADD COLUMN part_id INT UNSIGNED NULL AFTER dcf_id";
$sqlAlterPartIndex = "ALTER TABLE dcf_questions ADD INDEX idx_dcf_questions_part_id (part_id)";
$sqlAlterPartFk = "ALTER TABLE dcf_questions ADD CONSTRAINT fk_dcf_questions_part FOREIGN KEY (part_id) REFERENCES dcf_parts(id) ON DELETE CASCADE ON UPDATE CASCADE";
$sqlAlterAnswerType = "ALTER TABLE dcf_questions MODIFY COLUMN answer_type ENUM('multiple_choice','checkbox','dropdown','short_answer','paragraph','rate_me','wysiwyg','advance_checkbox') NOT NULL";
$sqlAlterRateMin = "ALTER TABLE dcf_questions ADD COLUMN rate_min INT NULL AFTER answer_type";
$sqlAlterRateMax = "ALTER TABLE dcf_questions ADD COLUMN rate_max INT NULL AFTER rate_min";
$sqlAlterGridRows = "ALTER TABLE dcf_questions ADD COLUMN grid_rows TEXT NULL AFTER rate_max";
$sqlAlterGridColumns = "ALTER TABLE dcf_questions ADD COLUMN grid_columns TEXT NULL AFTER grid_rows";

$sqlOptions = "CREATE TABLE IF NOT EXISTS dcf_question_options (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id INT UNSIGNED NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dcf_question_options_question_id (question_id),
    INDEX idx_dcf_question_options_sort_order (sort_order),
    CONSTRAINT fk_dcf_question_options_question FOREIGN KEY (question_id) REFERENCES dcf_questions(id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($conn->query($sqlParts) === TRUE) {
    echo "✓ dcf_parts table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcf_parts table: " . $conn->error . "\n";
}

if ($conn->query($sqlQuestions) === TRUE) {
    echo "✓ dcf_questions table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcf_questions table: " . $conn->error . "\n";
}

if ($conn->query($sqlOptions) === TRUE) {
    echo "✓ dcf_question_options table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcf_question_options table: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterPartId) === TRUE) {
    echo "✓ dcf_questions.part_id added.\n";
} elseif (stripos($conn->error, 'Duplicate column name') !== false) {
    echo "• dcf_questions.part_id already exists.\n";
} else {
    echo "✗ Error adding dcf_questions.part_id: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterPartIndex) === TRUE) {
    echo "✓ idx_dcf_questions_part_id added.\n";
} elseif (stripos($conn->error, 'Duplicate key name') !== false) {
    echo "• idx_dcf_questions_part_id already exists.\n";
} else {
    echo "✗ Error adding idx_dcf_questions_part_id: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterPartFk) === TRUE) {
    echo "✓ fk_dcf_questions_part added.\n";
} elseif (stripos($conn->error, 'Duplicate') !== false) {
    echo "• fk_dcf_questions_part already exists.\n";
} else {
    echo "✗ Error adding fk_dcf_questions_part: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterAnswerType) === TRUE) {
    echo "✓ dcf_questions.answer_type updated to include advance_checkbox.\n";
} else {
    echo "✗ Error updating answer_type enum: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterRateMin) === TRUE) {
    echo "✓ dcf_questions.rate_min added.\n";
} elseif (stripos($conn->error, 'Duplicate column name') !== false) {
    echo "• dcf_questions.rate_min already exists.\n";
} else {
    echo "✗ Error adding dcf_questions.rate_min: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterRateMax) === TRUE) {
    echo "✓ dcf_questions.rate_max added.\n";
} elseif (stripos($conn->error, 'Duplicate column name') !== false) {
    echo "• dcf_questions.rate_max already exists.\n";
} else {
    echo "✗ Error adding dcf_questions.rate_max: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterGridRows) === TRUE) {
    echo "✓ dcf_questions.grid_rows added.\n";
} elseif (stripos($conn->error, 'Duplicate column name') !== false) {
    echo "• dcf_questions.grid_rows already exists.\n";
} else {
    echo "✗ Error adding dcf_questions.grid_rows: " . $conn->error . "\n";
}

if ($conn->query($sqlAlterGridColumns) === TRUE) {
    echo "✓ dcf_questions.grid_columns added.\n";
} elseif (stripos($conn->error, 'Duplicate column name') !== false) {
    echo "• dcf_questions.grid_columns already exists.\n";
} else {
    echo "✗ Error adding dcf_questions.grid_columns: " . $conn->error . "\n";
}

$conn->close();
echo "\nSetup complete!\n";
