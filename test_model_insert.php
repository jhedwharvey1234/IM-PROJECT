<?php
// Test CodeIgniter Model Insert
require __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsConfig = new Config\Paths();
require_once $pathsConfig->systemDirectory . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

echo "Testing Server Model Insert\n";
echo str_repeat("=", 50) . "\n\n";

// Load the model
$serverModel = new \App\Models\Server();

// Test 1: Empty data array
echo "TEST 1: Empty data array\n";
echo str_repeat("-", 50) . "\n";
$data1 = [];
try {
    $result = $serverModel->insert($data1);
    if ($result) {
        echo "✓ Insert successful (unexpected!)\n";
    } else {
        echo "✗ Insert failed\n";
        echo "Errors: " . json_encode($serverModel->errors()) . "\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

// Test 2: Data with only server_name
echo "\n\nTEST 2: Data with only server_name\n";
echo str_repeat("-", 50) . "\n";
$data2 = ['server_name' => 'Test Server 2'];
try {
    $result = $serverModel->insert($data2);
    if ($result) {
        echo "✓ Insert successful! ID: $result\n";
        // Clean up
        $serverModel->delete($result);
        echo "✓ Test data cleaned up\n";
    } else {
        echo "✗ Insert failed\n";
        echo "Errors: " . json_encode($serverModel->errors()) . "\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test 3: Full data array
echo "\n\nTEST 3: Full data with all fields\n";
echo str_repeat("-", 50) . "\n";
$data3 = [
    'server_name' => 'Test Server 3',
    'server_type' => 'Web Server',
    'ip_address' => '192.168.1.200'
];
try {
    $result = $serverModel->insert($data3);
    if ($result) {
        echo "✓ Insert successful! ID: $result\n";
        // Clean up
        $serverModel->delete($result);
        echo "✓ Test data cleaned up\n";
    } else {
        echo "✗ Insert failed\n";
        echo "Errors: " . json_encode($serverModel->errors()) . "\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test 4: Test with array that might cause TypeError
echo "\n\nTEST 4: Array with potential issue\n";
echo str_repeat("-", 50) . "\n";
$data4 = [
    0 => 'value',  // Numeric key - this would cause TypeError!
    'server_name' => 'Test Server 4'
];
try {
    echo "Attempting insert with numeric key...\n";
    $result = $serverModel->insert($data4);
    if ($result) {
        echo "✓ Insert successful (unexpected!)\n";
        $serverModel->delete($result);
    } else {
        echo "✗ Insert failed\n";
        echo "Errors: " . json_encode($serverModel->errors()) . "\n";
    }
} catch (TypeError $e) {
    echo "✓ TypeError caught (expected): " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "✗ Other Exception: " . $e->getMessage() . "\n";
}

echo "\n\n✓ All model tests completed!\n";
