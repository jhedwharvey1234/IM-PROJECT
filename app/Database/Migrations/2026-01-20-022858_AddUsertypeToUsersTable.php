<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsertypeToUsersTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('usertype', 'users')) {
            $this->forge->addColumn('users', [
                'usertype' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'readonly',
                ],
            ]);
        }

        // Set user id 1 as superadmin
        $this->db->table('users')->where('id', 1)->update(['usertype' => 'superadmin']);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'usertype');
    }
}
