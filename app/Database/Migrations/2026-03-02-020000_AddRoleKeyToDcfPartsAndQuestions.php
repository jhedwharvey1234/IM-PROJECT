<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleKeyToDcfPartsAndQuestions extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('dcf_parts') && !$this->db->fieldExists('role_key', 'dcf_parts')) {
            $this->forge->addColumn('dcf_parts', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'all',
                    'after' => 'description',
                ],
            ]);
        }

        if ($this->db->tableExists('dcf_questions') && !$this->db->fieldExists('role_key', 'dcf_questions')) {
            $this->forge->addColumn('dcf_questions', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'question_text',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('dcf_questions') && $this->db->fieldExists('role_key', 'dcf_questions')) {
            $this->forge->dropColumn('dcf_questions', 'role_key');
        }

        if ($this->db->tableExists('dcf_parts') && $this->db->fieldExists('role_key', 'dcf_parts')) {
            $this->forge->dropColumn('dcf_parts', 'role_key');
        }
    }
}
