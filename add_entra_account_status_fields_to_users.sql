ALTER TABLE users
    ADD COLUMN entra_account_enabled TINYINT(1) NULL AFTER entra_user_principal_name,
    ADD COLUMN entra_last_password_change_at DATETIME NULL AFTER entra_account_enabled;
