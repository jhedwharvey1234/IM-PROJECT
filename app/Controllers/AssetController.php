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
        $data['departments'] = $this->getDepartments();
        $data['locations'] = $this->getLocations();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();

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
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['locations'] = $this->getLocations();
        $data['departments'] = $this->getDepartments();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();
        $data['categories'] = $this->getCategories();
        $data['units'] = $this->getUnitsForAssets();

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
            'asset_tag'           => $this->request->getPost('asset_tag') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'model_number'        => $this->request->getPost('model_number') ?: null,
            'manufacturer'        => $this->request->getPost('manufacturer') ?: null,
            'category'            => $this->request->getPost('category') ?: null,
            'qty'                 => $this->request->getPost('qty') ?: 1,
            'description'         => $this->request->getPost('description') ?: null,
            'status'              => $this->request->getPost('status') ?? 'pending',
            'date_updated'        => date('Y-m-d H:i:s'),
            'purchase_cost'       => $this->request->getPost('purchase_cost') ?: null,
            'order_number'        => $this->request->getPost('order_number') ?: null,
            'supplier'            => $this->request->getPost('supplier') ?: null,
            'requestable'         => $this->request->getPost('requestable') ? 1 : 0,
            'byod'                => $this->request->getPost('byod') ? 1 : 0,
            'department_id'       => $this->request->getPost('department_id') ?: null,
            'location_id'         => $this->request->getPost('location_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'unit_id'             => $this->request->getPost('unit_id') ?: null,
        ];

        $purchaseDate = $this->request->getPost('purchase_date');
        if ($purchaseDate) {
            $data['purchase_date'] = date('Y-m-d', strtotime($purchaseDate));
        }

        // handle barcode image upload
        $file = $this->request->getFile('barcode');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/barcodes', $newName);
            $data['barcode'] = $newName;
        }

        // handle device image upload
        $deviceImage = $this->request->getFile('device_image');
        if ($deviceImage && $deviceImage->isValid() && !$deviceImage->hasMoved()) {
            $newName = $deviceImage->getRandomName();
            $deviceImage->move(FCPATH . 'uploads/devices', $newName);
            $data['device_image'] = $newName;
        }

        if ($assetModel->insert($data)) {
            $assetId = $assetModel->insertID();
            
            // Log asset creation to history
            $assetHistoryModel = new \App\Models\AssetHistory();
            $assetHistoryModel->logAction($assetId, 'created', 'Asset created');

            if (!empty($data['assigned_to_user_id'])) {
                $assetHistoryModel->logChanges($assetId, [
                    [
                        'field' => 'assigned_to_user_id',
                        'old' => null,
                        'new' => $data['assigned_to_user_id'],
                        'description' => 'Assigned on create',
                    ],
                ], 'assigned');
            }
            
            // Create peripherals if provided
            $peripheralTypeIds = $this->request->getPost('peripheral_type_id[]');
            $createdPeripheralCount = 0;
            if (!empty($peripheralTypeIds) && is_array($peripheralTypeIds)) {
                $peripheralModel = new \App\Models\Peripheral();
                
                foreach ($peripheralTypeIds as $index => $peripheralTypeId) {
                    if (empty($peripheralTypeId)) {
                        continue;
                    }
                    
                    $peripheralData = [
                        'asset_id'               => $assetId,
                        'peripheral_type_id'     => $peripheralTypeId,
                        'brand'                  => $this->request->getPost("peripheral_brand[]")[$index] ?? null,
                        'model'                  => $this->request->getPost("peripheral_model[]")[$index] ?? null,
                        'serial_number'          => $this->request->getPost("peripheral_serial_number[]")[$index] ?? null,
                        'department_id'          => $this->request->getPost("peripheral_department_id[]")[$index] ?? null,
                        'location_id'            => $this->request->getPost("peripheral_location_id[]")[$index] ?? null,
                        'workstation_id'         => $this->request->getPost("peripheral_workstation_id[]")[$index] ?? null,
                        'assigned_to_user_id'    => $this->request->getPost("peripheral_assigned_to_user_id[]")[$index] ?? null,
                        'status'                 => $this->request->getPost("peripheral_status[]")[$index] ?? 'available',
                        'condition_status'       => $this->request->getPost("peripheral_condition_status[]")[$index] ?? 'new',
                        'criticality'            => $this->request->getPost("peripheral_criticality[]")[$index] ?? 'low',
                        'purchase_date'          => !empty($this->request->getPost("peripheral_purchase_date[]")[$index] ?? null) ? date('Y-m-d', strtotime($this->request->getPost("peripheral_purchase_date[]")[$index])) : null,
                        'notes'                  => $this->request->getPost("peripheral_notes[]")[$index] ?? null,
                    ];
                    
                    if ($peripheralModel->insert($peripheralData)) {
                        $createdPeripheralCount++;
                    }
                }
            }
            
            return redirect()->to('/assets/details/' . $assetId)->with('success', 'Asset created successfully with ' . ($createdPeripheralCount > 0 ? $createdPeripheralCount . ' peripheral(s)' : '0 peripherals') . '.');
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
        $data['peripheral_types'] = $this->getPeripheralTypes();
        $data['locations'] = $this->getLocations();
        $data['departments'] = $this->getDepartments();
        $data['workstations'] = $this->getWorkstations();
        $data['assignable_users'] = $this->getAssignableUsers();
        $data['categories'] = $this->getCategories();
        $data['units'] = $this->getUnitsForAssets();

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
            'asset_tag'           => $this->request->getPost('asset_tag') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'model_number'        => $this->request->getPost('model_number') ?: null,
            'manufacturer'        => $this->request->getPost('manufacturer') ?: null,
            'category'            => $this->request->getPost('category') ?: null,
            'qty'                 => $this->request->getPost('qty') ?: 1,
            'description'         => $this->request->getPost('description') ?: null,
            'status'              => $newStatus ?: 'pending',
            'purchase_cost'       => $this->request->getPost('purchase_cost') ?: null,
            'order_number'        => $this->request->getPost('order_number') ?: null,
            'supplier'            => $this->request->getPost('supplier') ?: null,
            'requestable'         => $this->request->getPost('requestable') ? 1 : 0,
            'byod'                => $this->request->getPost('byod') ? 1 : 0,
            'department_id'       => $this->request->getPost('department_id') ?: null,
            'location_id'         => $this->request->getPost('location_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'unit_id'             => $this->request->getPost('unit_id') ?: null,
        ];

        // Auto-update date_updated if status changed
        if ($asset['status'] !== $newStatus) {
            $data['date_updated'] = date('Y-m-d H:i:s');
        }

        $purchaseDate = $this->request->getPost('purchase_date');
        if ($purchaseDate) {
            $data['purchase_date'] = date('Y-m-d', strtotime($purchaseDate));
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

        // handle device image upload
        $deviceImage = $this->request->getFile('device_image');
        if ($deviceImage && $deviceImage->isValid() && !$deviceImage->hasMoved()) {
            if (!empty($asset['device_image']) && file_exists(FCPATH . 'uploads/devices/' . $asset['device_image'])) {
                @unlink(FCPATH . 'uploads/devices/' . $asset['device_image']);
            }
            $newName = $deviceImage->getRandomName();
            $deviceImage->move(FCPATH . 'uploads/devices', $newName);
            $data['device_image'] = $newName;
        }

        // set validation rules for update (exclude current record from unique check)
        $assetModel->setValidationRule('asset_tag', 'permit_empty|is_unique[assets.asset_tag,id,' . $id . ']');
        $assetModel->setValidationRule('barcode', 'permit_empty|is_unique[assets.barcode,id,' . $id . ']');
        $assetModel->setValidationRule('device_image', 'permit_empty');

        if ($assetModel->update($id, $data)) {
            // Log changes to history
            $assetHistoryModel = new \App\Models\AssetHistory();
            $changes = [];

            // Track which fields changed
            foreach ($data as $field => $newValue) {
                $oldValue = $asset[$field] ?? null;
                if ($oldValue != $newValue) {
                    $changes[] = [
                        'field' => $field,
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }

            // Log the changes if any
            if (!empty($changes)) {
                $assetHistoryModel->logChanges($id, $changes, 'updated');
            }

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

        $asset = $assetModel->find($id);
        if ($asset) {
            // Log deletion before deleting
            $assetHistoryModel = new \App\Models\AssetHistory();
            $assetHistoryModel->logAction($id, 'deleted', 'Asset deleted');
        }

        if ($assetModel->delete($id)) {
            return redirect()->to('/assets')->with('success', 'Asset deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete asset');
        }
    }

    public function batchDelete()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (session()->get('usertype') !== 'superadmin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $ids = $this->request->getPost('ids');
        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No assets selected']);
        }

        $assetModel = new Asset();
        $assetHistoryModel = new \App\Models\AssetHistory();
        $deletedCount = 0;

        foreach ($ids as $id) {
            // Log deletion before deleting
            $assetHistoryModel->logAction($id, 'deleted', 'Asset deleted (batch operation)');
            
            if ($assetModel->delete($id)) {
                $deletedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => $deletedCount > 0,
            'message' => "$deletedCount asset(s) deleted",
            'count' => $deletedCount
        ]);
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
        $assetNoteModel = new \App\Models\AssetNote();
        $assetHistoryModel = new \App\Models\AssetHistory();

        $data['asset'] = $assetModel->find($id);

        if (!$data['asset']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Asset $id not found");
        }

        $data['peripherals'] = $peripheralModel->where('asset_id', $id)->findAll();
        $data['notes'] = $assetNoteModel->getNotesForAsset($id);
        $data['history'] = $assetHistoryModel->getHistoryForAsset($id);
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
                ->like('asset_tag', $escaped)
                ->orLike('serial_number', $escaped)
                ->orLike('model', $escaped)
                ->orLike('model_number', $escaped)
                ->orLike('barcode', $escaped)
                ->orLike('sender', $escaped)
                ->orLike('recipient', $escaped)
                ->groupEnd();
        }

        if ($status !== '') {
            $query = $query->where('status', $status);
        }

        if ($startDate !== '') {
            // Filter by date_updated or created_at
            $query = $query->where('(date_updated >= "' . $startDate . ' 00:00:00" OR created_at >= "' . $startDate . ' 00:00:00")', null, false);
        }

        if ($endDate !== '') {
            // Filter by date_updated or created_at
            $query = $query->where('(date_updated <= "' . $endDate . ' 23:59:59" OR created_at <= "' . $endDate . ' 23:59:59")', null, false);
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
            'peripheral_type_id'  => $this->request->getPost('peripheral_type_id'),
            'brand'               => $this->request->getPost('brand') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'model_number'        => $this->request->getPost('model_number') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'department_id'       => $this->request->getPost('department_id') ?: null,
            'location_id'         => $this->request->getPost('location_id') ?: null,
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'status'              => $this->request->getPost('status') ?? 'available',
            'condition_status'    => $this->request->getPost('condition_status') ?? 'good',
            'criticality'         => $this->request->getPost('criticality') ?? 'medium',
            'purchase_date'       => $this->request->getPost('purchase_date') ?: null,
            'purchase_cost'       => $this->request->getPost('purchase_cost') ?: null,
            'order_number'        => $this->request->getPost('order_number') ?: null,
            'supplier'            => $this->request->getPost('supplier') ?: null,
            'qty'                 => $this->request->getPost('qty') ?: 1,
            'requestable'         => $this->request->getPost('requestable') ? 1 : 0,
            'byod'                => $this->request->getPost('byod') ? 1 : 0,
            'warranty_expiry'     => $this->request->getPost('warranty_expiry') ?: null,
            'vendor'              => $this->request->getPost('vendor') ?: null,
        ];

        // Handle device image upload
        $deviceImage = $this->request->getFile('device_image');
        if ($deviceImage && $deviceImage->isValid() && !$deviceImage->hasMoved()) {
            $newName = $deviceImage->getRandomName();
            $deviceImage->move(FCPATH . 'uploads/devices', $newName);
            $data['device_image'] = $newName;
        }

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
            'peripheral_type_id'  => $this->request->getPost('peripheral_type_id'),
            'brand'               => $this->request->getPost('brand') ?: null,
            'model'               => $this->request->getPost('model') ?: null,
            'model_number'        => $this->request->getPost('model_number') ?: null,
            'serial_number'       => $this->request->getPost('serial_number') ?: null,
            'department_id'       => $this->request->getPost('department_id') ?: null,
            'location_id'         => $this->request->getPost('location_id') ?: null,
            'assigned_to_user_id' => $this->request->getPost('assigned_to_user_id') ?: null,
            'workstation_id'      => $this->request->getPost('workstation_id') ?: null,
            'status'              => $this->request->getPost('status'),
            'condition_status'    => $this->request->getPost('condition_status'),
            'criticality'         => $this->request->getPost('criticality'),
            'purchase_date'       => $this->request->getPost('purchase_date') ?: null,
            'purchase_cost'       => $this->request->getPost('purchase_cost') ?: null,
            'order_number'        => $this->request->getPost('order_number') ?: null,
            'supplier'            => $this->request->getPost('supplier') ?: null,
            'qty'                 => $this->request->getPost('qty') ?: 1,
            'requestable'         => $this->request->getPost('requestable') ? 1 : 0,
            'byod'                => $this->request->getPost('byod') ? 1 : 0,
            'warranty_expiry'     => $this->request->getPost('warranty_expiry') ?: null,
            'vendor'              => $this->request->getPost('vendor') ?: null,
        ];

        // Handle device image upload
        $deviceImage = $this->request->getFile('device_image');
        if ($deviceImage && $deviceImage->isValid() && !$deviceImage->hasMoved()) {
            $newName = $deviceImage->getRandomName();
            $deviceImage->move(FCPATH . 'uploads/devices', $newName);
            $data['device_image'] = $newName;
        }

        // Set validation rules for update
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

    private function getCategories()
    {
        $categoryModel = new \App\Models\Category();
        $result = $categoryModel->getActive();
        $categories = [];
        foreach ($result as $row) {
            $categories[$row['id']] = $row['name'];
        }
        return $categories;
    }

    private function getUnitsForAssets()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT id, unit_name, unit_type FROM units ORDER BY unit_name")->getResultArray();
        $units = [];
        foreach ($result as $row) {
            if (in_array($row['unit_type'], ['asset', 'both'], true)) {
                $units[$row['id']] = $row['unit_name'];
            }
        }
        return $units;
    }

    public function addNote()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $assetNoteModel = new \App\Models\AssetNote();

        $data = [
            'asset_id' => $this->request->getPost('asset_id'),
            'user_id'  => session()->get('user_id'),
            'note'     => $this->request->getPost('note'),
        ];

        if ($assetNoteModel->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Note added successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add note', 'errors' => $assetNoteModel->errors()]);
        }
    }

    public function deleteNote($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $assetNoteModel = new \App\Models\AssetNote();
        $note = $assetNoteModel->find($id);

        if (!$note) {
            return redirect()->back()->with('error', 'Note not found');
        }

        // Only allow deletion by the note creator or superadmin
        if ($note['user_id'] != session()->get('user_id') && session()->get('usertype') !== 'superadmin') {
            return redirect()->back()->with('error', 'Unauthorized to delete this note');
        }

        if ($assetNoteModel->delete($id)) {
            return redirect()->back()->with('success', 'Note deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete note');
        }
    }
}
