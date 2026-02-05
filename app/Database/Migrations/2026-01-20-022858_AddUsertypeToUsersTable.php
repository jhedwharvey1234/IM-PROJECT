<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsertypeToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'usertype' => [
                'type' => 'ENUM',
                'constraint' => ['readandwrite', 'readonly', 'superadmin'],
                
            ],
        ]);

        // Set user id 1 as superadmin
        $this->db->table('users')->where('id', 1)->update(['usertype' => 'superadmin']);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'usertype');
    }
}
