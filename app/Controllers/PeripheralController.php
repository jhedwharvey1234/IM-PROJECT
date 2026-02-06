<?php

namespace App\Controllers;

use App\Models\Peripheral;

class PeripheralController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $peripheralModel = new Peripheral();

        $data['peripherals'] = $peripheralModel->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Manage Peripherals';
        $data['peripheral_types_map'] = $this->getPeripheralTypes();
        $data['locations'] = $this->getLocations();
        $data['departments'] = $this->getDepartments();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();
        $data['assets'] = $this->getAssets();

        return view('peripherals/index', $data);
    }

    public function search()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $peripheralModel = new Peripheral();
        $search = trim((string) $this->request->getGet('search'));
        $status = trim((string) $this->request->getGet('status'));
        $condition = trim((string) $this->request->getGet('condition_status'));
        $startDate = trim((string) $this->request->getGet('start_date'));
        $endDate = trim((string) $this->request->getGet('end_date'));
        $locationId = trim((string) $this->request->getGet('location_id'));
        $departmentId = trim((string) $this->request->getGet('department_id'));
        $workstationId = trim((string) $this->request->getGet('workstation_id'));
        $peripheralTypeId = trim((string) $this->request->getGet('peripheral_type_id'));
        $assignedToUserId = trim((string) $this->request->getGet('assigned_to_user_id'));

        $query = $peripheralModel;

        if ($search !== '') {
            $query = $query->groupStart()
                ->like('asset_tag', $search, 'both', null, true)
                ->orLike('serial_number', $search, 'both', null, true)
                ->orLike('brand', $search, 'both', null, true)
                ->orLike('model', $search, 'both', null, true)
                ->groupEnd();
        }

        if ($status !== '') {
            $query = $query->where('status', $status);
        }

        if ($condition !== '') {
            $query = $query->where('condition_status', $condition);
        }

        if ($startDate !== '') {
            $query = $query->where('purchase_date >=', $startDate . ' 00:00:00');
        }

        if ($endDate !== '') {
            $query = $query->where('purchase_date <=', $endDate . ' 23:59:59');
        }

        if ($locationId !== '') {
            $query = $query->where('location_id', $locationId);
        }

        if ($departmentId !== '') {
            $query = $query->where('department_id', $departmentId);
        }

        if ($workstationId !== '') {
            $query = $query->where('workstation_id', $workstationId);
        }

        if ($peripheralTypeId !== '') {
            $query = $query->where('peripheral_type_id', $peripheralTypeId);
        }

        if ($assignedToUserId !== '') {
            $query = $query->where('assigned_to_user_id', $assignedToUserId);
        }

        $data['peripherals'] = $query->orderBy('id', 'DESC')->findAll();
        $data['peripheral_types_map'] = $this->getPeripheralTypes();
        $data['locations'] = $this->getLocations();
        $data['departments'] = $this->getDepartments();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();
        $data['assets'] = $this->getAssets();

        return view('peripherals/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Peripheral';
        $data['locations'] = $this->getLocations();
        $data['workstations'] = $this->getWorkstations();
        $data['workstations_with_location'] = $this->getWorkstationsWithLocation();
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['departments'] = $this->getDepartments();
        $data['assignable_users'] = $this->getAssignableUsers();
        return view('peripherals/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new Peripheral();

        $data = [
            'asset_tag'           => $this->request->getPost('asset_tag'),
            'peripheral_type_id'  => $this->request->getPost('peripheral_type_id'),
            'brand'               => $this->request->getPost('brand') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'department_id'       => $this->request->getPost('department_id'),
            'location_id'         => $this->request->getPost('location_id'),
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'status'              => $this->request->getPost('status') ?? 'available',
            'condition_status'    => $this->request->getPost('condition_status') ?? 'good',
            'criticality'         => $this->request->getPost('criticality') ?? 'medium',
            'purchase_date'       => $this->request->getPost('purchase_date') ?: null,
            'warranty_expiry'     => $this->request->getPost('warranty_expiry') ?: null,
            'vendor'              => $this->request->getPost('vendor') ?: null,
            'last_maintenance_date' => $this->request->getPost('last_maintenance_date') ?: null,
            'next_maintenance_due'  => $this->request->getPost('next_maintenance_due') ?: null,
            'notes'               => $this->request->getPost('notes') ?: null,
        ];

        if ($peripheralModel->insert($data)) {
            return redirect()->to('/peripherals')->with('success', 'Peripheral created successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $peripheralModel->errors());
        }
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new Peripheral();
        $data['peripheral'] = $peripheralModel->find($id);

        if (!$data['peripheral']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral $id not found");
        }

        $data['title'] = 'Edit Peripheral';
        $data['locations'] = $this->getLocations();
        $data['workstations'] = $this->getWorkstations();
        $data['workstations_with_location'] = $this->getWorkstationsWithLocation();
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['departments'] = $this->getDepartments();
        $data['assignable_users'] = $this->getAssignableUsers();
        return view('peripherals/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new Peripheral();

        $data = [
            'asset_tag'           => $this->request->getPost('asset_tag'),
            'peripheral_type_id'  => $this->request->getPost('peripheral_type_id'),
            'brand'               => $this->request->getPost('brand') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'department_id'       => $this->request->getPost('department_id'),
            'location_id'         => $this->request->getPost('location_id'),
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'status'              => $this->request->getPost('status'),
            'condition_status'    => $this->request->getPost('condition_status'),
            'criticality'         => $this->request->getPost('criticality'),
            'purchase_date'       => $this->request->getPost('purchase_date') ?: null,
            'warranty_expiry'     => $this->request->getPost('warranty_expiry') ?: null,
            'vendor'              => $this->request->getPost('vendor') ?: null,
            'last_maintenance_date' => $this->request->getPost('last_maintenance_date') ?: null,
            'next_maintenance_due'  => $this->request->getPost('next_maintenance_due') ?: null,
            'notes'               => $this->request->getPost('notes') ?: null,
        ];

        // Set validation rules for update
        $peripheralModel->setValidationRule('asset_tag', 'required|is_unique[peripherals.asset_tag,id,' . $id . ']');
        $peripheralModel->setValidationRule('serial_number', 'permit_empty|is_unique[peripherals.serial_number,id,' . $id . ']');

        if ($peripheralModel->update($id, $data)) {
            return redirect()->to('/peripherals')->with('success', 'Peripheral updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $peripheralModel->errors());
        }
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new Peripheral();

        if ($peripheralModel->delete($id)) {
            return redirect()->to('/peripherals')->with('success', 'Peripheral deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete peripheral');
        }
    }

    public function details($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $peripheralModel = new Peripheral();
        $peripheral = $peripheralModel->find($id);

        if (!$peripheral) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral $id not found");
        }

        $locations = $this->getLocations();
        $workstations = $this->getWorkstations();
        $peripheralTypes = $this->getPeripheralTypes();
        $departments = $this->getDepartments();
        $assignableUsers = $this->getAssignableUsers();

        $data['peripheral'] = $peripheral;
        $data['peripheral_type_name'] = $peripheralTypes[$peripheral['peripheral_type_id']] ?? '-';
        $data['department_name'] = $departments[$peripheral['department_id']] ?? '-';
        $data['location_name'] = $locations[$peripheral['location_id']] ?? '-';
        $data['workstation_code'] = $workstations[$peripheral['workstation_id']] ?? '-';
        $data['assigned_user_name'] = $assignableUsers[$peripheral['assigned_to_user_id']] ?? '-';
        $data['title'] = 'Peripheral Details';

        return view('peripherals/details', $data);
    }

    private function getLocations()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, name FROM locations ORDER BY name")->getResultArray();
        $locations = [];
        foreach ($result as $row) {
            $locations[$row['id']] = $row['name'];
        }
        return $locations;
    }

    private function getWorkstations()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, workstation_code FROM workstations ORDER BY workstation_code")->getResultArray();
        $workstations = [];
        foreach ($result as $row) {
            $workstations[$row['id']] = $row['workstation_code'];
        }
        return $workstations;
    }

    private function getPeripheralTypes()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, type_name FROM peripheral_types ORDER BY type_name")->getResultArray();
        $peripheralTypes = [];
        foreach ($result as $row) {
            $peripheralTypes[$row['id']] = $row['type_name'];
        }
        return $peripheralTypes;
    }

    private function getDepartments()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, department_name FROM departments ORDER BY department_name")->getResultArray();
        $departments = [];
        foreach ($result as $row) {
            $departments[$row['id']] = $row['department_name'];
        }
        return $departments;
    }

    private function getAssignableUsers()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, full_name FROM assignable_users ORDER BY full_name")->getResultArray();
        $users = [];
        foreach ($result as $row) {
            $users[$row['id']] = $row['full_name'];
        }
        return $users;
    }

    private function getWorkstationsWithLocation()
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT id, workstation_code, location_id FROM workstations ORDER BY workstation_code")->getResultArray();
    }

    private function getAssets()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, tracking_number, recipient FROM assets ORDER BY id DESC")->getResultArray();
        $assets = [];
        foreach ($result as $row) {
            $assets[$row['id']] = $row['tracking_number'] . ' - ' . $row['recipient'];
        }
        return $assets;
    }
}
