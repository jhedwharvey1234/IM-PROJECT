<?php
try {
    $conn = new mysqli('localhost', 'root', '', 'im');
    if ($conn->connect_error) {
        die('Connection failed');
    }
    
    $result = $conn->query('DESC departments');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . ' - ' . $row['Type'] . PHP_EOL;
        }
    }
    $conn->close();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
