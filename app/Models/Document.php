<?php

namespace App\Models;

use CodeIgniter\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'subject',
        'description',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|max_length[200]',
        'subject' => 'permit_empty|max_length[200]',
        'description' => 'permit_empty|string',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Document title is required.',
            'max_length' => 'Document title cannot exceed 200 characters.',
        ],
    ];

    public function getDocuments($limit = null, $offset = 0)
    {
        $offset = max(0, (int) $offset);

        $builder = $this->db->table('documents d')
            ->select('d.*, u.username as created_by_name')
            ->join('users u', 'u.id = d.created_by', 'left')
            ->orderBy('d.created_at', 'DESC');

        if ($limit && $limit > 0) {
            $builder->limit((int) $limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    public function countDocuments()
    {
        return $this->countAllResults();
    }

    public function getWithMeta($id)
    {
        return $this->db->table('documents d')
            ->select('d.*, u.username as created_by_name')
            ->join('users u', 'u.id = d.created_by', 'left')
            ->where('d.id', $id)
            ->get()
            ->getRowArray();
    }

    public function searchDocuments($keyword)
    {
        $searchTerm = '%' . $keyword . '%';

        return $this->db->table('documents d')
            ->select('d.*, u.username as created_by_name')
            ->join('users u', 'u.id = d.created_by', 'left')
            ->groupStart()
            ->like('d.title', $keyword)
            ->orLike('d.subject', $keyword)
            ->orLike('d.description', $keyword)
            ->orLike('u.username', $keyword)
            ->groupEnd()
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
