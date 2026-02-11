<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetNote extends Model
{
    protected $table            = 'asset_notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['asset_id', 'user_id', 'note'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'asset_id' => 'required|integer',
        'note'     => 'required|string|min_length[1]|max_length[5000]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all notes for a specific asset with user information
     */
    public function getNotesForAsset($assetId)
    {
        return $this->select('asset_notes.*, users.username, users.email')
            ->join('users', 'users.id = asset_notes.user_id', 'left')
            ->where('asset_notes.asset_id', $assetId)
            ->orderBy('asset_notes.created_at', 'DESC')
            ->findAll();
    }
}
