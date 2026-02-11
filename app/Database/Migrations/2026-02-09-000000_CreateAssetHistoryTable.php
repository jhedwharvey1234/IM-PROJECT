<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssetHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'asset_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'created, updated, deleted, status_changed, assigned, unassigned, etc.',
            ],
            'field_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Name of the field that was changed',
            ],
            'old_value' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Previous value before the change',
            ],
            'new_value' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'New value after the change',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Human-readable description of the change',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('asset_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('created_at');

        // Foreign keys
        $this->forge->addForeignKey('asset_id', 'assets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('asset_history');
    }

    public function down()
    {
        $this->forge->dropTable('asset_history');
    }
}
