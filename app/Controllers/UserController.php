<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\AssignableUser;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\UserRole;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;
    protected $assignableUserModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->assignableUserModel = new AssignableUser();
    }

    public function index()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
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
        
        $data['users'] = $allUsers;
        $data['assignableMap'] = $assignableMap;
        
        return view('users/index', $data);
    }

    public function search()
    {
        if (session()->get('usertype') !== 'superadmin') {
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
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

            $data['userRoles'] = $this->getAvailableRoles();

        return view('users/create', $data);
    }

    public function store()
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $userType = $this->request->getPost('user_type');

        if ($userType === 'system') {
            $selectedRole = trim((string) $this->request->getPost('usertype'));

            // Create system user
            $data = [
                'username' => trim((string) $this->request->getPost('username')),
                'email'    => strtolower(trim((string) $this->request->getPost('email'))),
                'password' => $this->request->getPost('password'),
                'usertype' => $selectedRole,
            ];

            if ($selectedRole === '') {
                return redirect()->back()->withInput()->with('errors', ['usertype' => 'User role is required.']);
            }

            $rules = [
                'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'usertype' => 'required|max_length[50]',
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

                if (!$this->isValidRoleKey((string) $data['usertype'])) {
                return redirect()->back()->withInput()->with('errors', ['usertype' => 'Selected user role is invalid.']);
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
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Check if user is synced to assignable users
        $assignableUser = $this->assignableUserModel->where('full_name', $data['user']['username'])->first();
        $data['is_assignable'] = !empty($assignableUser);

        $data['userRoles'] = $this->getAvailableRoles();

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
    
        $oldUser = $this->userModel->find($id);
        if (!$oldUser) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }
        
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'usertype' => $this->request->getPost('usertype'),
            'password' => $this->request->getPost('password'),
        ];

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . (int) $id . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . (int) $id . ']',
            'password' => 'permit_empty|min_length[8]',
            'usertype' => 'required|max_length[50]',
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

        if (!$this->isValidRoleKey((string) $data['usertype'])) {
            return redirect()->back()->withInput()->with('errors', ['usertype' => 'Selected user role is invalid.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $syncToAssignable = $this->request->getPost('sync_to_assignable');

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
        if (session()->get('usertype') !== 'superadmin') {
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
        if (session()->get('usertype') !== 'superadmin') {
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
        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
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
                'created_at' => $systemUser['created_at'] ?? null,
                'updated_at' => $systemUser['updated_at'] ?? null,
            ];
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

    private function getAvailableRoles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return [
                ['role_name' => 'Readonly', 'role_key' => 'readonly'],
                ['role_name' => 'Read and Write', 'role_key' => 'readandwrite'],
                ['role_name' => 'Superadmin', 'role_key' => 'superadmin'],
            ];
        }

        $roleModel = new UserRole();
        return $roleModel->orderBy('role_name', 'ASC')->findAll();
    }

    private function isValidRoleKey(string $roleKey): bool
    {
        $roles = $this->getAvailableRoles();
        $keys = array_column($roles, 'role_key');
        return in_array($roleKey, $keys, true);
    }
}