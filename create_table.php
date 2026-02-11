<?php
// Direct mysqli connection without requiring CodeIgniter
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "Connected to database: IM\n";

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `assignable_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL;

echo "Creating table...\n";

if ($mysqli->query($sql)) {
    echo "✓ Table 'assignable_users' created successfully!\n";
    
    // Check if table exists and get structure
    $result = $mysqli->query("SHOW TABLES LIKE 'assignable_users'");
    if ($result->num_rows > 0) {
        echo "✓ Table confirmed to exist\n";
        
        // Show table structure
        $describe = $mysqli->query("DESCRIBE assignable_users");
        echo "\nTable structure:\n";
        while ($row = $describe->fetch_assoc()) {
            echo "  - {$row['Field']} ({$row['Type']})\n";
        }
    }
    
    echo "\n✓ Setup complete! You can now:\n";
    echo "  1. Go to Settings > Assignable Users and add users\n";
    echo "  2. Or sync system users in User Management\n";
    echo "  3. Then assign them to assets and peripherals\n";
} else {
    echo "✗ Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
