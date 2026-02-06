<?php

namespace App\Controllers;

use App\Models\AssignableUser;

class AssignableUserController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $userModel = new AssignableUser();
        $data['assignable_users'] = $userModel->orderBy('full_name', 'ASC')->findAll();
        $data['title'] = 'Assignable Users';

        return view('settings/assigned_users/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Assignable User';
        return view('settings/assigned_users/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $userModel = new AssignableUser();
        $userModel->setValidationRule('full_name', 'required|max_length[150]|is_unique[assignable_users.full_name]');

        $data = [
            'full_name' => $this->request->getPost('full_name'),
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/settings/assigned-users')->with('success', 'Assignable user created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $userModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $userModel = new AssignableUser();
        $data['assignable_user'] = $userModel->find($id);

        if (!$data['assignable_user']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Assignable user $id not found");
        }

        $data['title'] = 'Edit Assignable User';
        return view('settings/assigned_users/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $userModel = new AssignableUser();
        $userModel->setValidationRule('full_name', str_replace('{id}', $id, $userModel->validationRules['full_name']));

        $data = [
            'full_name' => $this->request->getPost('full_name'),
        ];

        if ($userModel->update($id, $data)) {
            return redirect()->to('/settings/assigned-users')->with('success', 'Assignable user updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $userModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $userModel = new AssignableUser();

        if ($userModel->delete($id)) {
            return redirect()->to('/settings/assigned-users')->with('success', 'Assignable user deleted successfully');
        }

        return redirect()->to('/settings/assigned-users')->with('error', 'Failed to delete assignable user');
    }
}
