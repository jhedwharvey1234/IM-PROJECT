<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'inventory_management';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    source_type VARCHAR(100) NULL,
    source_id INT UNSIGNED NULL,
    related_url VARCHAR(500) NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_source_notification (source_type, source_id),
    INDEX idx_notifications_is_read (is_read),
    INDEX idx_notifications_created_at (created_at)
)";

if ($conn->query($sql) === TRUE) {
    echo "✓ notifications table created successfully or already exists.\n";
} else {
    echo "✗ Error creating notifications table: " . $conn->error . "\n";
}

$conn->close();
echo "\nSetup complete!\n";
