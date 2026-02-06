<?php

namespace App\Controllers;

use App\Models\Location;

class LocationController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $locationModel = new Location();
        $data['locations'] = $locationModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Locations';

        return view('settings/locations/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Location';
        return view('settings/locations/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $locationModel = new Location();
        $locationModel->setValidationRule('name', 'required|max_length[255]|is_unique[locations.name]');

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($locationModel->insert($data)) {
            return redirect()->to('/settings/locations')->with('success', 'Location created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $locationModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $locationModel = new Location();
        $data['location'] = $locationModel->find($id);

        if (!$data['location']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Location $id not found");
        }

        $data['title'] = 'Edit Location';
        return view('settings/locations/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $locationModel = new Location();
        $locationModel->setValidationRule('name', str_replace('{id}', $id, $locationModel->validationRules['name']));

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($locationModel->update($id, $data)) {
            return redirect()->to('/settings/locations')->with('success', 'Location updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $locationModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $locationModel = new Location();

        if ($locationModel->delete($id)) {
            return redirect()->to('/settings/locations')->with('success', 'Location deleted successfully');
        }

        return redirect()->to('/settings/locations')->with('error', 'Failed to delete location');
    }
}
