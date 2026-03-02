<?php

namespace App\Controllers;

use App\Models\Asset;
use App\Models\Peripheral;

class AssetPublicController extends BaseController
{
    public function details($shareToken)
    {
        $shareToken = trim((string) $shareToken);
        if ($shareToken === '') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid asset link');
        }

        $assetModel = new Asset();

        if (!\Config\Database::connect()->fieldExists('share_token', 'assets')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Public asset links are not configured yet');
        }

        $asset = $assetModel->where('share_token', $shareToken)->first();
        if (!$asset) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Asset not found');
        }

        $peripheralModel = new Peripheral();

        $data = [
            'asset' => $asset,
            'peripherals' => $peripheralModel->where('asset_id', (int) $asset['id'])->findAll(),
            'peripheralTypes' => $this->getPeripheralTypes(),
            'locations' => $this->getLocations(),
            'departments' => $this->getDepartments(),
            'workstations' => $this->getWorkstations(),
            'title' => 'Asset Details',
        ];

        return view('assets/public_details', $data);
    }

    private function getPeripheralTypes(): array
    {
        $db = \Config\Database::connect();
        $result = $db->query('SELECT id, type_name FROM peripheral_types ORDER BY type_name')->getResultArray();
        $types = [];
        foreach ($result as $row) {
            $types[$row['id']] = $row['type_name'];
        }

        return $types;
    }

    private function getLocations(): array
    {
        $db = \Config\Database::connect();
        $result = $db->query('SELECT id, name FROM locations ORDER BY name')->getResultArray();
        $locations = [];
        foreach ($result as $row) {
            $locations[$row['id']] = $row['name'];
        }

        return $locations;
    }

    private function getDepartments(): array
    {
        $db = \Config\Database::connect();
        $result = $db->query('SELECT id, department_name FROM departments ORDER BY department_name')->getResultArray();
        $departments = [];
        foreach ($result as $row) {
            $departments[$row['id']] = $row['department_name'];
        }

        return $departments;
    }

    private function getWorkstations(): array
    {
        $db = \Config\Database::connect();
        $result = $db->query('SELECT id, workstation_code FROM workstations ORDER BY workstation_code')->getResultArray();
        $workstations = [];
        foreach ($result as $row) {
            $workstations[$row['id']] = $row['workstation_code'];
        }

        return $workstations;
    }
}
