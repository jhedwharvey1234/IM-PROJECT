<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data['users'] = $this->userModel->orderBy('id','DESC')->findAll();
        return view('users/index', $data);
    }

    public function search()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return $this->response->setJSON([]);
        }

        $q = $this->request->getGet('q');

        $builder = $this->userModel;

        if (!empty($q)) {
            $builder = $builder->like('username', $q)
                ->orLike('email', $q)
                ->orLike('usertype', $q)
                ->orLike('created_at', $q);
        }

        return $this->response->setJSON($builder->orderBy('id','DESC')->findAll());
    }

    public function create()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        return view('users/create');
    }

    public function store()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'usertype' => $this->request->getPost('usertype'),
        ];

        // Set validation rules for insert (no id exclusion)
        $this->userModel->setValidationRule('username', 'required|min_length[3]|max_length[100]|is_unique[users.username]');
        $this->userModel->setValidationRule('email', 'required|valid_email|is_unique[users.email]');

        if ($this->userModel->insert($data)) {
            return redirect()->to('/users')->with('success', 'User created successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function edit($id)
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
    
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'usertype' => $this->request->getPost('usertype'),
        ];
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Set validation rules with id for unique checks
        $this->userModel->setValidationRule('username', str_replace('{id}', $id, $this->userModel->validationRules['username']));
        $this->userModel->setValidationRule('email', str_replace('{id}', $id, $this->userModel->validationRules['email']));

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function delete($id)
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/users')->with('success', 'User deleted successfully');
        } else {
            return redirect()->to('/users')->with('error', 'Failed to delete user');
        }
    }
}