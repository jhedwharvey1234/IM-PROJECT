-- Application Management Tables

-- Create application_status table first (referenced by applications)
CREATE TABLE IF NOT EXISTS application_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) UNIQUE NOT NULL
);

-- Create applications table
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_code VARCHAR(30) UNIQUE,
    app_name VARCHAR(150) NOT NULL,
    description TEXT,
    
    department_id INT,
    owner_name VARCHAR(100),
    business_criticality VARCHAR(20),
    
    repository_url VARCHAR(255),
    production_url VARCHAR(255),
    
    version VARCHAR(20),
    status_id INT,
    
    date_created DATE,
    last_updated DATE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (status_id) REFERENCES application_status(id) ON DELETE SET NULL
);

-- Create servers table
CREATE TABLE IF NOT EXISTS servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    server_name VARCHAR(100) NOT NULL,
    server_type VARCHAR(50),
    ip_address VARCHAR(50),
    location VARCHAR(100),
    status VARCHAR(20) DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create technologies table
CREATE TABLE IF NOT EXISTS technologies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    technology_name VARCHAR(100) UNIQUE NOT NULL
);

-- Create application_technologies table
CREATE TABLE IF NOT EXISTS application_technologies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    technology_id INT NOT NULL,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (technology_id) REFERENCES technologies(id) ON DELETE CASCADE
);

-- Create environments table
CREATE TABLE IF NOT EXISTS environments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    environment_type VARCHAR(50),
    
    server_id INT,
    url VARCHAR(255),
    version VARCHAR(20),
    status VARCHAR(20) DEFAULT 'ACTIVE',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (server_id) REFERENCES servers(id) ON DELETE SET NULL
);

-- Create application_contacts table
CREATE TABLE IF NOT EXISTS application_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    contact_name VARCHAR(100),
    email VARCHAR(100),
    role VARCHAR(50),
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- Create application_logs table
CREATE TABLE IF NOT EXISTS application_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    action VARCHAR(100),
    performed_by VARCHAR(100),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- Insert default application statuses
INSERT IGNORE INTO application_status (status_name) VALUES
('Active'),
('Inactive'),
('Development'),
('Maintenance'),
('Decommissioned');
