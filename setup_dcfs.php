<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'inventory_management';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS dcfs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    due_date DATE NOT NULL,
    department_id BIGINT(20) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_dcfs_due_date (due_date),
    INDEX idx_dcfs_department_id (department_id),
    CONSTRAINT fk_dcfs_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE RESTRICT ON UPDATE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "✓ dcfs table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcfs table: " . $conn->error . "\n";
}

$conn->close();
echo "\nSetup complete!\n";
