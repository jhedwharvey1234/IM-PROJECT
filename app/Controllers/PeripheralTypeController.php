<?php

namespace App\Controllers;

use App\Models\PeripheralType;

class PeripheralTypeController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $typeModel = new PeripheralType();
        $data['types'] = $typeModel->orderBy('type_name', 'ASC')->findAll();
        $data['title'] = 'Peripheral Types';

        return view('settings/peripheral_types/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Peripheral Type';
        return view('settings/peripheral_types/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $typeModel = new PeripheralType();
        $typeModel->setValidationRule('type_name', 'required|max_length[100]|is_unique[peripheral_types.type_name]');

        $data = [
            'type_name' => $this->request->getPost('type_name'),
        ];

        if ($typeModel->insert($data)) {
            return redirect()->to('/settings/peripheral-types')->with('success', 'Peripheral type created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $typeModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $typeModel = new PeripheralType();
        $data['type'] = $typeModel->find($id);

        if (!$data['type']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral type $id not found");
        }

        $data['title'] = 'Edit Peripheral Type';
        return view('settings/peripheral_types/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $typeModel = new PeripheralType();
        $typeModel->setValidationRule('type_name', str_replace('{id}', $id, $typeModel->validationRules['type_name']));

        $data = [
            'type_name' => $this->request->getPost('type_name'),
        ];

        if ($typeModel->update($id, $data)) {
            return redirect()->to('/settings/peripheral-types')->with('success', 'Peripheral type updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $typeModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $typeModel = new PeripheralType();

        if ($typeModel->delete($id)) {
            return redirect()->to('/settings/peripheral-types')->with('success', 'Peripheral type deleted successfully');
        }

        return redirect()->to('/settings/peripheral-types')->with('error', 'Failed to delete peripheral type');
    }
}
