<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntraAccountStatusFieldsToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        $fields = [
            'entra_account_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'after' => 'entra_user_principal_name',
            ],
            'entra_last_password_change_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'entra_account_enabled',
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
            'entra_account_enabled',
            'entra_last_password_change_at',
        ];

        foreach ($dropFields as $fieldName) {
            if ($db->fieldExists($fieldName, 'users')) {
                $this->forge->dropColumn('users', $fieldName);
            }
        }
    }
}
