<?php

namespace App\Controllers;

use App\Models\Asset;
use App\Models\Peripheral;
use App\Models\Unit;

class UnitController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $unitModel = new Unit();
        $data['units'] = $unitModel->orderBy('id', 'DESC')->findAll();
        $data['assets'] = $this->getAssetsWithMeta();
        $data['peripherals'] = $this->getPeripherals();
        $data['title'] = 'Units Management';

        return view('units/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Create Unit';
        $data['assets'] = $this->getAssetsWithMeta();
        $data['peripherals'] = $this->getPeripherals();
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['departments'] = $this->getDepartments();
        $data['locations'] = $this->getLocations();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();

        return view('units/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $unitModel = new Unit();
        $db = \Config\Database::connect();

        $unitType = $this->request->getPost('unit_type');
        $assetMode = $this->request->getPost('asset_mode');
        $peripheralMode = $this->request->getPost('peripheral_mode');

        // Get asset IDs (can be array or single)
        $assetIds = $this->request->getPost('asset_ids') ?: [];
        $peripheralIds = $this->request->getPost('peripheral_ids') ?: [];
        
        // Handle single asset/peripheral for backward compatibility
        if (empty($assetIds) && $this->request->getPost('asset_id')) {
            $assetIds = [$this->request->getPost('asset_id')];
        }
        if (empty($peripheralIds) && $this->request->getPost('peripheral_id')) {
            $peripheralIds = [$this->request->getPost('peripheral_id')];
        }
        
        // Filter out empty values
        $assetIds = array_filter($assetIds);
        $peripheralIds = array_filter($peripheralIds);

        // Handle creating new asset
        if ($this->includesAsset($unitType) && $assetMode === 'new') {
            $newAssetId = $this->createAssetFromUnit();
            if (!$newAssetId) {
                return redirect()->back()->withInput()->with('errors', session()->getFlashdata('errors') ?? ['Failed to create asset.']);
            }
            $assetIds[] = $newAssetId;
        }

        // Handle creating new peripheral
        if ($this->includesPeripheral($unitType) && $peripheralMode === 'new') {
            $linkedAssetId = !empty($assetIds) ? $assetIds[0] : ($this->request->getPost('peripheral_asset_id') ?: null);
            $newPeripheralId = $this->createPeripheralFromUnit($linkedAssetId);
            if (!$newPeripheralId) {
                return redirect()->back()->withInput()->with('errors', session()->getFlashdata('errors') ?? ['Failed to create peripheral.']);
            }
            $peripheralIds[] = $newPeripheralId;
        }

        $data = [
            'unit_name' => trim((string) $this->request->getPost('unit_name')),
            'unit_type' => $unitType,
            'asset_id' => !empty($assetIds) ? $assetIds[0] : null, // Keep first for backward compatibility
            'peripheral_id' => !empty($peripheralIds) ? $peripheralIds[0] : null, // Keep first for backward compatibility
            'notes' => $this->request->getPost('notes') ?: null,
        ];

        $errors = $this->validateUnitSelection($data['unit_type'], $assetIds, $peripheralIds);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $db->transStart();

        if ($unitModel->insert($data)) {
            $unitId = $unitModel->insertID();
            
            // Sync assets in junction table
            $this->syncUnitAssets($unitId, $assetIds);
            
            // Sync peripherals in junction table
            $this->syncUnitPeripherals($unitId, $peripheralIds);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('errors', ['Failed to save unit relationships']);
            }
            
            return redirect()->to('/units')->with('success', 'Unit created successfully');
        }

        $db->transComplete();
        return redirect()->back()->withInput()->with('errors', $unitModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $unitModel = new Unit();
        $data['unit'] = $unitModel->find($id);

        if (!$data['unit']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Unit $id not found");
        }

        $data['assets'] = $this->getAssets();
        $data['peripherals'] = $this->getPeripherals();
        $data['title'] = 'Edit Unit';
        
        // Load existing relationships from junction tables
        $data['unit_assets'] = $this->getUnitAssets($id);
        $data['unit_peripherals'] = $this->getUnitPeripherals($id);

        return view('units/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $unitModel = new Unit();
        $db = \Config\Database::connect();
        
        // Get asset and peripheral IDs (arrays)
        $assetIds = $this->request->getPost('asset_ids') ?: [];
        $peripheralIds = $this->request->getPost('peripheral_ids') ?: [];
        
        // Filter out empty values
        $assetIds = array_filter($assetIds);
        $peripheralIds = array_filter($peripheralIds);

        $data = [
            'unit_name' => trim((string) $this->request->getPost('unit_name')),
            'unit_type' => $this->request->getPost('unit_type'),
            'asset_id' => !empty($assetIds) ? $assetIds[0] : null, // Keep first for backward compatibility
            'peripheral_id' => !empty($peripheralIds) ? $peripheralIds[0] : null, // Keep first for backward compatibility
            'notes' => $this->request->getPost('notes') ?: null,
        ];

        $errors = $this->validateUnitSelection($data['unit_type'], $assetIds, $peripheralIds);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $db->transStart();

        if ($unitModel->update($id, $data)) {
            // Sync assets in junction table
            $this->syncUnitAssets($id, $assetIds);
            
            // Sync peripherals in junction table
            $this->syncUnitPeripherals($id, $peripheralIds);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('errors', ['Failed to update unit relationships']);
            }
            
            return redirect()->to('/units')->with('success', 'Unit updated successfully');
        }

        $db->transComplete();
        return redirect()->back()->withInput()->with('errors', $unitModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $unitModel = new Unit();

        if ($unitModel->delete($id)) {
            return redirect()->to('/units')->with('success', 'Unit deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete unit');
    }

    public function view($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $unitModel = new Unit();
        $assetModel = new Asset();
        $peripheralModel = new Peripheral();
        
        $data['unit'] = $unitModel->find($id);

        if (!$data['unit']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Unit $id not found");
        }

        // Load assets and peripherals from junction tables
        $data['unit_assets'] = $this->getUnitAssetsWithDetails($id);
        $data['unit_peripherals'] = $this->getUnitPeripheralsWithDetails($id);
        $data['title'] = 'Unit Details';

        return view('units/view', $data);
    }

    private function getAssets()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, asset_tag, recipient FROM assets ORDER BY id DESC")->getResultArray();
        $assets = [];
        foreach ($result as $row) {
            $label = trim(($row['asset_tag'] ?: 'No Asset Tag') . ' - ' . $row['recipient']);
            $assets[$row['id']] = $label;
        }
        return $assets;
    }

    private function getAssetsWithMeta()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, asset_tag, recipient, department_id, location_id, workstation_id, assigned_to_user_id FROM assets ORDER BY id DESC")->getResultArray();
        $assets = [];
        foreach ($result as $row) {
            $label = trim(($row['asset_tag'] ?: 'No Asset Tag') . ' - ' . $row['recipient']);
            $assets[$row['id']] = [
                'label' => $label,
                'department_id' => $row['department_id'],
                'location_id' => $row['location_id'],
                'workstation_id' => $row['workstation_id'],
                'assigned_to_user_id' => $row['assigned_to_user_id'],
            ];
        }
        return $assets;
    }

    private function getPeripherals()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, brand, model, serial_number FROM peripherals ORDER BY id DESC")->getResultArray();
        $peripherals = [];
        foreach ($result as $row) {
            $brand = $row['brand'] ?: 'Unknown';
            $model = $row['model'] ?: '';
            $serial = $row['serial_number'] ?: '';
            $details = trim($brand . ' ' . $model);
            if ($serial !== '') {
                $details = trim($details . ' (' . $serial . ')');
            }
            $peripherals[$row['id']] = trim('P#' . $row['id'] . ' - ' . ($details !== '' ? $details : 'Peripheral'));
        }
        return $peripherals;
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

    private function includesAsset($unitType)
    {
        return in_array($unitType, ['asset', 'both'], true);
    }

    private function includesPeripheral($unitType)
    {
        return in_array($unitType, ['peripheral', 'both'], true);
    }

    private function createAssetFromUnit()
    {
        $assetModel = new Asset();

        $status = $this->request->getPost('asset_status') ?: 'pending';

        $assetData = [
            'asset_tag' => $this->request->getPost('asset_asset_tag') ?: null,
            'box_number' => $this->request->getPost('asset_box_number') ?: null,
            'serial_number' => $this->request->getPost('asset_serial_number') ?: null,
            'model' => $this->request->getPost('asset_model') ?: null,
            'model_number' => $this->request->getPost('asset_model_number') ?: null,
            'manufacturer' => $this->request->getPost('asset_manufacturer') ?: null,
            'category' => $this->request->getPost('asset_category') ?: null,
            'qty' => $this->request->getPost('asset_qty') ?: null,
            'description' => $this->request->getPost('asset_description') ?: null,
            'status' => $status,
            'date_updated' => date('Y-m-d H:i:s'),
            'purchase_cost' => $this->request->getPost('asset_purchase_cost') ?: null,
            'order_number' => $this->request->getPost('asset_order_number') ?: null,
            'supplier' => $this->request->getPost('asset_supplier') ?: null,
            'department_id' => $this->request->getPost('asset_department_id') ?: null,
            'location_id' => $this->request->getPost('asset_location_id') ?: null,
            'workstation_id' => $this->request->getPost('asset_workstation_id') ?: null,
            'assigned_to_user_id' => $this->request->getPost('asset_assigned_to_user_id') ?: null,
            'requestable' => $this->request->getPost('asset_requestable') ? 1 : 0,
            'byod' => $this->request->getPost('asset_byod') ? 1 : 0,
        ];

        $purchaseDate = $this->request->getPost('asset_purchase_date');
        if ($purchaseDate) {
            $assetData['purchase_date'] = date('Y-m-d', strtotime($purchaseDate));
        }

        if ($assetModel->insert($assetData)) {
            return $assetModel->insertID();
        }

        session()->setFlashdata('errors', $assetModel->errors());
        return null;
    }

    private function createPeripheralFromUnit($assetId)
    {
        $peripheralModel = new Peripheral();

        $peripheralData = [
            'asset_id' => $assetId ?: null,
            'peripheral_type_id' => $this->request->getPost('peripheral_type_id'),
            'brand' => $this->request->getPost('peripheral_brand') ?: null,
            'model' => $this->request->getPost('peripheral_model') ?: null,
            'serial_number' => $this->request->getPost('peripheral_serial_number') ?: null,
            'department_id' => $this->request->getPost('peripheral_department_id'),
            'location_id' => $this->request->getPost('peripheral_location_id'),
            'assigned_to_user_id' => $this->request->getPost('peripheral_assigned_to_user_id') ?: null,
            'workstation_id' => $this->request->getPost('peripheral_workstation_id') ?: null,
            'status' => $this->request->getPost('peripheral_status') ?: 'available',
            'condition_status' => $this->request->getPost('peripheral_condition_status') ?: 'good',
            'criticality' => $this->request->getPost('peripheral_criticality') ?: 'medium',
            'purchase_date' => $this->request->getPost('peripheral_purchase_date') ?: null,
            'notes' => $this->request->getPost('peripheral_notes') ?: null,
        ];

        if ($peripheralModel->insert($peripheralData)) {
            return $peripheralModel->insertID();
        }

        session()->setFlashdata('errors', $peripheralModel->errors());
        return null;
    }

    private function validateUnitSelection($unitType, $assetIds, $peripheralIds)
    {
        $errors = [];
        
        // Handle both array and single value for backward compatibility
        $hasAssets = is_array($assetIds) ? !empty($assetIds) : !empty($assetIds);
        $hasPeripherals = is_array($peripheralIds) ? !empty($peripheralIds) : !empty($peripheralIds);

        if ($unitType === 'asset' && !$hasAssets) {
            $errors[] = 'At least one asset is required when unit type is Asset.';
        }

        if ($unitType === 'peripheral' && !$hasPeripherals) {
            $errors[] = 'At least one peripheral is required when unit type is Peripheral.';
        }

        if ($unitType === 'both') {
            if (!$hasAssets || !$hasPeripherals) {
                $errors[] = 'At least one asset and one peripheral are required when unit type is Both.';
            }
        }

        return $errors;
    }
    
    private function syncUnitAssets($unitId, $assetIds)
    {
        $db = \Config\Database::connect();
        
        // Delete existing relationships
        $db->table('unit_assets')->where('unit_id', $unitId)->delete();
        
        // Insert new relationships
        if (!empty($assetIds)) {
            $data = [];
            foreach ($assetIds as $assetId) {
                if ($assetId) {
                    $data[] = [
                        'unit_id' => $unitId,
                        'asset_id' => $assetId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if (!empty($data)) {
                $db->table('unit_assets')->insertBatch($data);
            }
        }
    }
    
    private function syncUnitPeripherals($unitId, $peripheralIds)
    {
        $db = \Config\Database::connect();
        
        // Delete existing relationships
        $db->table('unit_peripherals')->where('unit_id', $unitId)->delete();
        
        // Insert new relationships
        if (!empty($peripheralIds)) {
            $data = [];
            foreach ($peripheralIds as $peripheralId) {
                if ($peripheralId) {
                    $data[] = [
                        'unit_id' => $unitId,
                        'peripheral_id' => $peripheralId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if (!empty($data)) {
                $db->table('unit_peripherals')->insertBatch($data);
            }
        }
    }
    
    private function getUnitAssets($unitId)
    {
        $db = \Config\Database::connect();
        return $db->table('unit_assets')
            ->where('unit_id', $unitId)
            ->get()
            ->getResultArray();
    }
    
    private function getUnitPeripherals($unitId)
    {
        $db = \Config\Database::connect();
        return $db->table('unit_peripherals')
            ->where('unit_id', $unitId)
            ->get()
            ->getResultArray();
    }
    
    private function getUnitAssetsWithDetails($unitId)
    {
        $db = \Config\Database::connect();
        return $db->table('unit_assets ua')
            ->select('a.*, ua.created_at as linked_at')
            ->join('assets a', 'a.id = ua.asset_id', 'left')
            ->where('ua.unit_id', $unitId)
            ->get()
            ->getResultArray();
    }
    
    private function getUnitPeripheralsWithDetails($unitId)
    {
        $db = \Config\Database::connect();
        return $db->table('unit_peripherals up')
            ->select('p.*, pt.type_name as peripheral_type_name, up.created_at as linked_at')
            ->join('peripherals p', 'p.id = up.peripheral_id', 'left')
            ->join('peripheral_types pt', 'pt.id = p.peripheral_type_id', 'left')
            ->where('up.unit_id', $unitId)
            ->get()
            ->getResultArray();
    }
}
