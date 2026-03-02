<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShareTokenToAssets extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('share_token', 'assets')) {
            $this->forge->addColumn('assets', [
                'share_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => true,
                    'after' => 'unit_id',
                ],
            ]);
        }

        $assets = $this->db->table('assets')->select('id, share_token')->get()->getResultArray();
        foreach ($assets as $asset) {
            $existing = (string) ($asset['share_token'] ?? '');
            if ($existing !== '') {
                continue;
            }

            $this->db->table('assets')
                ->where('id', (int) $asset['id'])
                ->update(['share_token' => $this->generateUniqueShareToken()]);
        }

        try {
            $this->db->query('CREATE UNIQUE INDEX idx_assets_share_token ON assets (share_token)');
        } catch (\Throwable $e) {
        }
    }

    public function down()
    {
        try {
            $this->db->query('DROP INDEX idx_assets_share_token ON assets');
        } catch (\Throwable $e) {
        }

        if ($this->db->fieldExists('share_token', 'assets')) {
            $this->forge->dropColumn('assets', 'share_token');
        }
    }

    private function generateUniqueShareToken(): string
    {
        do {
            $token = bin2hex(random_bytes(16));
            $exists = $this->db->table('assets')->where('share_token', $token)->countAllResults() > 0;
        } while ($exists);

        return $token;
    }
}
