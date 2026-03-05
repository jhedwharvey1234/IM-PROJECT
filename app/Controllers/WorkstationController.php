<?php

namespace App\Controllers;

use App\Models\Location;
use App\Models\Workstation;

class WorkstationController extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireAuthenticated()) {
            return $redirect;
        }

        $workstationModel = new Workstation();
        $data['workstations'] = $workstationModel->orderBy('workstation_code', 'ASC')->findAll();
        $data['locations'] = $this->getLocationsMap();
        $data['title'] = 'Workstations';

        return view('settings/workstations/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $data['title'] = 'Create Workstation';
        $data['locations'] = $this->getLocationsMap();
        return view('settings/workstations/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $workstationModel = new Workstation();
        $workstationModel->setValidationRule('workstation_code', 'required|max_length[50]|is_unique[workstations.workstation_code]');

        $data = [
            'workstation_code' => $this->request->getPost('workstation_code'),
            'location_id' => $this->request->getPost('location_id') ?: null,
        ];

        if ($workstationModel->insert($data)) {
            return redirect()->to('/settings/workstations')->with('success', 'Workstation created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $workstationModel->errors());
    }

    public function edit($id)
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $workstationModel = new Workstation();
        $data['workstation'] = $workstationModel->find($id);

        if (!$data['workstation']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Workstation $id not found");
        }

        $data['title'] = 'Edit Workstation';
        $data['locations'] = $this->getLocationsMap();
        return view('settings/workstations/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $workstationModel = new Workstation();
        $workstationModel->setValidationRule('workstation_code', str_replace('{id}', $id, $workstationModel->validationRules['workstation_code']));

        $data = [
            'workstation_code' => $this->request->getPost('workstation_code'),
            'location_id' => $this->request->getPost('location_id') ?: null,
        ];

        if ($workstationModel->update($id, $data)) {
            return redirect()->to('/settings/workstations')->with('success', 'Workstation updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $workstationModel->errors());
    }

    public function delete($id)
    {
        if ($redirect = $this->requireFullAccess()) {
            return $redirect;
        }

        $workstationModel = new Workstation();

        if ($workstationModel->delete($id)) {
            return redirect()->to('/settings/workstations')->with('success', 'Workstation deleted successfully');
        }

        return redirect()->to('/settings/workstations')->with('error', 'Failed to delete workstation');
    }

    private function getLocationsMap()
    {
        $locationModel = new Location();
        $locations = $locationModel->orderBy('name', 'ASC')->findAll();
        $map = [];
        foreach ($locations as $location) {
            $map[$location['id']] = $location['name'];
        }
        return $map;
    }
}
