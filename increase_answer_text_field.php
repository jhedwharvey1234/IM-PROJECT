<?php
/**
 * Migration Script: Increase answer_text field size for WYSIWYG content
 * 
 * This script changes the answer_text field from TEXT to MEDIUMTEXT
 * to support larger WYSIWYG content with multiple images.
 * 
 * Run this file once by accessing it in your browser:
 * http://localhost/IM/increase_answer_text_field.php
 */

// Simple database connection using CodeIgniter config
require_once __DIR__ . '/app/Config/Database.php';

// Create database connection
$config = new \Config\Database();
$dbConfig = $config->default;

// Connect to MySQL
$mysqli = new mysqli(
    $dbConfig['hostname'],
    $dbConfig['username'],
    $dbConfig['password'],
    $dbConfig['database']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Migration: Increase answer_text Field Size</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; padding: 10px; border: 1px solid green; background: #e8f5e9; margin: 10px 0; }
        .error { color: red; padding: 10px; border: 1px solid red; background: #ffebee; margin: 10px 0; }
        .info { color: blue; padding: 10px; border: 1px solid blue; background: #e3f2fd; margin: 10px 0; }
        table { border-collapse: collapse; margin: 20px 0; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Database Migration: Increase answer_text Field Size</h1>";

try {
    // Get current column info
    $result = $mysqli->query("SHOW COLUMNS FROM dcf_response_answers WHERE Field = 'answer_text'");
    $currentColumn = $result->fetch_assoc();
    
    if (!$currentColumn) {
        echo "<div class='error'><strong>Error:</strong> Column 'answer_text' not found in dcf_response_answers table.</div>";
    } else {
        echo "<h3>Current Column Info:</h3>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
        echo "<tr>";
        echo "<td>" . htmlspecialchars($currentColumn['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($currentColumn['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($currentColumn['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($currentColumn['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
        echo "</table>";
        
        $currentType = strtolower($currentColumn['Type']);
        
        if (strpos($currentType, 'mediumtext') !== false || strpos($currentType, 'longtext') !== false) {
            echo "<div class='info'><strong>Info:</strong> Column 'answer_text' is already " . strtoupper($currentColumn['Type']) . ". No migration needed.</div>";
        } else {
            // Perform the migration
            echo "<div class='info'><strong>Info:</strong> Upgrading from {$currentColumn['Type']} to MEDIUMTEXT...</div>";
            
            $sql = "ALTER TABLE dcf_response_answers MODIFY COLUMN answer_text MEDIUMTEXT NULL";
            if ($mysqli->query($sql)) {
                echo "<div class='success'><strong>Success!</strong> Column 'answer_text' has been upgraded to MEDIUMTEXT.</div>";
                echo "<div class='info'>
                    <strong>Field Capacity Comparison:</strong>
                    <ul>
                        <li><strong>TEXT:</strong> ~64 KB (65,535 bytes)</li>
                        <li><strong>MEDIUMTEXT:</strong> ~16 MB (16,777,215 bytes) ✓</li>
                        <li><strong>LONGTEXT:</strong> ~4 GB (4,294,967,295 bytes)</li>
                    </ul>
                    <p>MEDIUMTEXT is sufficient for WYSIWYG content with multiple images.</p>
                </div>";
                
                // Display updated structure
                $result = $mysqli->query("SHOW COLUMNS FROM dcf_response_answers WHERE Field = 'answer_text'");
                $updatedColumn = $result->fetch_assoc();
                
                echo "<h3>Updated Column Info:</h3>";
                echo "<table>";
                echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
                echo "<tr>";
                echo "<td>" . htmlspecialchars($updatedColumn['Field']) . "</td>";
                echo "<td><strong>" . htmlspecialchars($updatedColumn['Type']) . "</strong></td>";
                echo "<td>" . htmlspecialchars($updatedColumn['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($updatedColumn['Default'] ?? 'NULL') . "</td>";
                echo "</tr>";
                echo "</table>";
            } else {
                echo "<div class='error'><strong>Error:</strong> " . $mysqli->error . "</div>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}

$mysqli->close();

echo "<hr><p><a href='index.php'>Go Back to Home</a></p>";
echo "</body></html>";
