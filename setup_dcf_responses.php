<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'im';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function columnExists(mysqli $conn, string $table, string $column): bool
{
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $sql = "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

$sqlResponses = "CREATE TABLE IF NOT EXISTS dcf_responses (
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
)";

$sqlAnswers = "CREATE TABLE IF NOT EXISTS dcf_response_answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    response_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    answer_text TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_dcf_response_answers_response_id (response_id),
    INDEX idx_dcf_response_answers_question_id (question_id),
    CONSTRAINT fk_dcf_response_answers_response FOREIGN KEY (response_id) REFERENCES dcf_responses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_dcf_response_answers_question FOREIGN KEY (question_id) REFERENCES dcf_questions(id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($conn->query($sqlResponses) === TRUE) {
    echo "✓ dcf_responses table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcf_responses table: " . $conn->error . "\n";
}

if ($conn->query($sqlAnswers) === TRUE) {
    echo "✓ dcf_response_answers table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcf_response_answers table: " . $conn->error . "\n";
}

if (!columnExists($conn, 'dcfs', 'share_token')) {
    if ($conn->query("ALTER TABLE dcfs ADD COLUMN share_token VARCHAR(64) UNIQUE NULL AFTER department_id") === TRUE) {
        echo "✓ Added column: share_token to dcfs table\n";
    } else {
        echo "✗ Failed to add share_token: " . $conn->error . "\n";
    }
}

$result = $conn->query("SHOW INDEX FROM dcfs WHERE Key_name = 'idx_dcfs_share_token'");
if ($result && $result->num_rows === 0) {
    if ($conn->query("CREATE INDEX idx_dcfs_share_token ON dcfs(share_token)") === TRUE) {
        echo "✓ Added index: idx_dcfs_share_token\n";
    } else {
        echo "✗ Failed to add idx_dcfs_share_token: " . $conn->error . "\n";
    }
}

$conn->close();
echo "\nSetup complete!\n";
