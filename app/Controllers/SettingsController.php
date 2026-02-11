<?php

namespace App\Controllers;

use App\Models\Category;

class SettingsController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Settings';
        return view('settings/index', $data);
    }

    public function categories()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        $data['categories'] = $categoryModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Category Management';

        return view('settings/categories', $data);
    }

    public function saveCategory()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color') ?? '#0d6efd',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $data['id'] = $id;
            // Update existing category
            if ($categoryModel->update($id, $data)) {
                return redirect()->to('/settings/categories')->with('success', 'Category updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $categoryModel->errors());
            }
        } else {
            // Create new category
            if ($categoryModel->insert($data)) {
                return redirect()->to('/settings/categories')->with('success', 'Category created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $categoryModel->errors());
            }
        }
    }

    public function deleteCategory($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        
        if ($categoryModel->delete($id)) {
            return redirect()->to('/settings/categories')->with('success', 'Category deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete category');
        }
    }
}
