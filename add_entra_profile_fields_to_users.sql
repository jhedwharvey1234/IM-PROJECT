ALTER TABLE users
    ADD COLUMN entra_display_name VARCHAR(191) NULL AFTER entra_object_id,
    ADD COLUMN entra_user_principal_name VARCHAR(191) NULL AFTER entra_display_name,
    ADD COLUMN entra_given_name VARCHAR(100) NULL AFTER entra_user_principal_name,
    ADD COLUMN entra_surname VARCHAR(100) NULL AFTER entra_given_name,
    ADD COLUMN entra_job_title VARCHAR(191) NULL AFTER entra_surname,
    ADD COLUMN entra_department VARCHAR(191) NULL AFTER entra_job_title,
    ADD COLUMN entra_office_location VARCHAR(191) NULL AFTER entra_department,
    ADD COLUMN entra_mobile_phone VARCHAR(64) NULL AFTER entra_office_location,
    ADD COLUMN entra_business_phones TEXT NULL AFTER entra_mobile_phone;
