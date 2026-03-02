<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserRole;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        $data['userRoles'] = $this->getAvailableRoles();
        return view('auth/register', $data);
    }

    public function store()
    {
        $userModel = new User();

        $selectedRole = trim((string) $this->request->getPost('usertype'));

        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => $this->request->getPost('password'),
            'usertype' => $selectedRole,
        ];

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'usertype' => 'required|max_length[50]',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'Username already exists.',
            ],
            'email' => [
                'is_unique' => 'Email already exists.',
            ],
        ];

        if (!$this->validateData($data, $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (!$this->isValidRoleKey($selectedRole)) {
            return redirect()->back()->withInput()->with('errors', ['usertype' => 'Selected user role is invalid.']);
        }

        if ($userModel->insert($data)) {
            return redirect()->to(site_url('login'))->with('success', 'Registration successful. Please login.');
        } else {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }

    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $userModel = new User();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set('user_id', $user['id']);
            session()->set('username', $user['username']);
            session()->set('usertype', $user['usertype']);
            return redirect()->to(site_url('dashboard'));
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }

    private function getAvailableRoles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return [
                ['role_name' => 'Readonly', 'role_key' => 'readonly'],
                ['role_name' => 'Read and Write', 'role_key' => 'readandwrite'],
                ['role_name' => 'Superadmin', 'role_key' => 'superadmin'],
            ];
        }

        $roleModel = new UserRole();
        return $roleModel->orderBy('role_name', 'ASC')->findAll();
    }

    private function isValidRoleKey(string $roleKey): bool
    {
        $roles = $this->getAvailableRoles();
        $keys = array_column($roles, 'role_key');
        return in_array($roleKey, $keys, true);
    }
}