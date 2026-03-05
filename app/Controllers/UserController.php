<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\AssignableUser;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\UserRole;
use App\Libraries\EntraDirectorySyncService;

class UserController extends BaseController
{
    private const BASE_USER_TYPES = ['superadmin', 'readandwrite', 'readonly'];

    protected $userModel;
    protected $assignableUserModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->assignableUserModel = new AssignableUser();
    }

    public function index()
    {
        if ($redirect = $this->requireSuperadmin()) {
            return $redirect;
        }

        $systemUsers = $this->userModel->orderBy('id','DESC')->findAll();
        $assignableUsers = $this->assignableUserModel->orderBy('full_name', 'ASC')->findAll();
        
        // Create a map of system users by username
        $systemUserMap = [];
        foreach ($systemUsers as $user) {
            $systemUserMap[$user['username']] = true;
        }
        
        // Add sync status to system users
        $assignableMap = [];
        foreach ($assignableUsers as $au) {
            $assignableMap[$au['full_name']] = $au['id'];
        }
        
        foreach ($systemUsers as &$user) {
            $user['is_system_user'] = true;
            $user['is_assignable'] = isset($assignableMap[$user['username']]);
            $user['assignable_id'] = $assignableMap[$user['username']] ?? null;
            $user['display_name'] = $user['username'];
        }
        
        // Add assignable-only users (those without system accounts)
        $allUsers = $systemUsers;
        foreach ($assignableUsers as $au) {
            if (!isset($systemUserMap[$au['full_name']])) {
                // This is an assignable-only user
                $allUsers[] = [
                    'id' => 'A-' . $au['id'], // Prefix with A- to distinguish
                    'assignable_id' => $au['id'],
                    'username' => null,
                    'email' => null,
                    'usertype' => null,
                    'display_name' => $au['full_name'],
                    'is_system_user' => false,
                    'is_assignable' => true,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        
        $addedRoleMap = $this->getAddedRoleMap();
        foreach ($allUsers as &$user) {
            $roleId = isset($user['user_role_id']) ? (int) $user['user_role_id'] : 0;
            $user['added_role_name'] = $roleId > 0 ? ($addedRoleMap[$roleId] ?? null) : null;
        }
        unset($user);

        $data['users'] = $allUsers;
        $data['assignableMap'] = $assignableMap;
        
        return view('users/index', $data);
    }

    public function search()
    {
        if ($redirect = $this->requireSuperadmin()) {
            return $this->response->setJSON([]);
        }

        $q = $this->request->getGet('q');

        $builder = $this->userModel;

        if (!empty($q)) {
            $builder = $builder->like('username', $q)
                ->orLike('email', $q)
                ->orLike('usertype', $q)
                ->orLike('created_at', $q);
        }

        return $this->response->setJSON($builder->orderBy('id','DESC')->findAll());
    }

    public function create()
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data['mainUsertypes'] = $this->getMainUsertypeOptions();
        $data['addedUserRoles'] = $this->getAddedRoles();

        return view('users/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $userType = $this->request->getPost('user_type');

        if ($userType === 'system') {
            $selectedMainType = strtolower(trim((string) $this->request->getPost('usertype')));
            $selectedAddedRoleId = $this->normalizeAddedRoleId($this->request->getPost('user_role_id'));

            // Create system user
            $data = [
                'username' => trim((string) $this->request->getPost('username')),
                'email'    => strtolower(trim((string) $this->request->getPost('email'))),
                'password' => $this->request->getPost('password'),
                'usertype' => $selectedMainType,
                'user_role_id' => $selectedAddedRoleId,
            ];

            if ($selectedMainType === '') {
                return redirect()->back()->withInput()->with('errors', ['usertype' => 'User role is required.']);
            }

            $rules = [
                'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'usertype' => 'required|in_list[superadmin,readandwrite,readonly]',
                'user_role_id' => 'permit_empty|integer',
            ];

            $messages = [
                'username' => [
                    'is_unique' => 'Username already exists.',
                ],
                'email' => [
                    'is_unique' => 'Email already exists.',
                ],
            ];

            if (!$this->validateData($data, $rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            if (!$this->isValidMainUsertype((string) $data['usertype'])) {
                return redirect()->back()->withInput()->with('errors', ['usertype' => 'Selected main user type is invalid.']);
            }

            if (!$this->isValidAddedRoleId($data['user_role_id'])) {
                return redirect()->back()->withInput()->with('errors', ['user_role_id' => 'Selected added role is invalid.']);
            }

            if ($this->userModel->insert($data)) {
                return redirect()->to('/users')->with('success', 'System user created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
            }
        } else {
            // Create non-system user (assignable only)
            $data = [
                'full_name' => trim((string) $this->request->getPost('full_name')),
            ];

            $rules = [
                'full_name' => 'required|max_length[150]|is_unique[assignable_users.full_name]',
            ];

            $messages = [
                'full_name' => [
                    'is_unique' => 'Name already exists.',
                ],
            ];

            if (!$this->validateData($data, $rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            if ($this->assignableUserModel->insert($data)) {
                return redirect()->to('/users')->with('success', 'Non-system user created and added to assignable users');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->assignableUserModel->errors());
            }
        }
    }

    public function edit($id)
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Check if user is synced to assignable users
        $assignableUser = $this->assignableUserModel->where('full_name', $data['user']['username'])->first();
        $data['is_assignable'] = !empty($assignableUser);

        $data['mainUsertypes'] = $this->getMainUsertypeOptions();
        $data['addedUserRoles'] = $this->getAddedRoles();

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
    
        $oldUser = $this->userModel->find($id);
        if (!$oldUser) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }
        
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'usertype' => strtolower(trim((string) $this->request->getPost('usertype'))),
            'user_role_id' => $this->normalizeAddedRoleId($this->request->getPost('user_role_id')),
            'password' => $this->request->getPost('password'),
        ];

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . (int) $id . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . (int) $id . ']',
            'password' => 'permit_empty|min_length[8]',
            'usertype' => 'required|in_list[superadmin,readandwrite,readonly]',
            'user_role_id' => 'permit_empty|integer',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'Username already exists.',
            ],
            'email' => [
                'is_unique' => 'Email already exists.',
            ],
        ];

        if (!$this->validateData($data, $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (!$this->isValidMainUsertype((string) $data['usertype'])) {
            return redirect()->back()->withInput()->with('errors', ['usertype' => 'Selected main user type is invalid.']);
        }

        if (!$this->isValidAddedRoleId($data['user_role_id'])) {
            return redirect()->back()->withInput()->with('errors', ['user_role_id' => 'Selected added role is invalid.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $syncToAssignable = $this->request->getPost('sync_to_assignable');

        $this->userModel->skipValidation(true);

        if ($this->userModel->update($id, $data)) {
            // Handle sync to assignable users
            $assignableUser = $this->assignableUserModel->where('full_name', $oldUser['username'])->first();
            
            if ($syncToAssignable) {
                if ($assignableUser) {
                    // Update if username changed
                    if ($oldUser['username'] !== $data['username']) {
                        $this->assignableUserModel->update($assignableUser['id'], ['full_name' => $data['username']]);
                    }
                } else {
                    // Create new assignable user
                    $this->syncUserToAssignable($data['username']);
                }
            } else {
                // Remove from assignable users if unchecked
                if ($assignableUser) {
                    $this->assignableUserModel->delete($assignableUser['id']);
                }
            }
            
            return redirect()->to('/users')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    public function delete($id)
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/users')->with('success', 'User deleted successfully');
        } else {
            return redirect()->to('/users')->with('error', 'Failed to delete user');
        }
    }

    /**
     * Sync a user to assignable users
     */
    private function syncUserToAssignable($username)
    {
        // Check if already exists
        $existing = $this->assignableUserModel->where('full_name', $username)->first();
        if (!$existing) {
            $this->assignableUserModel->insert(['full_name' => $username]);
        }
    }

    /**
     * Toggle sync status for a user
     */
    public function toggleSync($id)
    {
        if ($redirect = $this->requireSuperadmin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $assignableUser = $this->assignableUserModel->where('full_name', $user['username'])->first();
        
        if ($assignableUser) {
            // Remove from assignable users
            $this->assignableUserModel->delete($assignableUser['id']);
            return $this->response->setJSON(['success' => true, 'synced' => false, 'message' => 'Removed from assignable users']);
        } else {
            // Add to assignable users
            $this->syncUserToAssignable($user['username']);
            return $this->response->setJSON(['success' => true, 'synced' => true, 'message' => 'Added to assignable users']);
        }
    }

    public function details($id)
    {
        if ($redirect = $this->requireSuperadmin()) {
            return $redirect;
        }

        $assetModel = new Asset();
        $assetHistoryModel = new AssetHistory();
        $peripheralModel = new \App\Models\Peripheral();

        $profile = [];
        $assignableId = null;
        $systemUserId = null;

        if (is_string($id) && str_starts_with($id, 'A-')) {
            $assignableId = (int) substr($id, 2);
            $assignableUser = $this->assignableUserModel->find($assignableId);
            if (!$assignableUser) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
            }

            $profile = [
                'display_name' => $assignableUser['full_name'],
                'is_system_user' => false,
                'assignable_id' => $assignableId,
                'username' => null,
                'email' => null,
                'usertype' => null,
                'created_at' => $assignableUser['created_at'] ?? null,
                'updated_at' => $assignableUser['updated_at'] ?? null,
            ];
        } else {
            $systemUserId = (int) $id;
            $systemUser = $this->userModel->find($systemUserId);
            if (!$systemUser) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
            }

            $assignableUser = $this->assignableUserModel->where('full_name', $systemUser['username'])->first();
            $assignableId = $assignableUser['id'] ?? null;

            $profile = [
                'display_name' => $systemUser['username'],
                'is_system_user' => true,
                'assignable_id' => $assignableId,
                'username' => $systemUser['username'],
                'email' => $systemUser['email'],
                'usertype' => $systemUser['usertype'],
                'user_role_id' => $systemUser['user_role_id'] ?? null,
                'entra_object_id' => $systemUser['entra_object_id'] ?? null,
                'entra_display_name' => $systemUser['entra_display_name'] ?? null,
                'entra_user_principal_name' => $systemUser['entra_user_principal_name'] ?? null,
                'entra_account_enabled' => $systemUser['entra_account_enabled'] ?? null,
                'entra_last_password_change_at' => $systemUser['entra_last_password_change_at'] ?? null,
                'entra_user_type' => $systemUser['entra_user_type'] ?? null,
                'entra_given_name' => $systemUser['entra_given_name'] ?? null,
                'entra_surname' => $systemUser['entra_surname'] ?? null,
                'entra_job_title' => $systemUser['entra_job_title'] ?? null,
                'entra_company_name' => $systemUser['entra_company_name'] ?? null,
                'entra_department' => $systemUser['entra_department'] ?? null,
                'entra_employee_id' => $systemUser['entra_employee_id'] ?? null,
                'entra_office_location' => $systemUser['entra_office_location'] ?? null,
                'entra_city' => $systemUser['entra_city'] ?? null,
                'entra_state' => $systemUser['entra_state'] ?? null,
                'entra_postal_code' => $systemUser['entra_postal_code'] ?? null,
                'entra_country' => $systemUser['entra_country'] ?? null,
                'entra_mobile_phone' => $systemUser['entra_mobile_phone'] ?? null,
                'entra_mail' => $systemUser['entra_mail'] ?? null,
                'entra_manager_object_id' => $systemUser['entra_manager_object_id'] ?? null,
                'entra_manager_display_name' => $systemUser['entra_manager_display_name'] ?? null,
                'entra_manager_user_principal_name' => $systemUser['entra_manager_user_principal_name'] ?? null,
                'entra_manager_mail' => $systemUser['entra_manager_mail'] ?? null,
                'entra_identities' => $systemUser['entra_identities'] ?? null,
                'entra_business_phones' => $systemUser['entra_business_phones'] ?? null,
                'created_at' => $systemUser['created_at'] ?? null,
                'updated_at' => $systemUser['updated_at'] ?? null,
            ];

            $addedRoleMap = $this->getAddedRoleMap();
            $profileRoleId = isset($profile['user_role_id']) ? (int) $profile['user_role_id'] : 0;
            $profile['added_role_name'] = $profileRoleId > 0 ? ($addedRoleMap[$profileRoleId] ?? null) : null;
        }

        $assets = [];
        $peripherals = [];
        if (!empty($assignableId)) {
            $assets = $assetModel->where('assigned_to_user_id', $assignableId)
                ->orderBy('id', 'DESC')
                ->findAll();
            $peripherals = $peripheralModel
                ->select('peripherals.*, assets.asset_tag')
                ->join('assets', 'assets.id = peripherals.asset_id', 'left')
                ->where('peripherals.assigned_to_user_id', $assignableId)
                ->orderBy('peripherals.id', 'DESC')
                ->findAll();
        }

        $history = [];
        if (!empty($systemUserId)) {
            $history = $assetHistoryModel->select('asset_history.*, assets.asset_tag, assets.model')
                ->join('assets', 'assets.id = asset_history.asset_id', 'left')
                ->where('asset_history.user_id', $systemUserId)
                ->orderBy('asset_history.created_at', 'DESC')
                ->findAll();
        }

        $assignmentHistory = [];
        if (!empty($assignableId)) {
            $assignableIdValue = (string) $assignableId;
            $assignmentHistory = $assetHistoryModel->select('asset_history.*, assets.asset_tag, assets.model')
                ->join('assets', 'assets.id = asset_history.asset_id', 'left')
                ->groupStart()
                ->where('asset_history.field_name', 'assigned_to_user_id')
                ->where('asset_history.new_value', $assignableIdValue)
                ->orWhere('asset_history.old_value', $assignableIdValue)
                ->groupEnd()
                ->orderBy('asset_history.created_at', 'DESC')
                ->findAll();
        }

        $data = [
            'profile' => $profile,
            'assets' => $assets,
            'peripherals' => $peripherals,
            'history' => $history,
            'assignment_history' => $assignmentHistory,
            'title' => 'User Details',
        ];

        return view('users/details', $data);
    }

    public function syncEntra()
    {
        if ($redirect = $this->requireSuperadmin()) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        try {
            set_time_limit(0);

            $service = new EntraDirectorySyncService();
            $result = $service->syncAllUsers();

            $message = sprintf(
                'Azure AD sync completed. Fetched: %d, Created: %d, Updated: %d, Skipped: %d, Failed: %d.',
                (int) ($result['fetched'] ?? 0),
                (int) ($result['created'] ?? 0),
                (int) ($result['updated'] ?? 0),
                (int) ($result['skipped'] ?? 0),
                (int) ($result['failed'] ?? 0)
            );

            if (!empty($result['failed'])) {
                $errors = $result['errors'] ?? [];
                if (is_array($errors) && !empty($errors)) {
                    $message .= ' First error: ' . (string) $errors[0];
                }
            }

            return redirect()->to('/users')->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->to('/users')->with('error', 'Azure AD sync failed: ' . $e->getMessage());
        }
    }

    private function getMainUsertypeOptions(): array
    {
        return [
            ['role_name' => 'Superadmin', 'role_key' => 'superadmin'],
            ['role_name' => 'Read and Write', 'role_key' => 'readandwrite'],
            ['role_name' => 'Readonly', 'role_key' => 'readonly'],
        ];
    }

    private function getAddedRoles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return [];
        }

        $roleModel = new UserRole();
        return $roleModel
            ->whereNotIn('role_key', self::BASE_USER_TYPES)
            ->orderBy('role_name', 'ASC')
            ->findAll();
    }

    private function isValidMainUsertype(string $roleKey): bool
    {
        return in_array(strtolower(trim($roleKey)), self::BASE_USER_TYPES, true);
    }

    private function normalizeAddedRoleId($rawRoleId): ?int
    {
        if ($rawRoleId === null || $rawRoleId === '') {
            return null;
        }

        if (!is_numeric($rawRoleId)) {
            return null;
        }

        $roleId = (int) $rawRoleId;
        return $roleId > 0 ? $roleId : null;
    }

    private function isValidAddedRoleId(?int $roleId): bool
    {
        if ($roleId === null) {
            return true;
        }

        $roles = $this->getAddedRoles();
        $ids = array_map(static fn($role) => (int) ($role['id'] ?? 0), $roles);
        return in_array($roleId, $ids, true);
    }

    private function getAddedRoleMap(): array
    {
        $roles = $this->getAddedRoles();
        $map = [];
        foreach ($roles as $role) {
            $id = (int) ($role['id'] ?? 0);
            if ($id > 0) {
                $map[$id] = (string) ($role['role_name'] ?? '');
            }
        }

        return $map;
    }
}