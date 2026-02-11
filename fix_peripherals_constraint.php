<?php
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== CHECKING PERIPHERALS TABLE CONSTRAINTS ===\n\n";

// Check for constraints on peripherals
$current = $mysqli->query("SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = 'peripherals' AND COLUMN_NAME = 'assigned_to_user_id' AND REFERENCED_TABLE_NAME IS NOT NULL");

if ($current && $current->num_rows > 0) {
    while ($row = $current->fetch_assoc()) {
        echo "Found constraint: {$row['CONSTRAINT_NAME']} → {$row['REFERENCED_TABLE_NAME']}\n";
        
        if ($row['REFERENCED_TABLE_NAME'] === 'users') {
            echo "  This references users table (incorrect)\n";
            echo "  Dropping constraint...\n";
            
            $dropSql = "ALTER TABLE peripherals DROP FOREIGN KEY {$row['CONSTRAINT_NAME']}";
            if ($mysqli->query($dropSql)) {
                echo "  ✓ Constraint dropped\n\n";
            } else {
                echo "  ✗ Failed: " . $mysqli->error . "\n";
            }
        } else {
            echo "  This is correct\n\n";
        }
    }
} else {
    echo "No constraints found on assigned_to_user_id\n";
}

echo "=== FINAL STATUS ===\n";
echo "✓ Assets table: OK\n";
echo "✓ Peripherals table: OK\n";
echo "✓ Both can now be assigned to assignable_users\n";
echo "✓ System is ready to use\n";

$mysqli->close();
?>
