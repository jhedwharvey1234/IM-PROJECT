ALTER TABLE users
    ADD COLUMN entra_user_type VARCHAR(50) NULL AFTER entra_user_principal_name,
    ADD COLUMN entra_company_name VARCHAR(191) NULL AFTER entra_job_title,
    ADD COLUMN entra_employee_id VARCHAR(100) NULL AFTER entra_department,
    ADD COLUMN entra_city VARCHAR(120) NULL AFTER entra_office_location,
    ADD COLUMN entra_state VARCHAR(120) NULL AFTER entra_city,
    ADD COLUMN entra_postal_code VARCHAR(30) NULL AFTER entra_state,
    ADD COLUMN entra_country VARCHAR(120) NULL AFTER entra_postal_code,
    ADD COLUMN entra_mail VARCHAR(191) NULL AFTER entra_mobile_phone,
    ADD COLUMN entra_manager_object_id VARCHAR(64) NULL AFTER entra_mail,
    ADD COLUMN entra_manager_display_name VARCHAR(191) NULL AFTER entra_manager_object_id,
    ADD COLUMN entra_manager_user_principal_name VARCHAR(191) NULL AFTER entra_manager_display_name,
    ADD COLUMN entra_manager_mail VARCHAR(191) NULL AFTER entra_manager_user_principal_name,
    ADD COLUMN entra_identities TEXT NULL AFTER entra_manager_mail;
