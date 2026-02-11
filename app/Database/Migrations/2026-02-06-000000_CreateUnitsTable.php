<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => false,
                'auto_increment' => true,
            ],
            'unit_name' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
                'null' => false,
            ],
            'unit_type' => [
                'type' => 'ENUM',
                'constraint' => ['asset', 'peripheral', 'both'],
                'null' => false,
            ],
            'asset_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'peripheral_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('asset_id');
        $this->forge->addKey('peripheral_id');
        $this->forge->createTable('units');
    }

    public function down()
    {
        $this->forge->dropTable('units');
    }
}
