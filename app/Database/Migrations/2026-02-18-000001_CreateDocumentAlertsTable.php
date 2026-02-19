<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentAlertsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'document_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'alert_date' => [
                'type'    => 'DATE',
                'null'    => false,
            ],
            'alert_time' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_notified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('document_id', 'documents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('document_alerts');
    }

    public function down()
    {
        $this->forge->dropTable('document_alerts', true);
    }
}
