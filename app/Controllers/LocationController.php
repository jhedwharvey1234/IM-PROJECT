<?php

namespace App\Controllers;

use App\Models\Location;

class LocationController extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireAuthenticated()) {
            return $redirect;
        }

        $locationModel = new Location();
        $data['locations'] = $locationModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Locations';

        return view('settings/locations/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $data['title'] = 'Create Location';
        return view('settings/locations/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
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
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
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
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
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
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $locationModel = new Location();

        if ($locationModel->delete($id)) {
            return redirect()->to('/settings/locations')->with('success', 'Location deleted successfully');
        }

        return redirect()->to('/settings/locations')->with('error', 'Failed to delete location');
    }
}
