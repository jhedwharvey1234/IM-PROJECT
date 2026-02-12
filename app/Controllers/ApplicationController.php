<?php

namespace App\Controllers;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Department;
use App\Models\Technology;
use App\Models\ApplicationTechnology;
use App\Models\ApplicationLog;
use App\Models\ApplicationContactsExtended;
use App\Models\ApplicationEnvironments;
use App\Models\InfrastructureDetails;
use App\Models\ComplianceSecurity;
use App\Models\ApplicationDependencies;
use App\Models\ApplicationVendors;
use App\Models\BatchJobs;
use App\Models\LicensingInfo;
use App\Models\OperationalMetrics;
use App\Models\TechnologyDetails;

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
        $this->contactsModel = new ApplicationContactsExtended();
        $this->environmentsModel = new ApplicationEnvironments();
        $this->infrastructureModel = new InfrastructureDetails();
        $this->complianceModel = new ComplianceSecurity();
        $this->dependenciesModel = new ApplicationDependencies();
        $this->vendorsModel = new ApplicationVendors();
        $this->batchModel = new BatchJobs();
        $this->licensingModel = new LicensingInfo();
        $this->metricsModel = new OperationalMetrics();
        $this->techModel = new TechnologyDetails();
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
            'app_category' => $this->request->getPost('app_category'),
            'app_type' => $this->request->getPost('app_type'),
            'business_purpose' => $this->request->getPost('business_purpose'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'owner_name' => $this->request->getPost('owner_name'),
            'business_criticality' => $this->request->getPost('business_criticality'),
            'lifecycle_stage' => $this->request->getPost('lifecycle_stage'),
            'eol_date' => $this->request->getPost('eol_date') ?: null,
            'replacement_system' => $this->request->getPost('replacement_system'),
            'upgrade_roadmap' => $this->request->getPost('upgrade_roadmap'),
            'last_major_upgrade' => $this->request->getPost('last_major_upgrade') ?: null,
            'repository_url' => $this->request->getPost('repository_url'),
            'production_url' => $this->request->getPost('production_url'),
            'version' => $this->request->getPost('version'),
            'status_id' => $this->request->getPost('status_id') ?: null,
            'data_classification' => $this->request->getPost('data_classification'),
            'personal_data_flag' => $this->request->getPost('personal_data_flag') ? 1 : 0,
            'authentication_type' => $this->request->getPost('authentication_type'),
            'encryption_enabled' => $this->request->getPost('encryption_enabled') ? 1 : 0,
            'last_security_review' => $this->request->getPost('last_security_review') ?: null,
            'availability_sla' => $this->request->getPost('availability_sla'),
            'business_impact' => $this->request->getPost('business_impact'),
            'peak_users' => $this->request->getPost('peak_users') ?: null,
            'monitoring_tool' => $this->request->getPost('monitoring_tool'),
            'annual_cost' => $this->request->getPost('annual_cost') ?: null,
            'license_type' => $this->request->getPost('license_type'),
            'license_expiry' => $this->request->getPost('license_expiry') ?: null,
            'vendor_contract_ref' => $this->request->getPost('vendor_contract_ref'),
            'cloud_subscription_details' => $this->request->getPost('cloud_subscription_details'),
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

            // Add Technology Details
            $techDetails = [
                'application_id' => $appId,
                'programming_language' => $this->request->getPost('programming_language'),
                'framework' => $this->request->getPost('framework'),
                'frontend_technology' => $this->request->getPost('frontend_technology'),
                'database_type' => $this->request->getPost('database_type'),
                'middleware' => $this->request->getPost('middleware'),
                'container_technology' => $this->request->getPost('container_technology'),
            ];
            $this->techModel->insert($techDetails);

            // Add Infrastructure Details
            $infraData = [
                'application_id' => $appId,
                'server_type' => $this->request->getPost('server_type'),
                'operating_system' => $this->request->getPost('operating_system'),
                'os_version' => $this->request->getPost('os_version'),
                'cpu_cores' => $this->request->getPost('cpu_cores') ?: null,
                'memory_gb' => $this->request->getPost('memory_gb') ?: null,
                'storage_gb' => $this->request->getPost('storage_gb') ?: null,
                'database_server' => $this->request->getPost('database_server'),
                'storage_location' => $this->request->getPost('storage_location'),
                'backup_location' => $this->request->getPost('backup_location'),
            ];
            $this->infrastructureModel->insert($infraData);

            // Add Compliance & Security
            $complianceData = [
                'application_id' => $appId,
                'data_classification' => $this->request->getPost('data_classification'),
                'personal_data_flag' => $this->request->getPost('personal_data_flag') ? 1 : 0,
                'compliance_requirements' => $this->request->getPost('compliance_requirements'),
                'authentication_type' => $this->request->getPost('authentication_type'),
                'encryption_enabled' => $this->request->getPost('encryption_enabled') ? 1 : 0,
                'encryption_method' => $this->request->getPost('encryption_method'),
                'vulnerability_scan_status' => $this->request->getPost('vulnerability_scan_status'),
                'last_security_review' => $this->request->getPost('last_security_review') ?: null,
                'security_certifications' => $this->request->getPost('security_certifications'),
            ];
            $this->complianceModel->insert($complianceData);

            // Add Licensing Info
            $licensingData = [
                'application_id' => $appId,
                'license_type' => $this->request->getPost('license_type'),
                'license_key' => $this->request->getPost('license_key'),
                'license_expiry' => $this->request->getPost('license_expiry') ?: null,
                'vendor_name' => $this->request->getPost('vendor_name'),
                'vendor_contract_ref' => $this->request->getPost('vendor_contract_ref'),
                'annual_cost' => $this->request->getPost('annual_cost') ?: null,
                'cost_center' => $this->request->getPost('cost_center'),
                'purchase_date' => $this->request->getPost('purchase_date') ?: null,
                'renewal_date' => $this->request->getPost('renewal_date') ?: null,
            ];
            $this->licensingModel->insert($licensingData);

            // Add Operational Metrics
            $metricsData = [
                'application_id' => $appId,
                'availability_sla' => $this->request->getPost('availability_sla'),
                'criticality_level' => $this->request->getPost('criticality_level') ?? $this->request->getPost('business_criticality'),
                'business_impact' => $this->request->getPost('business_impact'),
                'peak_users' => $this->request->getPost('peak_users') ?: null,
                'peak_transactions' => $this->request->getPost('peak_transactions') ?: null,
                'monitoring_tool' => $this->request->getPost('monitoring_tool'),
                'mttr_target' => $this->request->getPost('mttr_target') ?: null,
                'rto_target' => $this->request->getPost('rto_target') ?: null,
                'rpo_target' => $this->request->getPost('rpo_target') ?: null,
            ];
            $this->metricsModel->insert($metricsData);

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
        
        // Load related data
        $data['techDetails'] = $this->techModel->getTechDetailsByApplication($id);
        $data['infrastructure'] = $this->infrastructureModel->getInfrastructureByApplication($id);
        $data['compliance'] = $this->complianceModel->getComplianceByApplication($id);
        $data['licensing'] = $this->licensingModel->getLicensingByApplication($id);
        $data['metrics'] = $this->metricsModel->getMetricsByApplication($id);
        $data['contacts'] = $this->contactsModel->getContactsByApplication($id);
        $data['environments'] = $this->environmentsModel->getEnvironmentsByApplication($id);
        $data['dependencies'] = $this->dependenciesModel->getDependenciesByApplication($id);
        $data['vendors'] = $this->vendorsModel->getVendorsByApplication($id);
        $data['batchJobs'] = $this->batchModel->getJobsByApplication($id);
        
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
            'app_category' => $this->request->getPost('app_category'),
            'app_type' => $this->request->getPost('app_type'),
            'business_purpose' => $this->request->getPost('business_purpose'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'owner_name' => $this->request->getPost('owner_name'),
            'business_criticality' => $this->request->getPost('business_criticality'),
            'lifecycle_stage' => $this->request->getPost('lifecycle_stage'),
            'eol_date' => $this->request->getPost('eol_date') ?: null,
            'replacement_system' => $this->request->getPost('replacement_system'),
            'upgrade_roadmap' => $this->request->getPost('upgrade_roadmap'),
            'last_major_upgrade' => $this->request->getPost('last_major_upgrade') ?: null,
            'repository_url' => $this->request->getPost('repository_url'),
            'production_url' => $this->request->getPost('production_url'),
            'version' => $this->request->getPost('version'),
            'status_id' => $this->request->getPost('status_id') ?: null,
            'data_classification' => $this->request->getPost('data_classification'),
            'personal_data_flag' => $this->request->getPost('personal_data_flag') ? 1 : 0,
            'authentication_type' => $this->request->getPost('authentication_type'),
            'encryption_enabled' => $this->request->getPost('encryption_enabled') ? 1 : 0,
            'last_security_review' => $this->request->getPost('last_security_review') ?: null,
            'availability_sla' => $this->request->getPost('availability_sla'),
            'business_impact' => $this->request->getPost('business_impact'),
            'peak_users' => $this->request->getPost('peak_users') ?: null,
            'monitoring_tool' => $this->request->getPost('monitoring_tool'),
            'annual_cost' => $this->request->getPost('annual_cost') ?: null,
            'license_type' => $this->request->getPost('license_type'),
            'license_expiry' => $this->request->getPost('license_expiry') ?: null,
            'vendor_contract_ref' => $this->request->getPost('vendor_contract_ref'),
            'cloud_subscription_details' => $this->request->getPost('cloud_subscription_details'),
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

            // Update Technology Details
            $techDetails = [
                'programming_language' => $this->request->getPost('programming_language'),
                'framework' => $this->request->getPost('framework'),
                'frontend_technology' => $this->request->getPost('frontend_technology'),
                'database_type' => $this->request->getPost('database_type'),
                'middleware' => $this->request->getPost('middleware'),
                'container_technology' => $this->request->getPost('container_technology'),
            ];
            $existingTech = $this->techModel->getTechDetailsByApplication($id);
            if ($existingTech) {
                $this->techModel->update($existingTech['id'], $techDetails);
            } else {
                $techDetails['application_id'] = $id;
                $this->techModel->insert($techDetails);
            }

            // Update Infrastructure Details
            $infraData = [
                'server_type' => $this->request->getPost('server_type'),
                'operating_system' => $this->request->getPost('operating_system'),
                'os_version' => $this->request->getPost('os_version'),
                'cpu_cores' => $this->request->getPost('cpu_cores') ?: null,
                'memory_gb' => $this->request->getPost('memory_gb') ?: null,
                'storage_gb' => $this->request->getPost('storage_gb') ?: null,
                'database_server' => $this->request->getPost('database_server'),
                'storage_location' => $this->request->getPost('storage_location'),
                'backup_location' => $this->request->getPost('backup_location'),
            ];
            $existingInfra = $this->infrastructureModel->getInfrastructureByApplication($id);
            if ($existingInfra) {
                $this->infrastructureModel->update($existingInfra['id'], $infraData);
            } else {
                $infraData['application_id'] = $id;
                $this->infrastructureModel->insert($infraData);
            }

            // Update Compliance & Security
            $complianceData = [
                'data_classification' => $this->request->getPost('data_classification'),
                'personal_data_flag' => $this->request->getPost('personal_data_flag') ? 1 : 0,
                'compliance_requirements' => $this->request->getPost('compliance_requirements'),
                'authentication_type' => $this->request->getPost('authentication_type'),
                'encryption_enabled' => $this->request->getPost('encryption_enabled') ? 1 : 0,
                'encryption_method' => $this->request->getPost('encryption_method'),
                'vulnerability_scan_status' => $this->request->getPost('vulnerability_scan_status'),
                'last_security_review' => $this->request->getPost('last_security_review') ?: null,
                'security_certifications' => $this->request->getPost('security_certifications'),
            ];
            $existingCompliance = $this->complianceModel->getComplianceByApplication($id);
            if ($existingCompliance) {
                $this->complianceModel->update($existingCompliance['id'], $complianceData);
            } else {
                $complianceData['application_id'] = $id;
                $this->complianceModel->insert($complianceData);
            }

            // Update Licensing Info
            $licensingData = [
                'license_type' => $this->request->getPost('license_type'),
                'license_key' => $this->request->getPost('license_key'),
                'license_expiry' => $this->request->getPost('license_expiry') ?: null,
                'vendor_name' => $this->request->getPost('vendor_name'),
                'vendor_contract_ref' => $this->request->getPost('vendor_contract_ref'),
                'annual_cost' => $this->request->getPost('annual_cost') ?: null,
                'cost_center' => $this->request->getPost('cost_center'),
                'purchase_date' => $this->request->getPost('purchase_date') ?: null,
                'renewal_date' => $this->request->getPost('renewal_date') ?: null,
            ];
            $existingLicensing = $this->licensingModel->getLicensingByApplication($id);
            if ($existingLicensing) {
                $this->licensingModel->update($existingLicensing['id'], $licensingData);
            } else {
                $licensingData['application_id'] = $id;
                $this->licensingModel->insert($licensingData);
            }

            // Update Operational Metrics
            $metricsData = [
                'availability_sla' => $this->request->getPost('availability_sla'),
                'criticality_level' => $this->request->getPost('criticality_level') ?? $this->request->getPost('business_criticality'),
                'business_impact' => $this->request->getPost('business_impact'),
                'peak_users' => $this->request->getPost('peak_users') ?: null,
                'peak_transactions' => $this->request->getPost('peak_transactions') ?: null,
                'monitoring_tool' => $this->request->getPost('monitoring_tool'),
                'mttr_target' => $this->request->getPost('mttr_target') ?: null,
                'rto_target' => $this->request->getPost('rto_target') ?: null,
                'rpo_target' => $this->request->getPost('rpo_target') ?: null,
            ];
            $existingMetrics = $this->metricsModel->getMetricsByApplication($id);
            if ($existingMetrics) {
                $this->metricsModel->update($existingMetrics['id'], $metricsData);
            } else {
                $metricsData['application_id'] = $id;
                $this->metricsModel->insert($metricsData);
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

        // Load all related data
        $data['technologies'] = $this->appTechModel->getTechnologiesByApplication($id);
        $data['techDetails'] = $this->techModel->getTechDetailsByApplication($id);
        $data['infrastructure'] = $this->infrastructureModel->getInfrastructureByApplication($id);
        $data['compliance'] = $this->complianceModel->getComplianceByApplication($id);
        $data['licensing'] = $this->licensingModel->getLicensingByApplication($id);
        $data['metrics'] = $this->metricsModel->getMetricsByApplication($id);
        $data['contacts'] = $this->contactsModel->getContactsByApplication($id);
        $data['environments'] = $this->environmentsModel->getEnvironmentsByApplication($id);
        $data['dependencies'] = $this->dependenciesModel->getDependenciesByApplication($id);
        $data['vendors'] = $this->vendorsModel->getVendorsByApplication($id);
        $data['batchJobs'] = $this->batchModel->getJobsByApplication($id);
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
