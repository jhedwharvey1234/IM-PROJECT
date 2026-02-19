<?php

namespace App\Controllers;

use App\Models\Dcf;

class DcfController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $data['dcfs'] = $dcfModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'DCF Management';

        return view('dcf/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create DCF';
        return view('dcf/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();

        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active');

        $data = [
            'name' => is_string($name) ? trim($name) : '',
            'description' => is_string($description) ? trim($description) : '',
            'is_active' => $isActive ? 1 : 0,
        ];

        if ($dcfModel->insert($data)) {
            return redirect()->to('/dcf')->with('success', 'DCF created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $dcfModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $data['dcf'] = $dcfModel->find($id);

        if (!$data['dcf']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("DCF $id not found");
        }

        $data['title'] = 'Edit DCF';
        return view('dcf/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $dcfModel->setValidationRule('name', str_replace('{id}', $id, $dcfModel->validationRules['name']));

        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active');

        $data = [
            'name' => is_string($name) ? trim($name) : '',
            'description' => is_string($description) ? trim($description) : '',
            'is_active' => $isActive ? 1 : 0,
        ];

        if ($dcfModel->update($id, $data)) {
            return redirect()->to('/dcf')->with('success', 'DCF updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $dcfModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();

        if ($dcfModel->delete($id)) {
            return redirect()->to('/dcf')->with('success', 'DCF deleted successfully');
        }

        return redirect()->to('/dcf')->with('error', 'Failed to delete DCF');
    }
}
