-- ============================================================
-- ENHANCED APPLICATION MANAGEMENT SCHEMA
-- Supports 10 Categories of Application Information
-- ============================================================

-- 1. ALTER applications table to add new columns for all categories
ALTER TABLE applications ADD COLUMN IF NOT EXISTS app_category VARCHAR(100) COMMENT 'Core System, Support, Reporting, Customer-facing, etc.';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS app_type VARCHAR(100) COMMENT 'Web, Mobile, Desktop, API, Batch, SaaS';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS business_purpose TEXT COMMENT 'Business Purpose';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS lifecycle_stage VARCHAR(50) COMMENT 'Development, Active, Maintenance, Sunset';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS eol_date DATE COMMENT 'End-of-Life Date';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS replacement_system VARCHAR(255) COMMENT 'Replacement System Name/ID';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS upgrade_roadmap TEXT COMMENT 'Planned upgrades and timeline';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS last_major_upgrade DATE COMMENT 'Last Major Upgrade Date';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS data_classification VARCHAR(50) COMMENT 'Public, Internal, Confidential, Sensitive';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS personal_data_flag TINYINT(1) DEFAULT 0 COMMENT 'Contains Personal Data (0=No, 1=Yes)';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS authentication_type VARCHAR(100) COMMENT 'SSO, AD, OAuth, etc.';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS encryption_enabled TINYINT(1) DEFAULT 0 COMMENT 'Encryption enabled (0=No, 1=Yes)';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS last_security_review DATE COMMENT 'Last Security Review Date';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS availability_sla VARCHAR(50) COMMENT 'SLA percentage (e.g., 99.9%)';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS business_impact TEXT COMMENT 'Business Impact if Down';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS peak_users INT COMMENT 'Peak Concurrent Users';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS monitoring_tool VARCHAR(100) COMMENT 'Tool used for monitoring';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS annual_cost DECIMAL(12, 2) COMMENT 'Annual Cost/Budget';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS license_type VARCHAR(100) COMMENT 'License Type';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS license_expiry DATE COMMENT 'License Expiry Date';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS vendor_contract_ref VARCHAR(255) COMMENT 'Vendor Contract Reference';
ALTER TABLE applications ADD COLUMN IF NOT EXISTS cloud_subscription_details TEXT COMMENT 'Cloud Subscription Details (if SaaS)';

-- 2. Create application_contacts_extended table for detailed contacts by role
CREATE TABLE IF NOT EXISTS application_contacts_extended (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    contact_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    role VARCHAR(100) COMMENT 'Business Owner, Technical Owner, Support Team, Escalation Contact, etc.',
    department VARCHAR(100),
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    INDEX (application_id, role)
);

-- 3. Create application_environments table for detailed environment information
CREATE TABLE IF NOT EXISTS application_environments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    environment_type VARCHAR(50) COMMENT 'Production, UAT, QA, Development, DR',
    server_id INT,
    ip_address VARCHAR(50),
    hostname VARCHAR(100),
    url VARCHAR(255),
    version VARCHAR(20),
    data_center VARCHAR(100),
    environment_status VARCHAR(20) DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (server_id) REFERENCES servers(id) ON DELETE SET NULL,
    INDEX (application_id, environment_type)
);

-- 4. Create infrastructure_details table
CREATE TABLE IF NOT EXISTS infrastructure_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    server_type VARCHAR(100) COMMENT 'VM, Physical, Container',
    operating_system VARCHAR(100),
    os_version VARCHAR(50),
    cpu_cores INT,
    memory_gb INT,
    storage_gb INT,
    database_server VARCHAR(100),
    database_type VARCHAR(50) COMMENT 'MySQL, Oracle, PostgreSQL, MSSQL',
    storage_location VARCHAR(255),
    backup_location VARCHAR(255),
    dr_server_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (dr_server_id) REFERENCES servers(id) ON DELETE SET NULL
);

-- 5. Create compliance_security table
CREATE TABLE IF NOT EXISTS compliance_security (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    data_classification VARCHAR(50),
    personal_data_flag TINYINT(1) DEFAULT 0,
    compliance_requirements VARCHAR(255) COMMENT 'PCI, GDPR, HIPAA, ISO, etc.',
    authentication_type VARCHAR(100),
    encryption_enabled TINYINT(1) DEFAULT 0,
    encryption_method VARCHAR(100),
    vulnerability_scan_status VARCHAR(50) COMMENT 'Pending, Completed, Failed',
    last_vulnerability_scan DATE,
    last_security_review DATE,
    security_certifications VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- 6. Create application_dependencies table
CREATE TABLE IF NOT EXISTS application_dependencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    dependency_type VARCHAR(50) COMMENT 'Upstream, Downstream, Integration',
    dependent_system VARCHAR(150),
    dependent_app_id INT,
    api_endpoint VARCHAR(255),
    integration_type VARCHAR(100) COMMENT 'API, MQ, ETL, FILE, etc.',
    is_critical TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (dependent_app_id) REFERENCES applications(id) ON DELETE SET NULL,
    INDEX (application_id, dependency_type)
);

-- 7. Create application_vendors table for vendor/SaaS dependencies
CREATE TABLE IF NOT EXISTS application_vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    vendor_name VARCHAR(150),
    vendor_type VARCHAR(100) COMMENT 'SaaS, Middleware, API Provider, On-Premise, etc.',
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    support_hours VARCHAR(100),
    sla_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- 8. Create batch_jobs table for scheduled jobs/tasks
CREATE TABLE IF NOT EXISTS batch_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    job_name VARCHAR(100),
    job_description TEXT,
    schedule VARCHAR(100) COMMENT 'Cron expression or frequency',
    execution_duration VARCHAR(50),
    last_run DATETIME,
    next_run DATETIME,
    job_status VARCHAR(50) DEFAULT 'ENABLED',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- 9. Create licensing_info table
CREATE TABLE IF NOT EXISTS licensing_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    license_type VARCHAR(100),
    license_key VARCHAR(255),
    license_expiry DATE,
    vendor_name VARCHAR(150),
    vendor_contract_ref VARCHAR(255),
    annual_cost DECIMAL(12, 2),
    cost_center VARCHAR(50),
    purchase_date DATE,
    renewal_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- 10. Create operational_metrics table
CREATE TABLE IF NOT EXISTS operational_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    availability_sla VARCHAR(50) COMMENT '99.9%, 99.99%, etc.',
    criticality_level VARCHAR(50) COMMENT 'High, Medium, Low',
    business_impact TEXT,
    peak_users INT,
    peak_transactions INT,
    monitoring_tool VARCHAR(100),
    incident_history TEXT,
    last_incident DATE,
    mttr_target INT COMMENT 'Mean Time To Recover (minutes)',
    rto_target INT COMMENT 'Recovery Time Objective (minutes)',
    rpo_target INT COMMENT 'Recovery Point Objective (minutes)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- 11. Create technology_details table for more comprehensive tech stack info
CREATE TABLE IF NOT EXISTS technology_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    programming_language VARCHAR(100),
    framework VARCHAR(100),
    frontend_technology VARCHAR(100),
    database_type VARCHAR(100),
    middleware VARCHAR(255),
    container_technology VARCHAR(100) COMMENT 'Docker, Kubernetes, etc.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- Add indices for faster queries
CREATE INDEX IF NOT EXISTS idx_app_status ON applications(status_id);
CREATE INDEX IF NOT EXISTS idx_app_category ON applications(app_category);
CREATE INDEX IF NOT EXISTS idx_app_type ON applications(app_type);
CREATE INDEX IF NOT EXISTS idx_data_classification ON applications(data_classification);
CREATE INDEX IF NOT EXISTS idx_lifecycle_stage ON applications(lifecycle_stage);
