<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntraProfileFieldsToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'entra_display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
                'after'      => 'entra_object_id',
            ],
            'entra_user_principal_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
                'after'      => 'entra_display_name',
            ],
            'entra_given_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'entra_user_principal_name',
            ],
            'entra_surname' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'entra_given_name',
            ],
            'entra_job_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
                'after'      => 'entra_surname',
            ],
            'entra_department' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
                'after'      => 'entra_job_title',
            ],
            'entra_office_location' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
                'after'      => 'entra_department',
            ],
            'entra_mobile_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'entra_office_location',
            ],
            'entra_business_phones' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'entra_mobile_phone',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'entra_display_name',
            'entra_user_principal_name',
            'entra_given_name',
            'entra_surname',
            'entra_job_title',
            'entra_department',
            'entra_office_location',
            'entra_mobile_phone',
            'entra_business_phones',
        ]);
    }
}
