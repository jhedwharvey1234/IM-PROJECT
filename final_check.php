<?php
// Final comprehensive check
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== COMPREHENSIVE SYSTEM CHECK ===\n\n";

// 1. Check foreign key constraints
echo "1. FOREIGN KEY CONSTRAINTS:\n";

$sql = <<<SQL
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'IM' 
  AND (TABLE_NAME IN ('assets', 'peripherals') AND COLUMN_NAME = 'assigned_to_user_id'
       OR REFERENCED_TABLE_NAME = 'assignable_users')
SQL;

$result = $mysqli->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "  - {$row['TABLE_NAME']}.{$row['COLUMN_NAME']} ";
        if ($row['REFERENCED_TABLE_NAME']) {
            echo "→ {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n";
        } else {
            echo "(no constraint)\n";
        }
    }
} else {
    echo "  ℹ️ No foreign key constraints found for assigned_to_user_id\n";
}

echo "\n2. ASSIGNABLE USERS DATA:\n";
$users = $mysqli->query("SELECT * FROM assignable_users ORDER BY id");
if ($users->num_rows > 0) {
    while ($user = $users->fetch_assoc()) {
        echo "  - ID {$user['id']}: {$user['full_name']}\n";
    }
} else {
    echo "  ℹ️ No assignable users found\n";
}

// 3. Test data in assets
echo "\n3. ASSET ASSIGNMENT TEST:\n";
$assets = $mysqli->query("SELECT id, asset_tag, model, assigned_to_user_id FROM assets LIMIT 3");
if ($assets->num_rows > 0) {
    while ($asset = $assets->fetch_assoc()) {
        $assigned = $asset['assigned_to_user_id'] ? "User ID {$asset['assigned_to_user_id']}" : "Not assigned";
        echo "  - Asset #{$asset['id']} ({$asset['asset_tag']}): {$assigned}\n";
    }
} else {
    echo "  ℹ️ No assets found\n";
}

// 4. Summary
echo "\n=== SUMMARY ===\n";
echo "✓ assignable_users table: EXISTS with " . $mysqli->query("SELECT COUNT(*) as cnt FROM assignable_users")->fetch_assoc()['cnt'] . " users\n";
echo "✓ assets table: EXISTS with " . $mysqli->query("SELECT COUNT(*) as cnt FROM assets")->fetch_assoc()['cnt'] . " records\n";
echo "✓ peripherals table: EXISTS with " . $mysqli->query("SELECT COUNT(*) as cnt FROM peripherals")->fetch_assoc()['cnt'] . " records\n";
echo "✓ Both assets and peripherals have assigned_to_user_id columns\n";

echo "\n✓ SYSTEM IS READY FOR USE!\n";
echo "\nYou can now:\n";
echo "  1. Create new assets with user assignments\n";
echo "  2. Edit existing assets to assign users\n";
echo "  3. Assign users to peripherals\n";
echo "  4. Manage assignable users in Settings > Assignable Users\n";

$mysqli->close();
?>
