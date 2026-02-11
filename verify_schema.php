<?php
// Check database schema
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== DATABASE SCHEMA VERIFICATION ===\n\n";

$tables = ['assets', 'peripherals', 'assignable_users', 'users'];

foreach ($tables as $table) {
    // Check if table exists
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "✓ Table '$table' exists\n";
        
        // Get table structure
        $describe = $mysqli->query("DESCRIBE $table");
        $columns = [];
        while ($row = $describe->fetch_assoc()) {
            $columns[$row['Field']] = $row['Type'];
        }
        
        // Check for specific columns
        if ($table === 'assets' || $table === 'peripherals') {
            if (isset($columns['assigned_to_user_id'])) {
                echo "  ✓ Has 'assigned_to_user_id' column: {$columns['assigned_to_user_id']}\n";
            } else {
                echo "  ✗ MISSING 'assigned_to_user_id' column!\n";
            }
        }
    } else {
        echo "✗ Table '$table' MISSING\n";
    }
    echo "\n";
}

// Check data in assignable_users
echo "=== ASSIGNABLE USERS TABLE STATUS ===\n";
$count = $mysqli->query("SELECT COUNT(*) as cnt FROM assignable_users")->fetch_assoc();
echo "Records in assignable_users: " . $count['cnt'] . "\n";

if ($count['cnt'] == 0) {
    echo "\n⚠️ The assignable_users table is EMPTY!\n";
    echo "You need to:\n";
    echo "  1. Go to Settings > Assignable Users and create users\n";
    echo "  2. OR go to Users and sync system users to assignable users\n";
    echo "  3. Then you'll be able to assign them to assets/peripherals\n";
} else {
    echo "\n✓ Assignable users are available!\n";
    $users = $mysqli->query("SELECT id, full_name FROM assignable_users");
    while ($user = $users->fetch_assoc()) {
        echo "  - ID {$user['id']}: {$user['full_name']}\n";
    }
}

$mysqli->close();
?>
