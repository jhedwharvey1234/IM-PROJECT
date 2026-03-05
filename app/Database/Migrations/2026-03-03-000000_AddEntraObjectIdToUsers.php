<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEntraObjectIdToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        if (!$this->db->fieldExists('entra_object_id', 'users')) {
            $this->forge->addColumn('users', [
                'entra_object_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'usertype',
                ],
            ]);

            $this->forge->addUniqueKey('entra_object_id', 'uq_users_entra_object_id');
            $this->forge->processIndexes('users');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users') && $this->db->fieldExists('entra_object_id', 'users')) {
            $this->forge->dropColumn('users', 'entra_object_id');
        }
    }
}
