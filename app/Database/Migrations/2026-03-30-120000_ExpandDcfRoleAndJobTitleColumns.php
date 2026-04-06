<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandDcfRoleAndJobTitleColumns extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('dcf_parts') && $this->db->fieldExists('role_key', 'dcf_parts')) {
            $this->forge->modifyColumn('dcf_parts', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => 'all',
                    'null' => false,
                ],
            ]);
        }

        if ($this->db->tableExists('dcf_questions') && $this->db->fieldExists('role_key', 'dcf_questions')) {
            $this->forge->modifyColumn('dcf_questions', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
            ]);
        }

        if ($this->db->tableExists('dcf_responses') && $this->db->fieldExists('respondent_role', 'dcf_responses')) {
            $this->forge->modifyColumn('dcf_responses', [
                'respondent_role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('dcf_parts') && $this->db->fieldExists('role_key', 'dcf_parts')) {
            $this->forge->modifyColumn('dcf_parts', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'all',
                    'null' => false,
                ],
            ]);
        }

        if ($this->db->tableExists('dcf_questions') && $this->db->fieldExists('role_key', 'dcf_questions')) {
            $this->forge->modifyColumn('dcf_questions', [
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
            ]);
        }

        if ($this->db->tableExists('dcf_responses') && $this->db->fieldExists('respondent_role', 'dcf_responses')) {
            $this->forge->modifyColumn('dcf_responses', [
                'respondent_role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
            ]);
        }
    }
}
