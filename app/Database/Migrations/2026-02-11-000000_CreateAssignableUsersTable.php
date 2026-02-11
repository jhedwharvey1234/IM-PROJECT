<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignableUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('assignable_users');
    }

    public function down()
    {
        $this->forge->dropTable('assignable_users');
    }
}
