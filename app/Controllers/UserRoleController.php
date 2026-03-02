<?php

namespace App\Controllers;

use App\Models\UserRole;

class UserRoleController extends BaseController
{
    private function guard()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $model = new UserRole();
        $data['roles'] = $model->orderBy('role_name', 'ASC')->findAll();
        $data['title'] = 'User Role Management';

        return view('settings/user_roles/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $data['title'] = 'Create User Role';
        return view('settings/user_roles/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $model = new UserRole();
        $name = trim((string) $this->request->getPost('role_name'));
        $key = trim((string) $this->request->getPost('role_key'));
        $description = trim((string) $this->request->getPost('description'));

        $data = [
            'role_name' => $name,
            'role_key' => strtolower($key),
            'description' => $description !== '' ? $description : null,
        ];

        if ($model->insert($data)) {
            return redirect()->to('/settings/user-roles')->with('success', 'User role created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $model->errors());
    }

    public function edit($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $model = new UserRole();
        $data['role'] = $model->find($id);
        if (!$data['role']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User role $id not found");
        }

        $data['title'] = 'Edit User Role';
        return view('settings/user_roles/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $model = new UserRole();
        $existing = $model->find($id);
        if (!$existing) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User role $id not found");
        }

        $name = trim((string) $this->request->getPost('role_name'));
        $key = trim((string) $this->request->getPost('role_key'));
        $description = trim((string) $this->request->getPost('description'));

        $data = [
            'role_name' => $name,
            'role_key' => strtolower($key),
            'description' => $description !== '' ? $description : null,
        ];

        if ($model->update($id, $data)) {
            return redirect()->to('/settings/user-roles')->with('success', 'User role updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $model->errors());
    }

    public function delete($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $model = new UserRole();
        $role = $model->find($id);

        if (!$role) {
            return redirect()->to('/settings/user-roles')->with('error', 'User role not found');
        }

        if (in_array($role['role_key'], ['readonly', 'readandwrite', 'superadmin'], true)) {
            return redirect()->to('/settings/user-roles')->with('error', 'Default roles cannot be deleted');
        }

        if ($model->delete($id)) {
            return redirect()->to('/settings/user-roles')->with('success', 'User role deleted successfully');
        }

        return redirect()->to('/settings/user-roles')->with('error', 'Failed to delete user role');
    }
}
