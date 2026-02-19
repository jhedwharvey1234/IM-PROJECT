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
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_dcfs_name (name),
    INDEX idx_dcfs_is_active (is_active)
)";

if ($conn->query($sql) === TRUE) {
    echo "✓ dcfs table created successfully or already exists.\n";
} else {
    echo "✗ Error creating dcfs table: " . $conn->error . "\n";
}

$conn->close();
echo "\nSetup complete!\n";
