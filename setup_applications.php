<?php

try {
    // Connect directly to MySQL
    $connection = new mysqli('localhost', 'root', '', 'im');
    
    if ($connection->connect_error) {
        die('Connection failed: ' . $connection->connect_error);
    }

    echo "Starting migration for Application Management Tables...\n\n";

    // Create application_status table
    echo "Creating application_status table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS application_status (
        id INT AUTO_INCREMENT PRIMARY KEY,
        status_name VARCHAR(50) UNIQUE NOT NULL
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ application_status table created\n\n";
    }

    // Create applications table
    echo "Creating applications table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_code VARCHAR(30) UNIQUE,
        app_name VARCHAR(150) NOT NULL,
        description TEXT,
        
        department_id BIGINT,
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
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ applications table created\n\n";
    }

    // Create servers table
    echo "Creating servers table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS servers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        server_name VARCHAR(100) NOT NULL,
        server_type VARCHAR(50),
        ip_address VARCHAR(50),
        location VARCHAR(100),
        status VARCHAR(20) DEFAULT 'ACTIVE',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ servers table created\n\n";
    }

    // Create technologies table
    echo "Creating technologies table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS technologies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        technology_name VARCHAR(100) UNIQUE NOT NULL
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ technologies table created\n\n";
    }

    // Create application_technologies table
    echo "Creating application_technologies table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS application_technologies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        application_id INT NOT NULL,
        technology_id INT NOT NULL,
        
        FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
        FOREIGN KEY (technology_id) REFERENCES technologies(id) ON DELETE CASCADE
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ application_technologies table created\n\n";
    }

    // Create environments table
    echo "Creating environments table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS environments (
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
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ environments table created\n\n";
    }

    // Create application_contacts table
    echo "Creating application_contacts table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS application_contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        application_id INT NOT NULL,
        contact_name VARCHAR(100),
        email VARCHAR(100),
        role VARCHAR(50),
        
        FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ application_contacts table created\n\n";
    }

    // Create application_logs table
    echo "Creating application_logs table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS application_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        application_id INT NOT NULL,
        action VARCHAR(100),
        performed_by VARCHAR(100),
        action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
    )";
    $connection->query($sql);
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ application_logs table created\n\n";
    }

    // Insert default application statuses
    echo "Inserting default application statuses...\n";
    $statuses = ['Active', 'Inactive', 'Development', 'Maintenance', 'Decommissioned'];
    
    foreach ($statuses as $status) {
        $escaped_status = $connection->real_escape_string($status);
        $sql = "INSERT IGNORE INTO application_status (status_name) VALUES ('$escaped_status')";
        $connection->query($sql);
    }
    
    if ($connection->errno) {
        echo "✗ Error: " . $connection->error . "\n";
    } else {
        echo "✓ Default statuses inserted\n\n";
    }

    echo "========================================\n";
    echo "✓ All tables created successfully!\n";
    echo "=======================================\n";

    $connection->close();

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
