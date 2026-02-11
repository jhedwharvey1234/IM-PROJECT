<?php
// Load the CodeIgniter environment
$_SERVER['CI_ENVIRONMENT'] = $_SERVER['CI_ENVIRONMENT'] ?? 'development';

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load the necessary files
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Config/Constants.php';

// Initialize PHP settings
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Get database connection
$config = new \Config\Database();
$db = $config->connect('default');

echo "Creating assignable_users table...\n";

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `assignable_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL;

try {
    $db->query($sql);
    echo "✓ Table 'assignable_users' created successfully!\n";
    
    // Check table structure
    $result = $db->query("DESCRIBE assignable_users")->getResult();
    echo "\nTable structure:\n";
    foreach ($result as $row) {
        echo "  - {$row->Field} ({$row->Type})\n";
    }
    
    echo "\n✓ Setup complete! The assignable_users table is now ready to use.\n";
    echo "  You can now:\n";
    echo "  1. Go to Settings > Assignable Users and add users\n";
    echo "  2. Or sync system users in User Management\n";
    echo "  3. Then you'll be able to assign them to assets and peripherals\n";
    
} catch (\Throwable $e) {
    echo "✗ Error creating table: " . $e->getMessage() . "\n";
    exit(1);
}
?>
