<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\AssignableUser;
use App\Models\Unit;
use App\Models\Asset;
use App\Models\Peripheral;
use App\Models\Application;
use App\Models\Document;
use App\Models\Dcf;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userModel = new User();
        $assignableUserModel = new AssignableUser();
        $unitModel = new Unit();
        $assetModel = new Asset();
        $peripheralModel = new Peripheral();
        $applicationModel = new Application();
        $documentModel = new Document();
        $dcfModel = new Dcf();
        $db = \Config\Database::connect();

        // Get total users count
        $totalUsers = $userModel->countAllResults();

        // Get total assignable users count
        $totalAssignableUsers = $assignableUserModel->countAllResults();

        // Get Azure AD users count
        $azureAdUsers = $db->table('users')
            ->where('entra_object_id IS NOT NULL')
            ->countAllResults();

        // Get non-Azure AD users count
        $nonAzureAdUsers = $db->table('users')
            ->where('entra_object_id IS NULL')
            ->countAllResults();

        // Get total units count
        $totalUnits = $unitModel->countAllResults();

        // Get total assets count
        $totalAssets = $assetModel->countAllResults();

        // Get total peripherals count
        $totalPeripherals = $peripheralModel->countAllResults();

        // Get total applications count
        $totalApplications = $applicationModel->countAllResults();

        // Get total documents count
        $totalDocuments = $documentModel->countAllResults();

        // Get total DCF count
        $totalDcfs = $dcfModel->countAllResults();

        $data = [
            'totalUsers' => $totalUsers,
            'totalAssignableUsers' => $totalAssignableUsers,
            'azureAdUsers' => $azureAdUsers,
            'nonAzureAdUsers' => $nonAzureAdUsers,
            'totalUnits' => $totalUnits,
            'totalAssets' => $totalAssets,
            'totalPeripherals' => $totalPeripherals,
            'totalApplications' => $totalApplications,
            'totalDocuments' => $totalDocuments,
            'totalDcfs' => $totalDcfs,
        ];

        return view('dashboard/index', $data);
    }
}