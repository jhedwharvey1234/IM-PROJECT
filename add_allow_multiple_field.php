<?php
/**
 * Migration Script: Add allow_multiple field to dcf_questions table
 * 
 * This script adds the allow_multiple field which controls whether
 * multiple answers can be selected for questions with options.
 * 
 * Run this file once by accessing it in your browser:
 * http://localhost/IM/add_allow_multiple_field.php
 */

require_once __DIR__ . '/vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = APPPATH . 'Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require realpath($bootstrap) ?: $bootstrap;

$app = Config\Services::codeigniter();
$app->initialize();

// Database connection
$db = \Config\Database::connect();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Migration: Add allow_multiple field</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; padding: 10px; border: 1px solid green; background: #e8f5e9; }
        .error { color: red; padding: 10px; border: 1px solid red; background: #ffebee; }
        .info { color: blue; padding: 10px; border: 1px solid blue; background: #e3f2fd; }
    </style>
</head>
<body>
    <h1>Database Migration: Add allow_multiple Field</h1>";

try {
    // Check if column already exists
    $query = $db->query("SHOW COLUMNS FROM dcf_questions LIKE 'allow_multiple'");
    
    if ($query->getNumRows() > 0) {
        echo "<div class='info'><strong>Info:</strong> Column 'allow_multiple' already exists in dcf_questions table. No action needed.</div>";
    } else {
        // Add the column
        $sql = "ALTER TABLE dcf_questions 
                ADD COLUMN allow_multiple TINYINT(1) NOT NULL DEFAULT 0 
                AFTER answer_type";
        
        $db->query($sql);
        
        echo "<div class='success'><strong>Success!</strong> Column 'allow_multiple' has been added to dcf_questions table.</div>";
        echo "<div class='info'><strong>Info:</strong> Default behavior:
            <ul>
                <li>multiple_choice: allow_multiple = 0 (single selection, radio buttons)</li>
                <li>checkbox: allow_multiple = 1 (multiple selection)</li>
                <li>dropdown: allow_multiple = 0 (single selection)</li>
                <li>advance_checkbox: allow_multiple = 1 (multiple selection in grid)</li>
            </ul>
        </div>";
    }
    
    // Display current structure
    echo "<h3>Current dcf_questions Table Structure:</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    
    $columns = $db->query("SHOW COLUMNS FROM dcf_questions")->getResultArray();
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div class='error'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<hr><p><a href='javascript:history.back()'>Go Back</a> | <a href='" . site_url('dcf/create') . "'>Create DCF</a></p>";
echo "</body></html>";
