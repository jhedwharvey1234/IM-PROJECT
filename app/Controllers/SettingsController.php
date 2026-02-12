<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Technology;
use App\Models\ApplicationStatus;
use App\Models\Server;
use App\Models\Environment;
use App\Models\ApplicationContact;
use App\Models\Application;

class SettingsController extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['title'] = 'Settings';
        return view('settings/index', $data);
    }

    public function categories()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        $data['categories'] = $categoryModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Category Management';

        return view('settings/categories', $data);
    }

    public function saveCategory()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        $id = $this->request->getPost('id');
        
        // Retrieve and sanitize POST data
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $color = $this->request->getPost('color');
        $isActive = $this->request->getPost('is_active');
        
        $data = [
            'name' => is_string($name) ? trim($name) : '',
            'description' => is_string($description) ? trim($description) : '',
            'color' => is_string($color) ? trim($color) : '#0d6efd',
            'is_active' => $isActive ? 1 : 0,
        ];

        if ($id) {
            $data['id'] = $id;
            // Update existing category
            if ($categoryModel->update($id, $data)) {
                return redirect()->to('/settings/categories')->with('success', 'Category updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $categoryModel->errors());
            }
        } else {
            // Create new category
            if ($categoryModel->insert($data)) {
                return redirect()->to('/settings/categories')->with('success', 'Category created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $categoryModel->errors());
            }
        }
    }

    public function deleteCategory($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $categoryModel = new Category();
        
        if ($categoryModel->delete($id)) {
            return redirect()->to('/settings/categories')->with('success', 'Category deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete category');
        }
    }

    // Technologies Management
    public function technologies()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $technologyModel = new Technology();
        $data['technologies'] = $technologyModel->orderBy('technology_name', 'ASC')->findAll();
        $data['title'] = 'Technology Management';

        return view('settings/technologies/index', $data);
    }

    public function storeTechnology()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $technologyModel = new Technology();
        
        // Retrieve and sanitize POST data
        $technologyName = $this->request->getPost('technology_name');
        $data = [
            'technology_name' => is_string($technologyName) ? trim($technologyName) : ''
        ];
        
        // Validate required field
        if (empty($data['technology_name'])) {
            return redirect()->back()->withInput()->with('error', 'Technology name is required');
        }

        if ($technologyModel->insert($data)) {
            return redirect()->to('/settings/technologies')->with('success', 'Technology added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add technology');
        }
    }

    public function updateTechnology($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $technologyModel = new Technology();
        
        // Retrieve and sanitize POST data
        $technologyName = $this->request->getPost('technology_name');
        $data = [
            'technology_name' => is_string($technologyName) ? trim($technologyName) : ''
        ];
        
        // Validate required field
        if (empty($data['technology_name'])) {
            return redirect()->back()->withInput()->with('error', 'Technology name is required');
        }

        if ($technologyModel->update($id, $data)) {
            return redirect()->to('/settings/technologies')->with('success', 'Technology updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update technology');
        }
    }

    public function deleteTechnology($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $technologyModel = new Technology();
        
        if ($technologyModel->delete($id)) {
            return redirect()->to('/settings/technologies')->with('success', 'Technology deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete technology');
        }
    }

    // Application Status Management
    public function applicationStatus()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $statusModel = new ApplicationStatus();
        $data['statuses'] = $statusModel->orderBy('status_name', 'ASC')->findAll();
        $data['title'] = 'Application Status Management';

        return view('settings/application_status/index', $data);
    }

    public function storeApplicationStatus()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $statusModel = new ApplicationStatus();
        
        // Retrieve and sanitize POST data
        $statusName = $this->request->getPost('status_name');
        $data = [
            'status_name' => is_string($statusName) ? trim($statusName) : ''
        ];
        
        // Validate required field
        if (empty($data['status_name'])) {
            return redirect()->back()->withInput()->with('error', 'Status name is required');
        }

        if ($statusModel->insert($data)) {
            return redirect()->to('/settings/application-status')->with('success', 'Status added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add status');
        }
    }

    public function updateApplicationStatus($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $statusModel = new ApplicationStatus();
        
        // Retrieve and sanitize POST data
        $statusName = $this->request->getPost('status_name');
        $data = [
            'status_name' => is_string($statusName) ? trim($statusName) : ''
        ];
        
        // Validate required field
        if (empty($data['status_name'])) {
            return redirect()->back()->withInput()->with('error', 'Status name is required');
        }

        if ($statusModel->update($id, $data)) {
            return redirect()->to('/settings/application-status')->with('success', 'Status updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update status');
        }
    }

    public function deleteApplicationStatus($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $statusModel = new ApplicationStatus();
        
        if ($statusModel->delete($id)) {
            return redirect()->to('/settings/application-status')->with('success', 'Status deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete status');
        }
    }

    // Servers Management
    public function servers()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $serverModel = new Server();
        $data['servers'] = $serverModel->orderBy('server_name', 'ASC')->findAll();
        $data['title'] = 'Server Management';

        return view('settings/servers/index', $data);
    }

    public function storeServer()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $serverModel = new Server();
        
        // Get ALL POST data for debugging
        $allPost = $this->request->getPost();
        log_message('debug', 'Server Store - All POST data: ' . json_encode($allPost));
        
        // Retrieve and sanitize POST data
        $serverName = $this->request->getPost('server_name');
        $ipAddress = $this->request->getPost('ip_address');
        $serverType = $this->request->getPost('server_type');
        
        log_message('debug', 'Server Store - serverName type: ' . gettype($serverName) . ' value: ' . var_export($serverName, true));
        log_message('debug', 'Server Store - ipAddress type: ' . gettype($ipAddress) . ' value: ' . var_export($ipAddress, true));
        log_message('debug', 'Server Store - serverType type: ' . gettype($serverType) . ' value: ' . var_export($serverType, true));
        
        // Validate that POST data isn't arrays (security check)
        if (is_array($serverName) || is_array($ipAddress) || is_array($serverType)) {
            log_message('error', 'Server Store - Array detected in POST data');
            return redirect()->back()->withInput()->with('error', 'Invalid data format - array detected');
        }
        
        // Build data array with explicit string keys and values
        $data = [];
        
        // Handle server_name
        if (is_string($serverName)) {
            $trimmed = trim($serverName);
            if (!empty($trimmed)) {
                $data['server_name'] = $trimmed;
            }
        }
        
        // Validate required field
        if (!isset($data['server_name']) || empty($data['server_name'])) {
            log_message('error', 'Server Store - server_name is empty or not set');
            return redirect()->back()->withInput()->with('error', 'Server name is required');
        }
        
        // Add optional fields only if they have values
        if (is_string($ipAddress)) {
            $trimmed = trim($ipAddress);
            if (!empty($trimmed)) {
                $data['ip_address'] = $trimmed;
            }
        }
        
        if (is_string($serverType)) {
            $trimmed = trim($serverType);
            if (!empty($trimmed)) {
                $data['server_type'] = $trimmed;
            }
        }
        
        log_message('debug', 'Server Store - Final data array: ' . json_encode($data));
        log_message('debug', 'Server Store - Data array keys: ' . json_encode(array_keys($data)));
        
        // Check all keys are strings
        foreach (array_keys($data) as $key) {
            if (!is_string($key)) {
                log_message('error', 'Server Store - Non-string key detected: ' . var_export($key, true));
                return redirect()->back()->withInput()->with('error', 'Invalid data structure detected');
            }
        }

        try {
            $result = $serverModel->insert($data);
            if ($result) {
                log_message('info', 'Server Store - Insert successful. ID: ' . $result);
                return redirect()->to('/settings/servers')->with('success', 'Server added successfully');
            } else {
                $errors = $serverModel->errors();
                log_message('error', 'Server Store - Insert failed. Errors: ' . json_encode($errors));
                $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Failed to add server';
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }
        } catch (\TypeError $e) {
            log_message('error', 'Server Store - TypeError: ' . $e->getMessage());
            log_message('error', 'Server Store - Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'System error: Invalid data type. Please check your input.');
        } catch (\Exception $e) {
            log_message('error', 'Server Store - Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'System error: ' . $e->getMessage());
        }
    }

    public function updateServer($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $serverModel = new Server();
        
        // Retrieve and sanitize POST data
        $serverName = $this->request->getPost('server_name');
        $ipAddress = $this->request->getPost('ip_address');
        $serverType = $this->request->getPost('server_type');
        
        // Validate that POST data isn't arrays (security check)
        if (is_array($serverName) || is_array($ipAddress) || is_array($serverType)) {
            return redirect()->back()->withInput()->with('error', 'Invalid data format');
        }
        
        // Build data array with explicit string keys and values
        $data = [];
        $data['server_name'] = is_string($serverName) && !empty(trim($serverName)) ? trim($serverName) : '';
        
        // Validate required field
        if (empty($data['server_name'])) {
            return redirect()->back()->withInput()->with('error', 'Server name is required');
        }
        
        // Add optional fields only if they have values
        if (is_string($ipAddress) && !empty(trim($ipAddress))) {
            $data['ip_address'] = trim($ipAddress);
        }
        
        if (is_string($serverType) && !empty(trim($serverType))) {
            $data['server_type'] = trim($serverType);
        }

        if ($serverModel->update($id, $data)) {
            return redirect()->to('/settings/servers')->with('success', 'Server updated successfully');
        } else {
            $errors = $serverModel->errors();
            $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Failed to update server';
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    public function deleteServer($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $serverModel = new Server();
        
        if ($serverModel->delete($id)) {
            return redirect()->to('/settings/servers')->with('success', 'Server deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete server');
        }
    }

    // Environments Management
    public function environments()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $environmentModel = new Environment();
        $applicationModel = new Application();
        $serverModel = new Server();

        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT e.*, a.app_name as application_name, s.server_name, e.environment_type as environment_name
            FROM environments e
            LEFT JOIN applications a ON a.id = e.application_id
            LEFT JOIN servers s ON s.id = e.server_id
            ORDER BY e.id DESC
        ");
        $data['environments'] = $query->getResultArray();
        $data['applications'] = $applicationModel->orderBy('app_name', 'ASC')->findAll();
        $data['servers'] = $serverModel->orderBy('server_name', 'ASC')->findAll();
        $data['title'] = 'Environment Management';

        return view('settings/environments/index', $data);
    }

    public function storeEnvironment()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $environmentModel = new Environment();
        
        // Retrieve and sanitize POST data
        $environmentType = $this->request->getPost('environment_name');
        $applicationId = $this->request->getPost('application_id');
        $serverId = $this->request->getPost('server_id');
        $url = $this->request->getPost('url');
        
        // Build data array conditionally
        $data = [];
        
        if (is_string($environmentType) && !empty(trim($environmentType))) {
            $data['environment_type'] = trim($environmentType);
        }
        
        if (is_numeric($applicationId) && (int)$applicationId > 0) {
            $data['application_id'] = (int)$applicationId;
        }
        
        if (is_numeric($serverId) && (int)$serverId > 0) {
            $data['server_id'] = (int)$serverId;
        }
        
        if (is_string($url) && !empty(trim($url))) {
            $data['url'] = trim($url);
        }

        if ($environmentModel->insert($data)) {
            return redirect()->to('/settings/environments')->with('success', 'Environment added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add environment');
        }
    }

    public function updateEnvironment($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $environmentModel = new Environment();
        
        // Retrieve and sanitize POST data
        $environmentType = $this->request->getPost('environment_name');
        $applicationId = $this->request->getPost('application_id');
        $serverId = $this->request->getPost('server_id');
        $url = $this->request->getPost('url');
        
        // Build data array conditionally
        $data = [];
        
        if (is_string($environmentType) && !empty(trim($environmentType))) {
            $data['environment_type'] = trim($environmentType);
        }
        
        if (is_numeric($applicationId) && (int)$applicationId > 0) {
            $data['application_id'] = (int)$applicationId;
        }
        
        if (is_numeric($serverId) && (int)$serverId > 0) {
            $data['server_id'] = (int)$serverId;
        }
        
        if (is_string($url) && !empty(trim($url))) {
            $data['url'] = trim($url);
        }

        if ($environmentModel->update($id, $data)) {
            return redirect()->to('/settings/environments')->with('success', 'Environment updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update environment');
        }
    }

    public function deleteEnvironment($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $environmentModel = new Environment();
        
        if ($environmentModel->delete($id)) {
            return redirect()->to('/settings/environments')->with('success', 'Environment deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete environment');
        }
    }

    // Application Contacts Management
    public function applicationContacts()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $contactModel = new ApplicationContact();
        $applicationModel = new Application();

        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT c.*, a.app_name as application_name
            FROM application_contacts c
            LEFT JOIN applications a ON a.id = c.application_id
            ORDER BY c.id DESC
        ");
        $data['contacts'] = $query->getResultArray();
        $data['applications'] = $applicationModel->orderBy('app_name', 'ASC')->findAll();
        $data['title'] = 'Application Contact Management';

        return view('settings/application_contacts/index', $data);
    }

    public function storeApplicationContact()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $contactModel = new ApplicationContact();
        
        // Retrieve and sanitize POST data
        $applicationId = $this->request->getPost('application_id');
        $contactName = $this->request->getPost('contact_name');
        $role = $this->request->getPost('role');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        
        // Build data array conditionally
        $data = [];
        
        if (is_numeric($applicationId) && (int)$applicationId > 0) {
            $data['application_id'] = (int)$applicationId;
        }
        
        if (is_string($contactName) && !empty(trim($contactName))) {
            $data['contact_name'] = trim($contactName);
        }
        
        if (is_string($role) && !empty(trim($role))) {
            $data['role'] = trim($role);
        }
        
        if (is_string($email) && !empty(trim($email))) {
            $data['email'] = trim($email);
        }
        
        if (is_string($phone) && !empty(trim($phone))) {
            $data['phone'] = trim($phone);
        }

        if ($contactModel->insert($data)) {
            return redirect()->to('/settings/application-contacts')->with('success', 'Contact added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add contact');
        }
    }

    public function updateApplicationContact($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $contactModel = new ApplicationContact();
        
        // Retrieve and sanitize POST data
        $applicationId = $this->request->getPost('application_id');
        $contactName = $this->request->getPost('contact_name');
        $role = $this->request->getPost('role');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        
        // Build data array conditionally
        $data = [];
        
        if (is_numeric($applicationId) && (int)$applicationId > 0) {
            $data['application_id'] = (int)$applicationId;
        }
        
        if (is_string($contactName) && !empty(trim($contactName))) {
            $data['contact_name'] = trim($contactName);
        }
        
        if (is_string($role) && !empty(trim($role))) {
            $data['role'] = trim($role);
        }
        
        if (is_string($email) && !empty(trim($email))) {
            $data['email'] = trim($email);
        }
        
        if (is_string($phone) && !empty(trim($phone))) {
            $data['phone'] = trim($phone);
        }

        if ($contactModel->update($id, $data)) {
            return redirect()->to('/settings/application-contacts')->with('success', 'Contact updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update contact');
        }
    }

    public function deleteApplicationContact($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $contactModel = new ApplicationContact();
        
        if ($contactModel->delete($id)) {
            return redirect()->to('/settings/application-contacts')->with('success', 'Contact deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete contact');
        }
    }
}
