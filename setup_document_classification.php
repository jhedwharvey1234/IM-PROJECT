<?php

try {
    $connection = new mysqli('localhost', 'root', '', 'im');

    if ($connection->connect_error) {
        die('Connection failed: ' . $connection->connect_error);
    }

    echo "Starting migration for Document Category/Type Tables...\n\n";

    $queries = [
        'document_categories' => "CREATE TABLE IF NOT EXISTS document_categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            description TEXT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_document_categories_name (name)
        )",
        'document_types' => "CREATE TABLE IF NOT EXISTS document_types (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            document_category_id INT UNSIGNED NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_document_types_category FOREIGN KEY (document_category_id) REFERENCES document_categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
            UNIQUE KEY uniq_document_types_category_name (document_category_id, name)
        )",
    ];

    foreach ($queries as $table => $sql) {
        echo "Creating {$table} table...\n";
        $connection->query($sql);
        if ($connection->errno) {
            echo "✗ Error: " . $connection->error . "\n\n";
        } else {
            echo "✓ {$table} table created\n\n";
        }
    }

    echo "========================================\n";
    echo "✓ Document category/type tables created successfully!\n";
    echo "========================================\n";

    $connection->close();
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
