<?php
// Verify servers table structure

// Simple direct connection for XAMPP default
$host = 'localhost';
$user = 'root';
$pass = '';  // XAMPP default
$dbname = 'im';  // From your .env file

echo "Attempting to connect to database...\n";
echo "Host: $host\n";
echo "User: $user\n";
echo "Database: $dbname\n\n";

try {
    $db = new mysqli($host, $user, $pass, $dbname);

    if ($db->connect_error) {
        die("✗ Connection failed: " . $db->connect_error . "\n");
    }

    echo "✓ Database connection successful\n\n";

    // Check if servers table exists
    $result = $db->query("SHOW TABLES LIKE 'servers'");
    if ($result->num_rows > 0) {
        echo "✓ Table 'servers' exists\n\n";
        
        // Show table structure
        echo "Table Structure:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-20s %-20s %-8s %-5s %-15s %s\n", "Field", "Type", "Null", "Key", "Default", "Extra");
        echo str_repeat("-", 80) . "\n";
        $structure = $db->query("DESCRIBE servers");
        while ($row = $structure->fetch_assoc()) {
            printf("%-20s %-20s %-8s %-5s %-15s %s\n", 
                $row['Field'], 
                $row['Type'], 
                $row['Null'], 
                $row['Key'], 
                $row['Default'] ?? 'NULL',
                $row['Extra']
            );
        }
        
        echo str_repeat("-", 80) . "\n";
        
        // Check for any existing data
        $count = $db->query("SELECT COUNT(*) as cnt FROM servers")->fetch_assoc();
        echo "\nExisting records: " . $count['cnt'] . "\n";
        
        // Show sample data if any
        if ($count['cnt'] > 0) {
            echo "\nSample data:\n";
            $data = $db->query("SELECT * FROM servers LIMIT 3");
            while ($row = $data->fetch_assoc()) {
                print_r($row);
            }
        }
        
    } else {
        echo "✗ Table 'servers' does NOT exist!\n\n";
        echo "You need to create it first. Run one of:\n";
        echo "1. Using MySQL command:\n";
        echo "   C:\\xampp\\mysql\\bin\\mysql.exe -u root ci4 < create_application_management_tables.sql\n\n";
        echo "2. Or visit http://localhost/phpmyadmin and import the SQL file\n";
    }

    $db->close();
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
