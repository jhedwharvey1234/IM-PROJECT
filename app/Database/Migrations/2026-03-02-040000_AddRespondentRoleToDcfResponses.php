<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRespondentRoleToDcfResponses extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('dcf_responses')) {
            return;
        }

        if (!$this->db->fieldExists('respondent_role', 'dcf_responses')) {
            $this->forge->addColumn('dcf_responses', [
                'respondent_role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'respondent_email',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('dcf_responses') && $this->db->fieldExists('respondent_role', 'dcf_responses')) {
            $this->forge->dropColumn('dcf_responses', 'respondent_role');
        }
    }
}
