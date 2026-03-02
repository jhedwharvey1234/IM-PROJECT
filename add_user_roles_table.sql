-- Create user_roles table and seed default roles
CREATE TABLE IF NOT EXISTS user_roles (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(100) NOT NULL,
    role_key VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_roles_name (role_name),
    UNIQUE KEY uq_user_roles_key (role_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO user_roles (role_name, role_key, description)
SELECT 'Readonly', 'readonly', 'Read-only access'
WHERE NOT EXISTS (SELECT 1 FROM user_roles WHERE role_key = 'readonly');

INSERT INTO user_roles (role_name, role_key, description)
SELECT 'Read and Write', 'readandwrite', 'Can create and update data'
WHERE NOT EXISTS (SELECT 1 FROM user_roles WHERE role_key = 'readandwrite');

INSERT INTO user_roles (role_name, role_key, description)
SELECT 'Superadmin', 'superadmin', 'Full administrative access'
WHERE NOT EXISTS (SELECT 1 FROM user_roles WHERE role_key = 'superadmin');
