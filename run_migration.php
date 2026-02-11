<?php

try {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ci4');
    
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    // Read the SQL file
    $sql = file_get_contents('create_application_management_tables.sql');
    
    // Execute the SQL statements
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        
        echo "✓ All tables created successfully!";
    } else {
        echo "✗ Error executing SQL: " . $conn->error;
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
