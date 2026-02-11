<?php

namespace App\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Department;
use App\Models\Technology;
use App\Models\ApplicationTechnology;
use App\Models\ApplicationLog;

class ApplicationController extends BaseController
{
    protected $applicationModel;
    protected $statusModel;
    protected $departmentModel;
    protected $technologyModel;
    protected $appTechModel;
    protected $logModel;

    public function __construct()
    {
        $this->applicationModel = new Application();
        $this->statusModel = new ApplicationStatus();
        $this->departmentModel = new Department();
        $this->technologyModel = new Technology();
        $this->appTechModel = new ApplicationTechnology();
        $this->logModel = new ApplicationLog();
    }

    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $page = (int) $this->request->getGet('page') ?? 1;
        $page = max(1, $page); // Ensure page is at least 1
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $data['applications'] = $this->applicationModel->getApplications($perPage, $offset);
        $data['total'] = $this->applicationModel->countApplications();
        $data['currentPage'] = $page;
        $data['perPage'] = $perPage;
        $data['title'] = 'Applications';

        return view('applications/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['statuses'] = $this->statusModel->orderBy('status_name', 'ASC')->findAll();
        $data['departments'] = $this->departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['technologies'] = $this->technologyModel->orderBy('technology_name', 'ASC')->findAll();
        $data['title'] = 'Create Application';

        return view('applications/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data = [
            'app_code' => $this->request->getPost('app_code'),
            'app_name' => $this->request->getPost('app_name'),
            'description' => $this->request->getPost('description'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'owner_name' => $this->request->getPost('owner_name'),
            'business_criticality' => $this->request->getPost('business_criticality'),
            'repository_url' => $this->request->getPost('repository_url'),
            'production_url' => $this->request->getPost('production_url'),
            'version' => $this->request->getPost('version'),
            'status_id' => $this->request->getPost('status_id') ?: null,
            'date_created' => date('Y-m-d'),
            'last_updated' => date('Y-m-d'),
        ];

        if ($this->applicationModel->insert($data)) {
            $appId = $this->applicationModel->getInsertID();

            // Add technologies if selected
            $technologies = $this->request->getPost('technologies');
            if ($technologies) {
                foreach ($technologies as $techId) {
                    $this->appTechModel->insert([
                        'application_id' => $appId,
                        'technology_id' => $techId,
                    ]);
                }
            }

            // Log the action
            $this->logModel->insert([
                'application_id' => $appId,
                'action' => 'Application Created',
                'performed_by' => session()->get('user_name') ?? 'System',
                'action_date' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to('/applications')
                ->with('success', 'Application created successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->applicationModel->errors());
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['application'] = $this->applicationModel->getWithDepartment($id);

        if (!$data['application']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Application $id not found");
        }

        $data['statuses'] = $this->statusModel->orderBy('status_name', 'ASC')->findAll();
        $data['departments'] = $this->departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['technologies'] = $this->technologyModel->orderBy('technology_name', 'ASC')->findAll();
        $data['selectedTechnologies'] = $this->appTechModel->getTechnologiesByApplication($id);
        $data['title'] = 'Edit Application';

        return view('applications/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Application $id not found");
        }

        $data = [
            'app_code' => $this->request->getPost('app_code'),
            'app_name' => $this->request->getPost('app_name'),
            'description' => $this->request->getPost('description'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'owner_name' => $this->request->getPost('owner_name'),
            'business_criticality' => $this->request->getPost('business_criticality'),
            'repository_url' => $this->request->getPost('repository_url'),
            'production_url' => $this->request->getPost('production_url'),
            'version' => $this->request->getPost('version'),
            'status_id' => $this->request->getPost('status_id') ?: null,
            'last_updated' => date('Y-m-d'),
        ];

        if ($this->applicationModel->update($id, $data)) {
            // Update technologies
            $this->appTechModel->where('application_id', $id)->delete();
            $technologies = $this->request->getPost('technologies');
            if ($technologies) {
                foreach ($technologies as $techId) {
                    $this->appTechModel->insert([
                        'application_id' => $id,
                        'technology_id' => $techId,
                    ]);
                }
            }

            // Log the action
            $this->logModel->insert([
                'application_id' => $id,
                'action' => 'Application Updated',
                'performed_by' => session()->get('user_name') ?? 'System',
                'action_date' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to('/applications')
                ->with('success', 'Application updated successfully');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->applicationModel->errors());
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Application $id not found");
        }

        // Log the action
        $this->logModel->insert([
            'application_id' => $id,
            'action' => 'Application Deleted',
            'performed_by' => session()->get('user_name') ?? 'System',
            'action_date' => date('Y-m-d H:i:s'),
        ]);

        if ($this->applicationModel->delete($id)) {
            return redirect()->to('/applications')
                ->with('success', 'Application deleted successfully');
        }

        return redirect()->back()
            ->with('error', 'Failed to delete application');
    }

    public function details($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $data['application'] = $this->applicationModel->getWithDepartment($id);

        if (!$data['application']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Application $id not found");
        }

        $data['technologies'] = $this->appTechModel->getTechnologiesByApplication($id);
        $data['logs'] = $this->logModel->getLogsByApplication($id);
        $data['title'] = 'Application Details';

        return view('applications/details', $data);
    }

    public function search()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $keyword = $this->request->getGet('q') ?? '';
        $data['applications'] = [];

        if (strlen($keyword) > 2) {
            $data['applications'] = $this->applicationModel->searchApplications($keyword);
        }

        $data['keyword'] = $keyword;
        $data['title'] = 'Search Applications';

        return view('applications/search', $data);
    }
}
