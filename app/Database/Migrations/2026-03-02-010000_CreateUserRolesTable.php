<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRolesTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('user_roles')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'role_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'role_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'description' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
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
            $this->forge->addUniqueKey('role_name');
            $this->forge->addUniqueKey('role_key');
            $this->forge->createTable('user_roles');
        }

        $this->seedDefaultRoles();
    }

    public function down()
    {
        if ($this->db->tableExists('user_roles')) {
            $this->forge->dropTable('user_roles');
        }
    }

    private function seedDefaultRoles(): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');

        $defaults = [
            ['role_name' => 'Readonly', 'role_key' => 'readonly', 'description' => 'Read-only access'],
            ['role_name' => 'Read and Write', 'role_key' => 'readandwrite', 'description' => 'Can create and update data'],
            ['role_name' => 'Superadmin', 'role_key' => 'superadmin', 'description' => 'Full administrative access'],
        ];

        foreach ($defaults as $role) {
            $exists = $builder->where('role_key', $role['role_key'])->countAllResults() > 0;
            if (!$exists) {
                $builder->insert($role);
            }
        }
    }
}
