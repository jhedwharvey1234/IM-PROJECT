<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntraDirectorySyncFieldsToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $fields = [
            'entra_user_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'entra_user_principal_name',
            ],
            'entra_company_name' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
                'after' => 'entra_job_title',
            ],
            'entra_employee_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'entra_department',
            ],
            'entra_city' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true,
                'after' => 'entra_office_location',
            ],
            'entra_state' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true,
                'after' => 'entra_city',
            ],
            'entra_postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'after' => 'entra_state',
            ],
            'entra_country' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => true,
                'after' => 'entra_postal_code',
            ],
            'entra_mail' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
                'after' => 'entra_mobile_phone',
            ],
            'entra_manager_object_id' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'entra_mail',
            ],
            'entra_manager_display_name' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
                'after' => 'entra_manager_object_id',
            ],
            'entra_manager_user_principal_name' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
                'after' => 'entra_manager_display_name',
            ],
            'entra_manager_mail' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
                'after' => 'entra_manager_user_principal_name',
            ],
            'entra_identities' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'entra_manager_mail',
            ],
        ];

        foreach ($fields as $fieldName => $definition) {
            if (!$db->fieldExists($fieldName, 'users')) {
                $this->forge->addColumn('users', [$fieldName => $definition]);
            }
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $dropFields = [
            'entra_user_type',
            'entra_company_name',
            'entra_employee_id',
            'entra_city',
            'entra_state',
            'entra_postal_code',
            'entra_country',
            'entra_mail',
            'entra_manager_object_id',
            'entra_manager_display_name',
            'entra_manager_user_principal_name',
            'entra_manager_mail',
            'entra_identities',
        ];

        foreach ($dropFields as $fieldName) {
            if ($db->fieldExists($fieldName, 'users')) {
                $this->forge->dropColumn('users', $fieldName);
            }
        }
    }
}
