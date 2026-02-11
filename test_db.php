<?php
require __DIR__ . '/vendor/autoload.php';

$config = new \Config\Database();
$db = $config->connect();

echo "=== DATABASE DIAGNOSIS ===\n\n";

// 1. Check assignable_users table
echo "1. Checking 'assignable_users' table...\n";
$result = $db->query("SHOW TABLES LIKE 'assignable_users'");
if ($result->getResult()) {
    echo "   ✓ Table EXISTS\n";
    
    // Show columns
    echo "\n   Columns:\n";
    $columns = $db->query("DESCRIBE assignable_users")->getResult();
    foreach ($columns as $col) {
        echo "     - {$col->Field} ({$col->Type})\n";
    }
    
    // Show data
    echo "\n   Data in table:\n";
    $data = $db->query("SELECT * FROM assignable_users")->getResult();
    if (count($data) > 0) {
        echo "     Found " . count($data) . " records\n";
        foreach ($data as $row) {
            echo "     - ID: {$row->id}, Name: {$row->full_name}\n";
        }
    } else {
        echo "     ✗ TABLE IS EMPTY!\n";
    }
} else {
    echo "   ✗ Table DOES NOT EXIST\n";
}

// 2. Check assets table structure for assigned_to_user_id
echo "\n2. Checking 'assets' table...\n";
$columns = $db->query("DESCRIBE assets")->getResult();
$has_user_id = false;
foreach ($columns as $col) {
    if ($col->Field === 'assigned_to_user_id') {
        echo "   ✓ Column 'assigned_to_user_id' EXISTS ({$col->Type})\n";
        $has_user_id = true;
        break;
    }
}
if (!$has_user_id) {
    echo "   ✗ Column 'assigned_to_user_id' MISSING\n";
}

// 3. Check test query
echo "\n3. Testing getAssignableUsers query...\n";
$result = $db->query("SELECT id, full_name FROM assignable_users ORDER BY full_name")->getResultArray();
echo "   Query returned " . count($result) . " results\n";

echo "\n=== END DIAGNOSIS ===\n";
?>
