<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'inventory_management';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function columnExists(mysqli $conn, string $table, string $column): bool
{
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $sql = "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

function indexExists(mysqli $conn, string $table, string $index): bool
{
    $table = $conn->real_escape_string($table);
    $index = $conn->real_escape_string($index);
    $sql = "SHOW INDEX FROM `{$table}` WHERE Key_name = '{$index}'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

function constraintExists(mysqli $conn, string $table, string $constraint): bool
{
    $table = $conn->real_escape_string($table);
    $constraint = $conn->real_escape_string($constraint);
    $sql = "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table}' AND CONSTRAINT_NAME = '{$constraint}'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

if (!columnExists($conn, 'dcfs', 'title')) {
    if ($conn->query("ALTER TABLE dcfs ADD COLUMN title VARCHAR(255) NULL AFTER id") === TRUE) {
        echo "✓ Added column: title\n";
    } else {
        echo "✗ Failed to add title: " . $conn->error . "\n";
    }
}

if (!columnExists($conn, 'dcfs', 'due_date')) {
    if ($conn->query("ALTER TABLE dcfs ADD COLUMN due_date DATE NULL AFTER description") === TRUE) {
        echo "✓ Added column: due_date\n";
    } else {
        echo "✗ Failed to add due_date: " . $conn->error . "\n";
    }
}

if (!columnExists($conn, 'dcfs', 'department_id')) {
    if ($conn->query("ALTER TABLE dcfs ADD COLUMN department_id BIGINT(20) NULL AFTER due_date") === TRUE) {
        echo "✓ Added column: department_id\n";
    } else {
        echo "✗ Failed to add department_id: " . $conn->error . "\n";
    }
}

if ($conn->query("ALTER TABLE departments ENGINE=InnoDB") === TRUE) {
    echo "✓ Ensured departments engine is InnoDB\n";
} else {
    echo "✗ Failed to set departments engine: " . $conn->error . "\n";
}

if ($conn->query("ALTER TABLE dcfs ENGINE=InnoDB") === TRUE) {
    echo "✓ Ensured dcfs engine is InnoDB\n";
} else {
    echo "✗ Failed to set dcfs engine: " . $conn->error . "\n";
}

if (columnExists($conn, 'dcfs', 'department_id')) {
    if ($conn->query("ALTER TABLE dcfs MODIFY COLUMN department_id BIGINT(20) NULL") === TRUE) {
        echo "✓ Normalized column type: department_id BIGINT(20)\n";
    } else {
        echo "✗ Failed to normalize department_id type: " . $conn->error . "\n";
    }
}

if (!indexExists($conn, 'dcfs', 'idx_dcfs_due_date')) {
    if ($conn->query("CREATE INDEX idx_dcfs_due_date ON dcfs(due_date)") === TRUE) {
        echo "✓ Added index: idx_dcfs_due_date\n";
    } else {
        echo "✗ Failed to add idx_dcfs_due_date: " . $conn->error . "\n";
    }
}

if (!indexExists($conn, 'dcfs', 'idx_dcfs_department_id')) {
    if ($conn->query("CREATE INDEX idx_dcfs_department_id ON dcfs(department_id)") === TRUE) {
        echo "✓ Added index: idx_dcfs_department_id\n";
    } else {
        echo "✗ Failed to add idx_dcfs_department_id: " . $conn->error . "\n";
    }
}

if (!constraintExists($conn, 'dcfs', 'fk_dcfs_department')) {
    if ($conn->query("ALTER TABLE dcfs ADD CONSTRAINT fk_dcfs_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE RESTRICT ON UPDATE CASCADE") === TRUE) {
        echo "✓ Added foreign key: fk_dcfs_department\n";
    } else {
        echo "✗ Failed to add fk_dcfs_department: " . $conn->error . "\n";
    }
}

echo "\nSchema alteration complete.\n";
$conn->close();
