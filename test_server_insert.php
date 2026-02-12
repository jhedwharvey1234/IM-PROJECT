<?php
// Test server insert directly
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'im';

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("✗ Connection failed: " . $db->connect_error . "\n");
}

echo "✓ Connected to database\n\n";

// Test 1: Try inserting a simple server record directly
echo "TEST 1: Direct SQL Insert\n";
echo str_repeat("-", 50) . "\n";

$sql = "INSERT INTO servers (server_name, server_type, ip_address) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql);

$serverName = 'Test Server';
$serverType = 'Web Server';
$ipAddress = '192.168.1.100';

$stmt->bind_param('sss', $serverName, $serverType, $ipAddress);

if ($stmt->execute()) {
    echo "✓ Direct insert successful! ID: " . $stmt->insert_id . "\n";
    $lastId = $stmt->insert_id;
    
    // Verify the insert
    $result = $db->query("SELECT * FROM servers WHERE id = $lastId");
    $row = $result->fetch_assoc();
    echo "Inserted data:\n";
    print_r($row);
    
    // Clean up test data
    $db->query("DELETE FROM servers WHERE id = $lastId");
    echo "\n✓ Test data cleaned up\n";
} else {
    echo "✗ Insert failed: " . $stmt->error . "\n";
}

$stmt->close();

// Test 2: Check table structure
echo "\n\nTEST 2: Table Structure\n";
echo str_repeat("-", 50) . "\n";
$result = $db->query("DESCRIBE servers");
while ($row = $result->fetch_assoc()) {
    printf("%-20s %-20s %-8s %-5s %-15s %s\n", 
        $row['Field'], 
        $row['Type'], 
        $row['Null'], 
        $row['Key'], 
        $row['Default'] ?? 'NULL',
        $row['Extra']
    );
}

// Test 3: Check for any constraints or triggers
echo "\n\nTEST 3: Check Constraints\n";
echo str_repeat("-", 50) . "\n";
$result = $db->query("
    SELECT 
        CONSTRAINT_NAME, 
        CONSTRAINT_TYPE
    FROM information_schema.TABLE_CONSTRAINTS 
    WHERE TABLE_SCHEMA = 'im' 
    AND TABLE_NAME = 'servers'
");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Constraint: {$row['CONSTRAINT_NAME']} - Type: {$row['CONSTRAINT_TYPE']}\n";
    }
} else {
    echo "No special constraints found\n";
}

$db->close();

echo "\n\n✓ All tests completed!\n";
