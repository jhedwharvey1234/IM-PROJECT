<?php
// Run this script to add consent fields to dcf_responses table
// Access via: http://localhost/IM/setup_dcf_consent.php

require __DIR__ . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = mysqli_connect(
    $_ENV['database.default.hostname'],
    $_ENV['database.default.username'],
    $_ENV['database.default.password'],
    $_ENV['database.default.database']
);

if (!$db) {
    die('Database connection error: ' . mysqli_connect_error());
}

mysqli_report(MYSQLI_REPORT_OFF);

echo "<h3>Setting up DCF Consent Fields</h3>";

// Check if user_consent column exists
$checkColumn = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='dcf_responses' AND COLUMN_NAME='user_consent'";
$result = mysqli_query($db, $checkColumn);

if (mysqli_num_rows($result) === 0) {
    echo "<p>Adding user_consent column...</p>";
    $addConsent = "ALTER TABLE dcf_responses ADD COLUMN user_consent TINYINT(1) DEFAULT 0 AFTER respondent_email";
    if (mysqli_query($db, $addConsent)) {
        echo "✓ user_consent column added successfully.<br>";
    } else {
        echo "✗ Error adding user_consent: " . mysqli_error($db) . "<br>";
    }
} else {
    echo "✓ user_consent column already exists.<br>";
}

// Check if consent_timestamp column exists
$checkColumn2 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='dcf_responses' AND COLUMN_NAME='consent_timestamp'";
$result2 = mysqli_query($db, $checkColumn2);

if (mysqli_num_rows($result2) === 0) {
    echo "<p>Adding consent_timestamp column...</p>";
    $addTimestamp = "ALTER TABLE dcf_responses ADD COLUMN consent_timestamp DATETIME NULL AFTER user_consent";
    if (mysqli_query($db, $addTimestamp)) {
        echo "✓ consent_timestamp column added successfully.<br>";
    } else {
        echo "✗ Error adding consent_timestamp: " . mysqli_error($db) . "<br>";
    }
} else {
    echo "✓ consent_timestamp column already exists.<br>";
}

// Check if index exists
$checkIndex = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_NAME='dcf_responses' AND COLUMN_NAME='user_consent'";
$result3 = mysqli_query($db, $checkIndex);

if (mysqli_num_rows($result3) === 0) {
    echo "<p>Adding index on user_consent...</p>";
    $addIndex = "CREATE INDEX idx_dcf_responses_user_consent ON dcf_responses(user_consent)";
    if (mysqli_query($db, $addIndex)) {
        echo "✓ Index created successfully.<br>";
    } else {
        echo "✗ Error adding index: " . mysqli_error($db) . "<br>";
    }
} else {
    echo "✓ Index on user_consent already exists.<br>";
}

echo "<p><strong>Setup Complete!</strong> The DCF form is now ready with multi-page layout and consent tracking.</p>";

mysqli_close($db);
?>
