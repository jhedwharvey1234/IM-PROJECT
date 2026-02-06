<?php

namespace App\Controllers;

use App\Models\Asset;
use App\Models\Item;
use CodeIgniter\Controller;

class AssetController extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $assetModel = new Asset();
        $data['assets'] = $assetModel->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Assets Management';

        return view('assets/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Asset';

        return view('assets/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $assetModel = new Asset();

        $data = [
            'tracking_number' => $this->request->getPost('tracking_number') ?: null,
            'box_number'      => $this->request->getPost('box_number') ?: null,
            'sender'          => $this->request->getPost('sender'),
            'recipient'       => $this->request->getPost('recipient'),
            'address'         => $this->request->getPost('address'),
            'description'     => $this->request->getPost('description') ?: null,
            'status'          => $this->request->getPost('status') ?? 'pending',
            'date_sent'       => $this->request->getPost('date_sent') ? date('Y-m-d H:i:s', strtotime($this->request->getPost('date_sent'))) : date('Y-m-d H:i:s'),
        ];

        $dateInTransit = $this->request->getPost('date_in_transit');
        if ($dateInTransit) {
            $data['date_in_transit'] = date('Y-m-d H:i:s', strtotime($dateInTransit));
        }

        $dateReceived = $this->request->getPost('date_received');
        if ($dateReceived) {
            $data['date_received'] = date('Y-m-d H:i:s', strtotime($dateReceived));
        }

        $dateRejected = $this->request->getPost('date_rejected');
        if ($dateRejected) {
            $data['date_rejected'] = date('Y-m-d H:i:s', strtotime($dateRejected));
        }

        // handle barcode image upload
        $file = $this->request->getFile('barcode');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/barcodes', $newName);
            $data['barcode'] = $newName;
        }

        if ($assetModel->insert($data)) {
            $assetId = $assetModel->insertID();
            return redirect()->to('/assets')->with('success', 'Asset created successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $assetModel->errors());
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

        $assetModel = new Asset();
        $data['asset'] = $assetModel->find($id);

        if (!$data['asset']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Asset $id not found");
        }

        $data['title'] = 'Edit Asset';

        return view('assets/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $assetModel = new Asset();

        $asset = $assetModel->find($id);

        $newStatus = $this->request->getPost('status');

        $data = [
            'tracking_number' => $this->request->getPost('tracking_number') ?: null,
            'box_number'      => $this->request->getPost('box_number') ?: null,
            'sender'          => $this->request->getPost('sender'),
            'recipient'       => $this->request->getPost('recipient'),
            'address'         => $this->request->getPost('address'),
            'description'     => $this->request->getPost('description') ?: null,
            'status'          => $newStatus,
        ];

        // handle date inputs from form
        $dateInTransit = $this->request->getPost('date_in_transit');
        if ($dateInTransit) {
            $data['date_in_transit'] = date('Y-m-d H:i:s', strtotime($dateInTransit));
        }

        $dateReceived = $this->request->getPost('date_received');
        if ($dateReceived) {
            $data['date_received'] = date('Y-m-d H:i:s', strtotime($dateReceived));
        }

        $dateRejected = $this->request->getPost('date_rejected');
        if ($dateRejected) {
            $data['date_rejected'] = date('Y-m-d H:i:s', strtotime($dateRejected));
        }

        // handle barcode image upload
        $file = $this->request->getFile('barcode');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // remove old file if exists
            if (!empty($asset['barcode']) && file_exists(FCPATH . 'uploads/barcodes/' . $asset['barcode'])) {
                @unlink(FCPATH . 'uploads/barcodes/' . $asset['barcode']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/barcodes', $newName);
            $data['barcode'] = $newName;
        }

        // set validation rules for update (exclude current record from unique check)
        $assetModel->setValidationRule('tracking_number', 'permit_empty|is_unique[assets.tracking_number,id,' . $id . ']');
        $assetModel->setValidationRule('barcode', 'permit_empty|is_unique[assets.barcode,id,' . $id . ']');

        if ($assetModel->update($id, $data)) {
            return redirect()->to('/assets')->with('success', 'Asset updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $assetModel->errors());
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

        $assetModel = new Asset();

        if ($assetModel->delete($id)) {
            return redirect()->to('/assets')->with('success', 'Asset deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete asset');
        }
    }

    public function exportPdf($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $assetModel = new Asset();
        $itemModel = new Item();

        $asset = $assetModel->find($id);

        if (!$asset) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Asset $id not found");
        }

        $data['asset'] = $asset;
        $data['items'] = $itemModel->where('asset_id', $id)->findAll();

        return view('assets/export_pdf', $data);
    }

    public function details($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $assetModel = new Asset();
        $peripheralModel = new \App\Models\Peripheral();

        $data['asset'] = $assetModel->find($id);

        if (!$data['asset']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Asset $id not found");
        }

        $data['peripherals'] = $peripheralModel->where('asset_id', $id)->findAll();
        $data['title'] = 'Asset Details';
        
        // Get dropdown data for adding peripherals
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['locations'] = $this->getLocations();
        $data['departments'] = $this->getDepartments();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();

        return view('assets/details', $data);
    }

    public function storeItem()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $itemModel = new Item();
        $assetId = $this->request->getPost('asset_id');

        $itemData = [
            'item_name' => $this->request->getPost('item_name'),
            'quantity' => $this->request->getPost('quantity'),
            'unit' => $this->request->getPost('unit'),
            'description' => $this->request->getPost('description'),
        ];

        // Remove empty values and convert to JSON
        $itemData = array_filter($itemData, fn($value) => $value !== null && $value !== '');
        
        $data = [
            'asset_id' => $assetId,
            'item_description' => json_encode($itemData),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($itemModel->insert($data)) {
            return redirect()->to('/assets/details/' . $assetId)->with('success', 'Item added successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $itemModel->errors());
        }
    }

    public function search()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $assetModel = new Asset();
        $search = trim((string) $this->request->getGet('search'));
        $status = trim((string) $this->request->getGet('status'));
        $startDate = trim((string) $this->request->getGet('start_date'));
        $endDate = trim((string) $this->request->getGet('end_date'));

        $query = $assetModel;

        if ($search !== '') {
            $escaped = esc_like($search);
            $query = $query->groupStart()
                ->like('tracking_number', $escaped)
                ->orLike('barcode', $escaped)
                ->orLike('sender', $escaped)
                ->orLike('recipient', $escaped)
                ->groupEnd();
        }

        if ($status !== '') {
            $query = $query->where('status', $status);
        }

        if ($startDate !== '') {
            // assume YYYY-MM-DD -> start of day, check all date columns
            $query = $query->where('(date_sent >= "' . $startDate . ' 00:00:00" OR date_in_transit >= "' . $startDate . ' 00:00:00" OR date_received >= "' . $startDate . ' 00:00:00" OR date_rejected >= "' . $startDate . ' 00:00:00")', null, false);
        }

        if ($endDate !== '') {
            // assume YYYY-MM-DD -> end of day, check all date columns
            $query = $query->where('(date_sent <= "' . $endDate . ' 23:59:59" OR date_in_transit <= "' . $endDate . ' 23:59:59" OR date_received <= "' . $endDate . ' 23:59:59" OR date_rejected <= "' . $endDate . ' 23:59:59")', null, false);
        }

        $data['assets'] = $query->orderBy('id', 'DESC')->findAll();

        return view('assets/index', $data);
    }

    public function editItem($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $itemModel = new Item();
        $data['item'] = $itemModel->find($id);

        if (!$data['item']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Item $id not found");
        }

        $assetModel = new Asset();
        $data['asset'] = $assetModel->find($data['item']['asset_id']);
        $data['title'] = 'Edit Item';

        return view('assets/edit_item', $data);
    }

    public function updateItem($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $itemModel = new Item();
        $item = $itemModel->find($id);

        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Item $id not found");
        }

        $itemData = [
            'item_name' => $this->request->getPost('item_name'),
            'quantity' => $this->request->getPost('quantity'),
            'unit' => $this->request->getPost('unit'),
            'description' => $this->request->getPost('description'),
        ];

        // Remove empty values and convert to JSON
        $itemData = array_filter($itemData, fn($value) => $value !== null && $value !== '');
        
        $data = [
            'item_description' => json_encode($itemData),
        ];

        if ($itemModel->update($id, $data)) {
            return redirect()->to('/assets/details/' . $item['asset_id'])->with('success', 'Item updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $itemModel->errors());
        }
    }

    public function deleteItem($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $itemModel = new Item();
        $item = $itemModel->find($id);

        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Item $id not found");
        }

        $assetId = $item['asset_id'];

        if ($itemModel->delete($id)) {
            return redirect()->to('/assets/details/' . $assetId)->with('success', 'Item deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete item');
        }
    }

    // Peripheral management methods for assets
    public function storePeripheral()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new \App\Models\Peripheral();
        $assetId = $this->request->getPost('asset_id');

        $data = [
            'asset_id'            => $assetId,
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
            return redirect()->to('/assets/details/' . $assetId)->with('success', 'Peripheral added successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $peripheralModel->errors());
        }
    }

    public function editPeripheral($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new \App\Models\Peripheral();
        $data['peripheral'] = $peripheralModel->find($id);

        if (!$data['peripheral']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral $id not found");
        }

        $assetModel = new Asset();
        $data['asset'] = $assetModel->find($data['peripheral']['asset_id']);
        $data['title'] = 'Edit Peripheral';
        $data['locations'] = $this->getLocations();
        $data['workstations'] = $this->getWorkstations();
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['departments'] = $this->getDepartments();
        $data['assignable_users'] = $this->getAssignableUsers();

        return view('assets/edit_peripheral', $data);
    }

    public function updatePeripheral($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new \App\Models\Peripheral();
        $peripheral = $peripheralModel->find($id);

        if (!$peripheral) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral $id not found");
        }

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
            return redirect()->to('/assets/details/' . $peripheral['asset_id'])->with('success', 'Peripheral updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $peripheralModel->errors());
        }
    }

    public function deletePeripheral($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $peripheralModel = new \App\Models\Peripheral();
        $peripheral = $peripheralModel->find($id);

        if (!$peripheral) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Peripheral $id not found");
        }

        $assetId = $peripheral['asset_id'];

        if ($peripheralModel->delete($id)) {
            return redirect()->to('/assets/details/' . $assetId)->with('success', 'Peripheral deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete peripheral');
        }
    }

    // Helper methods
    private function getPeripheralTypes()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, type_name FROM peripheral_types ORDER BY type_name")->getResultArray();
        $types = [];
        foreach ($result as $row) {
            $types[$row['id']] = $row['type_name'];
        }
        return $types;
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
}
