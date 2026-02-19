-- Create application_related_data table
CREATE TABLE IF NOT EXISTS application_related_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    link VARCHAR(255),
    description TEXT,
    relation VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    INDEX idx_related_app (application_id),
    INDEX idx_related_relation (relation)
);
