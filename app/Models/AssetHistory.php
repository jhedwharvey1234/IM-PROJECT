<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetHistory extends Model
{
    protected $table = 'asset_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'asset_id',
        'user_id',
        'action',
        'field_name',
        'old_value',
        'new_value',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'asset_id' => 'required|integer',
        'action' => 'required|max_length[50]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get history for a specific asset with user information
     * 
     * @param int $assetId
     * @return array
     */
    public function getHistoryForAsset($assetId)
    {
        return $this->select('asset_history.*, users.username, users.email, users.usertype')
            ->join('users', 'users.id = asset_history.user_id', 'left')
            ->where('asset_history.asset_id', $assetId)
            ->orderBy('asset_history.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Log an asset action
     * 
     * @param int $assetId
     * @param string $action
     * @param string|null $description
     * @param string|null $fieldName
     * @param mixed|null $oldValue
     * @param mixed|null $newValue
     * @return bool
     */
    public function logAction($assetId, $action, $description = null, $fieldName = null, $oldValue = null, $newValue = null)
    {
        $data = [
            'asset_id' => $assetId,
            'user_id' => session()->get('user_id'),
            'action' => $action,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($data);
    }

    /**
     * Log multiple field changes at once
     * 
     * @param int $assetId
     * @param array $changes Array of changes with 'field', 'old', 'new' keys
     * @param string $action
     * @return bool
     */
    public function logChanges($assetId, $changes, $action = 'updated')
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        foreach ($changes as $change) {
            $data = [
                'asset_id' => $assetId,
                'user_id' => session()->get('user_id'),
                'action' => $action,
                'field_name' => $change['field'] ?? null,
                'old_value' => $change['old'] ?? null,
                'new_value' => $change['new'] ?? null,
                'description' => $change['description'] ?? null,
                'ip_address' => service('request')->getIPAddress(),
                'user_agent' => service('request')->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $builder->insert($data);
        }

        return true;
    }
}
