<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function store()
    {
        $userModel = new User();

        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => $this->request->getPost('password'),
            'usertype' => 'readonly',
        ];

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
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
}