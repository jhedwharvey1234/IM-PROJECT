<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApplicationManagementTables extends Migration
{
    public function up()
    {
        // Create application_status table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'status_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('application_status');

        // Create applications table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'app_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true,
                'null'       => true,
            ],
            'app_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'description' => [
                'type'   => 'TEXT',
                'null'   => true,
            ],
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'owner_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'business_criticality' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'repository_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'production_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'version' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'status_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'date_created' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'last_updated' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type'   => 'TIMESTAMP',
                'default' => new \Raw('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'   => 'TIMESTAMP',
                'default' => new \Raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('status_id', 'application_status', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('applications');

        // Create servers table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'server_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'server_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVE',
            ],
            'created_at' => [
                'type'   => 'TIMESTAMP',
                'default' => new \Raw('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('servers');

        // Create technologies table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'technology_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('technologies');

        // Create application_technologies table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'technology_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('technology_id', 'technologies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_technologies');

        // Create environments table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'environment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'server_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'version' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVE',
            ],
            'created_at' => [
                'type'   => 'TIMESTAMP',
                'default' => new \Raw('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('server_id', 'servers', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('environments');

        // Create application_contacts table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'contact_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_contacts');

        // Create application_logs table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'performed_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'action_date' => [
                'type'   => 'TIMESTAMP',
                'default' => new \Raw('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('application_id', 'applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('application_logs');

        // Insert default application statuses
        $data = [
            ['status_name' => 'Active'],
            ['status_name' => 'Inactive'],
            ['status_name' => 'Development'],
            ['status_name' => 'Maintenance'],
            ['status_name' => 'Decommissioned'],
        ];
        $this->db->table('application_status')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('application_logs');
        $this->forge->dropTable('application_contacts');
        $this->forge->dropTable('environments');
        $this->forge->dropTable('application_technologies');
        $this->forge->dropTable('technologies');
        $this->forge->dropTable('servers');
        $this->forge->dropTable('applications');
        $this->forge->dropTable('application_status');
    }
}
