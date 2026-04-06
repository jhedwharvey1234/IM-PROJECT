<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli('localhost', 'root', '', 'im');
    $conn->set_charset('utf8mb4');

    $queries = [
        "ALTER TABLE dcf_parts MODIFY COLUMN role_key VARCHAR(255) NOT NULL DEFAULT 'all'",
        "ALTER TABLE dcf_questions MODIFY COLUMN role_key VARCHAR(255) NULL",
        "ALTER TABLE dcf_responses MODIFY COLUMN respondent_role VARCHAR(255) NULL",
    ];

    echo "Applying DCF role/job title column length fixes...\n\n";

    foreach ($queries as $sql) {
        echo "Running: {$sql}\n";
        $conn->query($sql);
        echo "✓ Success\n\n";
    }

    echo "All schema fixes applied successfully.\n";
    echo "Note: Existing truncated job-title values in old DCF records must be re-selected and re-saved in DCF Edit.\n";

    $conn->close();
} catch (Throwable $e) {
    http_response_code(500);
    echo "Schema fix failed: " . $e->getMessage() . "\n";
}
