CREATE TABLE IF NOT EXISTS document_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_document_categories_name (name)
);

CREATE TABLE IF NOT EXISTS document_types (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_document_types_category FOREIGN KEY (document_category_id) REFERENCES document_categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uniq_document_types_category_name (document_category_id, name)
);