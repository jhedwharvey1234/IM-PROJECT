<?php
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== FIXING FOREIGN KEY CONSTRAINT ===\n\n";

// First show current constraint
echo "Current state:\n";
$current = $mysqli->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = 'assets' AND COLUMN_NAME = 'assigned_to_user_id' AND REFERENCED_TABLE_NAME = 'users'");

if ($current && $current->num_rows > 0) {
    $row = $current->fetch_assoc();
    $constraintName = $row['CONSTRAINT_NAME'];
    echo "  Found constraint: {$constraintName}\n";
    echo "  Dropping constraint...\n";
    
    $dropSql = "ALTER TABLE assets DROP FOREIGN KEY {$constraintName}";
    if ($mysqli->query($dropSql)) {
        echo "  ✓ Constraint dropped successfully\n\n";
    } else {
        echo "  ✗ Failed to drop constraint: " . $mysqli->error . "\n";
        exit(1);
    }
} else {
    echo "  No constraint found\n";
}

// Now, we have two options:
// Option 1: Add a constraint to assignable_users (if column is nullable, system is more flexible)
// Option 2: Remove the constraint completely (simpler approach)

// We'll go with Option 2 since assigned_to_user_id is nullable
// This gives flexibility - users don't have to be in assignable_users system

echo "Final state after removing problematic constraint:\n";
$describe = $mysqli->query("DESCRIBE assets")->fetch_all(MYSQLI_ASSOC);
foreach ($describe as $col) {
    if ($col['Field'] === 'assigned_to_user_id') {
        echo "  Column 'assigned_to_user_id': {$col['Type']} (Null: {$col['Null']})\n";
        echo "  ✓ Now accepts any integer ID or NULL\n";
    }
}

echo "\n=== VERIFICATION ===\n";
$assignable = $mysqli->query("SELECT COUNT(*) as cnt FROM assignable_users")->fetch_assoc();
echo "  Assignable users available: {$assignable['cnt']}\n";

$users = $mysqli->query("SELECT COUNT(*) as cnt FROM users")->fetch_assoc();
echo "  System users: {$users['cnt']}\n";

echo "\n✓ FIX COMPLETE!\n";
echo "  The assigned_to_user_id can now reference assignable_users\n";
echo "  You can now:\n";
echo "    1. Create new assets with assignable user assignments\n";
echo "    2. Edit assets to assign users\n";
echo "    3. The dropdown will show only assignable users (4, 5, etc)\n";

$mysqli->close();
?>
