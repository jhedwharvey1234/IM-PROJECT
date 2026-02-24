<?php
// Quick diagnostic to check answer_text field status and sample data
require_once __DIR__ . '/app/Config/Database.php';

// Create database connection
$config = new \Config\Database();
$dbConfig = $config->default;

$mysqli = new mysqli(
    $dbConfig['hostname'],
    $dbConfig['username'],
    $dbConfig['password'],
    $dbConfig['database']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check answer_text Field Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; padding: 10px; border: 1px solid green; background: #e8f5e9; margin: 10px 0; }
        .error { color: red; padding: 10px; border: 1px solid red; background: #ffebee; margin: 10px 0; }
        .info { color: blue; padding: 10px; border: 1px solid blue; background: #e3f2fd; margin: 10px 0; }
        table { border-collapse: collapse; margin: 20px 0; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background: #f2f2f2; }
        .wysiwyg-content img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
<h1>Diagnostic: answer_text Field Status</h1>
<?php

// Check field type
echo "<h2>Database Field Status</h2>";
$result = $mysqli->query("SHOW COLUMNS FROM dcf_response_answers WHERE Field = 'answer_text'");
$columnInfo = $result->fetch_assoc();

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
echo "<tr>";
echo "<td>{$columnInfo['Field']}</td>";
echo "<td><strong style='color:" . (strpos($columnInfo['Type'], 'mediumtext') !== false ? 'green' : 'red') . ";'>{$columnInfo['Type']}</strong></td>";
echo "<td>{$columnInfo['Null']}</td>";
echo "<td>{$columnInfo['Key']}</td>";
echo "<td>{$columnInfo['Default']}</td>";
echo "</tr>";
echo "</table>";

if (strpos($columnInfo['Type'], 'text') !== false && strpos($columnInfo['Type'], 'mediumtext') === false) {
    echo "<p style='color: red; font-weight: bold;'>⚠️ Field is still TEXT type (64KB limit) - Migration needed!</p>";
    echo "<p><a href='increase_answer_text_field.php'>Click here to run migration</a></p>";
} else {
    echo "<p style='color: green; font-weight: bold;'>✓ Field is MEDIUMTEXT (16MB capacity)</p>";
}

// Check sample WYSIWYG answers
echo "<h2>Sample WYSIWYG Answers (checking for truncation)</h2>";
$result = $mysqli->query("
    SELECT 
        ra.answer_text,
        LENGTH(ra.answer_text) as text_length,
        q.question_text,
        r.created_at
    FROM dcf_response_answers ra
    JOIN dcf_questions q ON ra.dcf_question_id = q.id
    JOIN dcf_responses r ON ra.dcf_response_id = r.id
    WHERE q.answer_type = 'wysiwyg'
    ORDER BY r.created_at DESC
    LIMIT 5
");

if (!$result || $result->num_rows === 0) {
    echo "<p>No WYSIWYG answers found in database.</p>";
} else {
    while ($answer = $result->fetch_object()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<p><strong>Question:</strong> " . htmlspecialchars($answer->question_text) . "</p>";
        echo "<p><strong>Length:</strong> {$answer->text_length} bytes</p>";
        echo "<p><strong>Submitted:</strong> {$answer->created_at}</p>";
        
        // Check if answer seems truncated (doesn't end with closing tag)
        $text = $answer->answer_text;
        $lastChars = substr($text, -50);
        
        // Check for img tags
        if (strpos($text, '<img') !== false) {
            echo "<p style='color: blue;'>✓ Contains image tags</p>";
            
            // Count complete vs incomplete img tags
            preg_match_all('/<img[^>]*>/', $text, $completeImgs);
            preg_match_all('/<img[^>]*$/', $text, $incompleteImgs);
            
            echo "<p>Complete &lt;img&gt; tags: " . count($completeImgs[0]) . "</p>";
            if (count($incompleteImgs[0]) > 0) {
                echo "<p style='color: red; font-weight: bold;'>⚠️ TRUNCATED: Found incomplete &lt;img&gt; tag at end!</p>";
            }
        }
        
        echo "<details><summary>View HTML (first 500 chars)</summary>";
        echo "<pre>" . htmlspecialchars(substr($text, 0, 500)) . "...</pre>";
        echo "</details>";
        
        echo "<details><summary>View Rendered (as displayed in results)</summary>";
        echo "<div class='wysiwyg-content' style='background: #f5f5f5; padding: 10px;'>";
        echo $text;
        echo "</div>";
        echo "</details>";
        
        echo "</div>";
    }
}

$mysqli->close();
?>
</body>
</html>
