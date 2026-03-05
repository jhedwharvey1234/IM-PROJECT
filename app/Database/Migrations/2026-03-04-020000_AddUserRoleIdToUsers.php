<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserRoleIdToUsers extends Migration
{
    private const BASE_USER_TYPES = ['superadmin', 'readandwrite', 'readonly'];

    public function up()
    {
        if (!$this->db->tableExists('users')) {
            return;
        }

        if (!$this->db->fieldExists('user_role_id', 'users')) {
            $this->forge->addColumn('users', [
                'user_role_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'usertype',
                ],
            ]);
        }

        if ($this->db->tableExists('user_roles')) {
            try {
                $this->db->query('CREATE INDEX idx_users_user_role_id ON users (user_role_id)');
            } catch (\Throwable $e) {
            }

            $this->backfillCustomRoleUsers();

            $dbPlatform = strtolower((string) $this->db->DBDriver);
            if (str_contains($dbPlatform, 'mysqli') || str_contains($dbPlatform, 'mysql')) {
                try {
                    $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_users_user_role_id FOREIGN KEY (user_role_id) REFERENCES user_roles(id) ON DELETE SET NULL ON UPDATE CASCADE');
                } catch (\Throwable $e) {
                }
            }
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('users') || !$this->db->fieldExists('user_role_id', 'users')) {
            return;
        }

        try {
            $this->db->query('ALTER TABLE users DROP FOREIGN KEY fk_users_user_role_id');
        } catch (\Throwable $e) {
        }

        try {
            $this->db->query('DROP INDEX idx_users_user_role_id ON users');
        } catch (\Throwable $e) {
        }

        $this->forge->dropColumn('users', 'user_role_id');
    }

    private function backfillCustomRoleUsers(): void
    {
        $userBuilder = $this->db->table('users');
        $rows = $userBuilder
            ->select('id, usertype')
            ->where('usertype IS NOT NULL')
            ->where('usertype !=', '')
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $userId = (int) ($row['id'] ?? 0);
            $roleKey = strtolower(trim((string) ($row['usertype'] ?? '')));

            if ($userId <= 0 || $roleKey === '' || in_array($roleKey, self::BASE_USER_TYPES, true)) {
                continue;
            }

            $userRoleId = $this->resolveOrCreateRoleId($roleKey);
            if ($userRoleId === null) {
                continue;
            }

            $userBuilder
                ->where('id', $userId)
                ->update([
                    'usertype' => 'readonly',
                    'user_role_id' => $userRoleId,
                ]);
        }
    }

    private function resolveOrCreateRoleId(string $roleKey): ?int
    {
        $roleBuilder = $this->db->table('user_roles');
        $existing = $roleBuilder->select('id')->where('role_key', $roleKey)->get()->getRowArray();
        if (!empty($existing['id'])) {
            return (int) $existing['id'];
        }

        $roleName = ucwords(str_replace('_', ' ', $roleKey));
        $inserted = $roleBuilder->insert([
            'role_name' => $roleName,
            'role_key' => $roleKey,
            'description' => 'Migrated from users.usertype',
        ]);

        if (!$inserted) {
            return null;
        }

        return (int) $this->db->insertID();
    }
}
