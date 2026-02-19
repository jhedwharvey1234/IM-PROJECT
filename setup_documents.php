<?php

try {
    $connection = new mysqli('localhost', 'root', '', 'im');

    if ($connection->connect_error) {
        die('Connection failed: ' . $connection->connect_error);
    }

    echo "Starting migration for Document Management Tables...\n\n";

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
        'documents' => "CREATE TABLE IF NOT EXISTS documents (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            subject VARCHAR(200) NULL,
            document_category_id INT UNSIGNED NULL,
            document_type_id INT UNSIGNED NULL,
            description TEXT NULL,
            details LONGTEXT NULL,
            created_by INT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_documents_category FOREIGN KEY (document_category_id) REFERENCES document_categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_documents_type FOREIGN KEY (document_type_id) REFERENCES document_types(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_documents_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )",
        'document_notes' => "CREATE TABLE IF NOT EXISTS document_notes (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            document_id INT UNSIGNED NOT NULL,
            user_id INT NULL,
            note TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_document_notes_document FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_document_notes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )",
        'document_files' => "CREATE TABLE IF NOT EXISTS document_files (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            document_id INT UNSIGNED NOT NULL,
            uploaded_by INT NULL,
            original_name VARCHAR(255) NOT NULL,
            stored_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            mime_type VARCHAR(100) NULL,
            file_size INT UNSIGNED NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_document_files_document FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_document_files_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
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
    echo "✓ Document management tables created successfully!\n";
    echo "========================================\n";

    $connection->close();
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
