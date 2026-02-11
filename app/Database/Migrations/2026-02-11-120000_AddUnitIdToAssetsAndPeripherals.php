<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitIdToAssetsAndPeripherals extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('unit_id', 'assets')) {
            $this->forge->addColumn('assets', [
                'unit_id' => [
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => true,
                ],
            ]);
        }

        if (!$this->db->fieldExists('unit_id', 'peripherals')) {
            $this->forge->addColumn('peripherals', [
                'unit_id' => [
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('unit_id', 'assets')) {
            $this->forge->dropColumn('assets', 'unit_id');
        }

        if ($this->db->fieldExists('unit_id', 'peripherals')) {
            $this->forge->dropColumn('peripherals', 'unit_id');
        }
    }
}