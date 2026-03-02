<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersUsertypeToVarchar extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('users') || !$this->db->fieldExists('usertype', 'users')) {
            return;
        }

        $this->db->query("ALTER TABLE users MODIFY COLUMN usertype VARCHAR(50) NOT NULL");
    }

    public function down()
    {
        if (!$this->db->tableExists('users') || !$this->db->fieldExists('usertype', 'users')) {
            return;
        }

        $this->db->query("ALTER TABLE users MODIFY COLUMN usertype ENUM('readandwrite','readonly','superadmin') NOT NULL");
    }
}
