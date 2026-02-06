<?php

namespace App\Controllers;

use App\Models\Department;

class DepartmentController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();
        $data['departments'] = $departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['title'] = 'Departments';

        return view('settings/departments/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Department';
        return view('settings/departments/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();
        $departmentModel->setValidationRule('department_name', 'required|max_length[100]|is_unique[departments.department_name]');

        $data = [
            'department_name' => $this->request->getPost('department_name'),
        ];

        if ($departmentModel->insert($data)) {
            return redirect()->to('/settings/departments')->with('success', 'Department created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $departmentModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();
        $data['department'] = $departmentModel->find($id);

        if (!$data['department']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Department $id not found");
        }

        $data['title'] = 'Edit Department';
        return view('settings/departments/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();
        $departmentModel->setValidationRule('department_name', str_replace('{id}', $id, $departmentModel->validationRules['department_name']));

        $data = [
            'department_name' => $this->request->getPost('department_name'),
        ];

        if ($departmentModel->update($id, $data)) {
            return redirect()->to('/settings/departments')->with('success', 'Department updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $departmentModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();

        if ($departmentModel->delete($id)) {
            return redirect()->to('/settings/departments')->with('success', 'Department deleted successfully');
        }

        return redirect()->to('/settings/departments')->with('error', 'Failed to delete department');
    }
}
