CREATE TABLE IF NOT EXISTS document_alerts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_id INT UNSIGNED NOT NULL,
    alert_date DATE NOT NULL,
    alert_time TIME NULL,
    description TEXT NULL,
    is_notified TINYINT(1) DEFAULT 0,
    created_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_document_alerts_document FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE ON UPDATE CASCADE
);
