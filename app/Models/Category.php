<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name',
        'description',
        'color',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'name' => 'required|is_unique[categories.name,id,{id}]|max_length[255]',
        'description' => 'permit_empty|max_length[1000]',
        'color' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'is_unique' => 'Category name must be unique',
            'max_length' => 'Category name cannot exceed 255 characters',
        ],
        'color' => [
            'required' => 'Color is required',
            'regex_match' => 'Color must be a valid hex color code (e.g., #0d6efd)',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all active categories
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get category by name
     */
    public function getByName($name)
    {
        return $this->where('name', $name)->first();
    }
}
