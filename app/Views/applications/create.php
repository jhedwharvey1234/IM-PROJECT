<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; overflow-y: auto; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .section-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #0d6efd; }
        .section-card h5 { color: #0d6efd; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; font-size: 16px; }
        .section-card h5 i { margin-right: 10px; font-size: 18px; }
        .form-section { margin-bottom: 10px; }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 15px; }
            .section-card h5 { font-size: 14px; }
        }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Create Application']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('applications') ?>" title="Go to Applications">Applications</a>
            <span class="separator">›</span>
            <span class="current">Create New Application</span>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <h5 class="mb-3"><i class="bi bi-exclamation-circle"></i> Validation Errors</h5>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('applications/store') ?>" method="post" class="needs-validation">
            <?= csrf_field() ?>

            <!-- CATEGORY 1: BASIC INFORMATION -->
            <div class="section-card">
                <h5><i class="bi bi-info-circle"></i> 1. Basic Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="app_code" class="form-label">Application Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="app_code" name="app_code" 
                               value="<?= old('app_code') ?>" required maxlength="30">
                        <small class="text-muted d-block mt-1">Unique identifier (e.g., CRM-001)</small>
                    </div>
                    <div class="col-md-6">
                        <label for="app_name" class="form-label">Application Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="app_name" name="app_name" 
                               value="<?= old('app_name') ?>" required maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label for="app_category" class="form-label">Application Category</label>
                        <input type="text" class="form-control" id="app_category" name="app_category" 
                               value="<?= old('app_category') ?>" maxlength="100" placeholder="e.g., Core System, Support, Reporting">
                    </div>
                    <div class="col-md-6">
                        <label for="app_type" class="form-label">Application Type</label>
                        <select class="form-select" id="app_type" name="app_type">
                            <option value="">Select Type</option>
                            <option value="Web" <?= old('app_type') === 'Web' ? 'selected' : '' ?>>Web</option>
                            <option value="Mobile" <?= old('app_type') === 'Mobile' ? 'selected' : '' ?>>Mobile</option>
                            <option value="Desktop" <?= old('app_type') === 'Desktop' ? 'selected' : '' ?>>Desktop</option>
                            <option value="API" <?= old('app_type') === 'API' ? 'selected' : '' ?>>API</option>
                            <option value="Batch" <?= old('app_type') === 'Batch' ? 'selected' : '' ?>>Batch</option>
                            <option value="SaaS" <?= old('app_type') === 'SaaS' ? 'selected' : '' ?>>SaaS</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" placeholder="Enter application description..."><?= old('description') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label for="business_purpose" class="form-label">Business Purpose (Detailed)</label>
                        <textarea class="form-control" id="business_purpose" name="business_purpose" 
                                  rows="3" placeholder="Detailed business purpose..."><?= old('business_purpose') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- CATEGORY 2: OWNERSHIP & CONTACTS -->
            <div class="section-card">
                <h5><i class="bi bi-people"></i> 2. Ownership & Contacts</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Business Unit / Department</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= old('department_id') == $dept['id'] ? 'selected' : '' ?>>
                                    <?= esc($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="owner_name" class="form-label">Business Owner</label>
                        <input type="text" class="form-control" id="owner_name" name="owner_name" 
                               value="<?= old('owner_name') ?>" maxlength="100">
                    </div>
                </div>
                <small class="text-muted">Detailed contacts can be added in the edit view.</small>
            </div>

            <!-- CATEGORY 3: ENVIRONMENT INFORMATION -->
            <div class="section-card">
                <h5><i class="bi bi-server"></i> 3. Environment Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="production_url" class="form-label">Production URL</label>
                        <input type="url" class="form-control" id="production_url" name="production_url" 
                               value="<?= old('production_url') ?>" maxlength="255" placeholder="https://...">
                    </div>
                    <div class="col-md-6">
                        <label for="version" class="form-label">Current Version</label>
                        <input type="text" class="form-control" id="version" name="version" 
                               value="<?= old('version') ?>" maxlength="20" placeholder="e.g., 1.0.0">
                    </div>
                </div>
                <small class="text-muted">Additional environments can be added in the edit view.</small>
            </div>

            <!-- CATEGORY 4: INFRASTRUCTURE DETAILS -->
            <div class="section-card">
                <h5><i class="bi bi-cpu"></i> 4. Infrastructure Details</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="server_type" class="form-label">Server Type</label>
                        <select class="form-select" id="server_type" name="server_type">
                            <option value="">Select Type</option>
                            <option value="VM" <?= old('server_type') === 'VM' ? 'selected' : '' ?>>VM</option>
                            <option value="Physical" <?= old('server_type') === 'Physical' ? 'selected' : '' ?>>Physical</option>
                            <option value="Container" <?= old('server_type') === 'Container' ? 'selected' : '' ?>>Container</option>
                            <option value="Cloud" <?= old('server_type') === 'Cloud' ? 'selected' : '' ?>>Cloud</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="operating_system" class="form-label">Operating System</label>
                        <input type="text" class="form-control" id="operating_system" name="operating_system" 
                               value="<?= old('operating_system') ?>" maxlength="100" placeholder="e.g., Ubuntu, Windows">
                    </div>
                    <div class="col-md-4">
                        <label for="os_version" class="form-label">OS Version</label>
                        <input type="text" class="form-control" id="os_version" name="os_version" 
                               value="<?= old('os_version') ?>" maxlength="50" placeholder="e.g., 20.04">
                    </div>
                    <div class="col-md-3">
                        <label for="cpu_cores" class="form-label">CPU Cores</label>
                        <input type="number" class="form-control" id="cpu_cores" name="cpu_cores" 
                               value="<?= old('cpu_cores') ?>" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="memory_gb" class="form-label">Memory (GB)</label>
                        <input type="number" class="form-control" id="memory_gb" name="memory_gb" 
                               value="<?= old('memory_gb') ?>" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="storage_gb" class="form-label">Storage (GB)</label>
                        <input type="number" class="form-control" id="storage_gb" name="storage_gb" 
                               value="<?= old('storage_gb') ?>" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="database_type" class="form-label">Database Type</label>
                        <select class="form-select" id="database_type" name="database_type">
                            <option value="">Select</option>
                            <option value="MySQL" <?= old('database_type') === 'MySQL' ? 'selected' : '' ?>>MySQL</option>
                            <option value="PostgreSQL" <?= old('database_type') === 'PostgreSQL' ? 'selected' : '' ?>>PostgreSQL</option>
                            <option value="Oracle" <?= old('database_type') === 'Oracle' ? 'selected' : '' ?>>Oracle</option>
                            <option value="MSSQL" <?= old('database_type') === 'MSSQL' ? 'selected' : '' ?>>MSSQL</option>
                            <option value="MongoDB" <?= old('database_type') === 'MongoDB' ? 'selected' : '' ?>>MongoDB</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="database_server" class="form-label">Database Server</label>
                        <input type="text" class="form-control" id="database_server" name="database_server" 
                               value="<?= old('database_server') ?>" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label for="storage_location" class="form-label">Storage Location</label>
                        <input type="text" class="form-control" id="storage_location" name="storage_location" 
                               value="<?= old('storage_location') ?>" maxlength="255" placeholder="e.g., /var/www, NAS">
                    </div>
                    <div class="col-md-6">
                        <label for="backup_location" class="form-label">Backup Location</label>
                        <input type="text" class="form-control" id="backup_location" name="backup_location" 
                               value="<?= old('backup_location') ?>" maxlength="255">
                    </div>
                </div>
            </div>

            <!-- CATEGORY 5: TECHNOLOGY STACK -->
            <div class="section-card">
                <h5><i class="bi bi-code-square"></i> 5. Technology Stack</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="programming_language" class="form-label">Programming Language</label>
                        <input type="text" class="form-control" id="programming_language" name="programming_language" 
                               value="<?= old('programming_language') ?>" maxlength="100" placeholder="e.g., PHP, Python, Java">
                    </div>
                    <div class="col-md-6">
                        <label for="framework" class="form-label">Framework</label>
                        <input type="text" class="form-control" id="framework" name="framework" 
                               value="<?= old('framework') ?>" maxlength="100" placeholder="e.g., Laravel, Spring Boot">
                    </div>
                    <div class="col-md-6">
                        <label for="frontend_technology" class="form-label">Frontend Technology</label>
                        <input type="text" class="form-control" id="frontend_technology" name="frontend_technology" 
                               value="<?= old('frontend_technology') ?>" maxlength="100" placeholder="e.g., React, Vue, Bootstrap">
                    </div>
                    <div class="col-md-6">
                        <label for="middleware" class="form-label">Middleware</label>
                        <input type="text" class="form-control" id="middleware" name="middleware" 
                               value="<?= old('middleware') ?>" maxlength="255" placeholder="e.g., Apache, Nginx, Tomcat">
                    </div>
                    <div class="col-md-6">
                        <label for="container_technology" class="form-label">Container Technology</label>
                        <input type="text" class="form-control" id="container_technology" name="container_technology" 
                               value="<?= old('container_technology') ?>" maxlength="100" placeholder="e.g., Docker, Kubernetes">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Additional Technologies</label>
                        <div class="row g-2">
                            <?php foreach ($technologies as $tech): ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="tech_<?= $tech['id'] ?>" 
                                               name="technologies[]" 
                                               value="<?= $tech['id'] ?>"
                                               <?= in_array($tech['id'], old('technologies') ?? []) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tech_<?= $tech['id'] ?>">
                                            <?= esc($tech['technology_name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CATEGORY 6: INTEGRATION & DEPENDENCIES -->
            <div class="section-card">
                <h5><i class="bi bi-diagram-3"></i> 6. Integration & Dependencies</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="repository_url" class="form-label">Repository URL</label>
                        <input type="url" class="form-control" id="repository_url" name="repository_url" 
                               value="<?= old('repository_url') ?>" maxlength="255" placeholder="https://github.com/...">
                    </div>
                </div>
                <small class="text-muted">Additional integrations and dependencies can be added in the edit view.</small>
            </div>

            <!-- CATEGORY 7: SECURITY & COMPLIANCE -->
            <div class="section-card">
                <h5><i class="bi bi-shield-check"></i> 7. Security & Compliance</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="data_classification" class="form-label">Data Classification</label>
                        <select class="form-select" id="data_classification" name="data_classification">
                            <option value="">Select Classification</option>
                            <option value="Public" <?= old('data_classification') === 'Public' ? 'selected' : '' ?>>Public</option>
                            <option value="Internal" <?= old('data_classification') === 'Internal' ? 'selected' : '' ?>>Internal</option>
                            <option value="Confidential" <?= old('data_classification') === 'Confidential' ? 'selected' : '' ?>>Confidential</option>
                            <option value="Sensitive" <?= old('data_classification') === 'Sensitive' ? 'selected' : '' ?>>Sensitive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="personal_data_flag" name="personal_data_flag" 
                                   value="1" <?= old('personal_data_flag') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="personal_data_flag">
                                Contains Personal Data
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="authentication_type" class="form-label">Authentication Type</label>
                        <select class="form-select" id="authentication_type" name="authentication_type">
                            <option value="">Select Type</option>
                            <option value="SSO" <?= old('authentication_type') === 'SSO' ? 'selected' : '' ?>>SSO</option>
                            <option value="AD" <?= old('authentication_type') === 'AD' ? 'selected' : '' ?>>Active Directory</option>
                            <option value="OAuth" <?= old('authentication_type') === 'OAuth' ? 'selected' : '' ?>>OAuth</option>
                            <option value="LDAP" <?= old('authentication_type') === 'LDAP' ? 'selected' : '' ?>>LDAP</option>
                            <option value="Internal" <?= old('authentication_type') === 'Internal' ? 'selected' : '' ?>>Internal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="encryption_enabled" name="encryption_enabled" 
                                   value="1" <?= old('encryption_enabled') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="encryption_enabled">
                                Encryption Enabled
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="last_security_review" class="form-label">Last Security Review Date</label>
                        <input type="date" class="form-control" id="last_security_review" name="last_security_review" 
                               value="<?= old('last_security_review') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="compliance_requirements" class="form-label">Compliance Requirements</label>
                        <input type="text" class="form-control" id="compliance_requirements" name="compliance_requirements" 
                               value="<?= old('compliance_requirements') ?>" placeholder="e.g., PCI, GDPR, HIPAA">
                    </div>
                </div>
            </div>

            <!-- CATEGORY 8: LIFECYCLE MANAGEMENT -->
            <div class="section-card">
                <h5><i class="bi bi-arrow-repeat"></i> 8. Lifecycle Management</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="lifecycle_stage" class="form-label">Application Lifecycle Stage</label>
                        <select class="form-select" id="lifecycle_stage" name="lifecycle_stage">
                            <option value="">Select Stage</option>
                            <option value="Development" <?= old('lifecycle_stage') === 'Development' ? 'selected' : '' ?>>Development</option>
                            <option value="Active" <?= old('lifecycle_stage') === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Maintenance" <?= old('lifecycle_stage') === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="Sunset" <?= old('lifecycle_stage') === 'Sunset' ? 'selected' : '' ?>>Sunset</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status_id" class="form-label">Status</label>
                        <select class="form-select" id="status_id" name="status_id">
                            <option value="">Select Status</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status['id'] ?>" <?= old('status_id') == $status['id'] ? 'selected' : '' ?>>
                                    <?= esc($status['status_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="eol_date" class="form-label">End-of-Life Date</label>
                        <input type="date" class="form-control" id="eol_date" name="eol_date" value="<?= old('eol_date') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="last_major_upgrade" class="form-label">Last Major Upgrade Date</label>
                        <input type="date" class="form-control" id="last_major_upgrade" name="last_major_upgrade" 
                               value="<?= old('last_major_upgrade') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="replacement_system" class="form-label">Replacement System</label>
                        <input type="text" class="form-control" id="replacement_system" name="replacement_system" 
                               value="<?= old('replacement_system') ?>" maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="upgrade_roadmap" class="form-label">Upgrade Roadmap</label>
                        <textarea class="form-control" id="upgrade_roadmap" name="upgrade_roadmap" 
                                  rows="3" placeholder="Planned upgrades and timeline..."><?= old('upgrade_roadmap') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- CATEGORY 9: OPERATIONAL METRICS -->
            <div class="section-card">
                <h5><i class="bi bi-graph-up"></i> 9. Operational Metrics</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="availability_sla" class="form-label">Availability SLA</label>
                        <input type="text" class="form-control" id="availability_sla" name="availability_sla" 
                               value="<?= old('availability_sla') ?>" placeholder="e.g., 99.9%">
                    </div>
                    <div class="col-md-6">
                        <label for="monitoring_tool" class="form-label">Monitoring Tool</label>
                        <input type="text" class="form-control" id="monitoring_tool" name="monitoring_tool" 
                               value="<?= old('monitoring_tool') ?>" placeholder="e.g., Datadog, New Relic">
                    </div>
                    <div class="col-md-6">
                        <label for="peak_users" class="form-label">Peak Concurrent Users</label>
                        <input type="number" class="form-control" id="peak_users" name="peak_users" 
                               value="<?= old('peak_users') ?>" min="0">
                    </div>
                    <div class="col-md-6">
                        <label for="peak_transactions" class="form-label">Peak Transactions/sec</label>
                        <input type="number" class="form-control" id="peak_transactions" name="peak_transactions" 
                               value="<?= old('peak_transactions') ?>" min="0">
                    </div>
                    <div class="col-12">
                        <label for="business_impact" class="form-label">Business Impact if Down</label>
                        <textarea class="form-control" id="business_impact" name="business_impact" 
                                  rows="3" placeholder="Describe impact on business if application is down..."><?= old('business_impact') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="criticality_level" class="form-label">Criticality Level</label>
                        <select class="form-select" id="criticality_level" name="business_criticality">
                            <option value="">Select Level</option>
                            <option value="High" <?= old('business_criticality') === 'High' ? 'selected' : '' ?>>High</option>
                            <option value="Medium" <?= old('business_criticality') === 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="Low" <?= old('business_criticality') === 'Low' ? 'selected' : '' ?>>Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- CATEGORY 10: LICENSING & COST -->
            <div class="section-card">
                <h5><i class="bi bi-receipt"></i> 10. Licensing & Cost</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="license_type" class="form-label">License Type</label>
                        <input type="text" class="form-control" id="license_type" name="license_type" 
                               value="<?= old('license_type') ?>" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label for="license_expiry" class="form-label">License Expiry Date</label>
                        <input type="date" class="form-control" id="license_expiry" name="license_expiry" 
                               value="<?= old('license_expiry') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="vendor_name" class="form-label">Vendor Name</label>
                        <input type="text" class="form-control" id="vendor_name" name="vendor_name" 
                               value="<?= old('vendor_name') ?>" maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label for="vendor_contract_ref" class="form-label">Vendor Contract Reference</label>
                        <input type="text" class="form-control" id="vendor_contract_ref" name="vendor_contract_ref" 
                               value="<?= old('vendor_contract_ref') ?>" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label for="annual_cost" class="form-label">Annual Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="annual_cost" name="annual_cost" 
                                   value="<?= old('annual_cost') ?>" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="cost_center" class="form-label">Cost Center</label>
                        <input type="text" class="form-control" id="cost_center" name="cost_center" 
                               value="<?= old('cost_center') ?>" maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="cloud_subscription_details" class="form-label">Cloud Subscription Details</label>
                        <textarea class="form-control" id="cloud_subscription_details" name="cloud_subscription_details" 
                                  rows="3" placeholder="Details about cloud subscription (if applicable)..."><?= old('cloud_subscription_details') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions Section -->
            <div class="row g-2 mt-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Create Application
                    </button>
                    <a href="<?= site_url('applications') ?>" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
