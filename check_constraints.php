<?php
$mysqli = new mysqli('localhost', 'root', '', 'IM');

if ($mysqli->connect_error) {
    echo "Connection Error: " . $mysqli->connect_error . "\n";
    exit(1);
}

echo "=== ANALYZING FOREIGN KEY CONSTRAINTS ===\n\n";

// Get detailed constraint info
$sql = <<<SQL
SELECT 
    kcu.CONSTRAINT_NAME,
    kcu.TABLE_NAME,
    kcu.COLUMN_NAME,
    kcu.REFERENCED_TABLE_NAME,
    kcu.REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
WHERE kcu.TABLE_SCHEMA = 'IM'
  AND kcu.TABLE_NAME IN ('assets', 'peripherals')
  AND kcu.COLUMN_NAME = 'assigned_to_user_id'
  AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
SQL;

$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    echo "FOREIGN KEY CONSTRAINTS FOUND:\n\n";
    while ($row = $result->fetch_assoc()) {
        echo "  Table: {$row['TABLE_NAME']}\n";
        echo "  Column: {$row['COLUMN_NAME']}\n";
        echo "  References: {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n";
        echo "  Constraint Name: {$row['CONSTRAINT_NAME']}\n\n";
    }
} else {
    echo "No foreign key constraints found on assigned_to_user_id\n";
}

// Show the actual table structure
echo "=== ASSETS TABLE FULL STRUCTURE ===\n";
$describe = $mysqli->query("DESCRIBE assets");
while ($col = $describe->fetch_assoc()) {
    if ($col['Field'] === 'assigned_to_user_id') {
        echo "Column: assigned_to_user_id\n";
        echo "  Type: {$col['Type']}\n";
        echo "  Null: {$col['Null']}\n";
        echo "  Key: {$col['Key']}\n";
        echo "  Default: {$col['Default']}\n";
        echo "  Extra: {$col['Extra']}\n";
    }
}

$mysqli->close();
?>
