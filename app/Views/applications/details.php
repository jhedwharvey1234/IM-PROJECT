<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        
        .detail-card { background-color: #fff; padding: 25px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .detail-card h3 { color: #212529; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; display: flex; align-items: center; }
        .detail-card h3 i { margin-right: 10px; color: #0d6efd; }
        
        .detail-row { margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .detail-label { font-weight: 600; color: #495057; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px; }
        .detail-value { color: #212529; font-size: 15px; word-break: break-word; }
        
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .status-active { background-color: #d1e7dd; color: #0f5132; }
        .status-inactive { background-color: #e2e3e5; color: #383d41; }
        .status-maintenance { background-color: #fff3cd; color: #856404; }
        .status-deprecated { background-color: #f8d7da; color: #842029; }
        
        .app-side-panel { background: white; border-radius: 8px; padding: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: sticky; top: 76px; }
        
        .action-btn { width: 100%; margin-bottom: 12px; }
        .action-btn i { margin-right: 8px; }
        
        .nav-tabs { border-bottom: 2px solid #e9ecef; }
        .nav-tabs .nav-link { color: #495057; border: none; border-bottom: 3px solid transparent; padding: 12px 16px; font-weight: 500; font-size: 14px; }
        .nav-tabs .nav-link:hover { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
        
        .tech-badge { display: inline-block; padding: 8px 16px; margin: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px; font-size: 13px; font-weight: 500; }
        .url-link { padding: 10px 15px; background: #f8f9fa; border-left: 4px solid #0d6efd; border-radius: 4px; display: block; margin-bottom: 10px; text-decoration: none; color: #212529; transition: all 0.3s; }
        .url-link:hover { background: #e9ecef; transform: translateX(5px); }
        
        .record-item { background: #f8f9fa; padding: 12px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #0d6efd; }
        .record-item-title { font-weight: 600; color: #212529; }
        .record-item-meta { font-size: 12px; color: #6c757d; margin-top: 4px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Application Details']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('applications') ?>" title="Go to Applications">Applications</a>
            <span class="separator">›</span>
            <span class="current"><?= !empty($application['app_name']) ? esc($application['app_name']) : 'Application Details' ?></span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-9">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4" id="appDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button"><i class="bi bi-file-earmark"></i> Basic Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-infrastructure" type="button"><i class="bi bi-diagram-3"></i> Infrastructure</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-technology" type="button"><i class="bi bi-code-square"></i> Technology</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-security" type="button"><i class="bi bi-shield-check"></i> Security</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-lifecycle" type="button"><i class="bi bi-arrow-repeat"></i> Lifecycle</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-operational" type="button"><i class="bi bi-speedometer2"></i> Operational</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-licensing" type="button"><i class="bi bi-credit-card"></i> Licensing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-related" type="button"><i class="bi bi-link-45deg"></i> Related Data</button>
                    </li>
                </ul>

                <div class="tab-content" id="appDetailsTabsContent">
                    <!-- TAB 1: BASIC INFORMATION -->
                    <div class="tab-pane fade show active" id="tab-basic" role="tabpanel">
                        <!-- Section 1: Basic Information -->
                        <div class="detail-card">
                            <h3><i class="bi bi-file-earmark"></i> Basic Information</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-code"></i> App Code</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= esc($application['app_code'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-app-indicator"></i> App Name</span>
                                        <span class="detail-value"><?= esc($application['app_name'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-folder"></i> Category</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['app_category'] ?? 'N/A') ?></span></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-tag"></i> Type</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['app_type'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-briefcase"></i> Business Purpose</span>
                                        <span class="detail-value"><?= esc($application['business_purpose'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-arrow-down-up"></i> Version</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= esc($application['version'] ?? 'N/A') ?></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Ownership & Contacts -->
                        <div class="detail-card">
                            <h3><i class="bi bi-people"></i> Ownership & Contacts</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-building"></i> Department</span>
                                        <span class="detail-value"><?= esc($application['department_name'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-person-badge"></i> Owner Name</span>
                                        <span class="detail-value"><?= esc($application['owner_name'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($contacts)): ?>
                            <div style="margin-top: 15px;">
                                <strong style="display: block; margin-bottom: 10px;">Extended Contacts (<?= count($contacts) ?>)</strong>
                                <?php foreach ($contacts as $contact): ?>
                                    <div class="record-item">
                                        <div class="record-item-title"><?= esc($contact['contact_name'] ?? 'N/A') ?></div>
                                        <div class="record-item-meta">Role: <span class="badge bg-info"><?= esc($contact['role'] ?? 'N/A') ?></span> | Email: <?= esc($contact['email'] ?? 'N/A') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Section 3: Environment Information -->
                        <div class="detail-card">
                            <h3><i class="bi bi-cloud"></i> Environment Information</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-globe"></i> Production URL</span>
                                        <span class="detail-value"><?= !empty($application['production_url']) ? '<a href="' . esc($application['production_url']) . '" target="_blank" class="url-link"><i class="bi bi-box-arrow-up-right"></i> ' . esc($application['production_url']) . '</a>' : 'N/A' ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-archive"></i> Archive Date</span>
                                        <span class="detail-value"><?= !empty($application['archive_date']) ? date('M d, Y', strtotime($application['archive_date'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($environments)): ?>
                            <div style="margin-top: 15px;">
                                <strong style="display: block; margin-bottom: 10px;">Environment Configurations (<?= count($environments) ?>)</strong>
                                <?php foreach ($environments as $env): ?>
                                    <div class="record-item">
                                        <div class="record-item-title"><?= esc($env['environment_type'] ?? 'N/A') ?></div>
                                        <div class="record-item-meta">URL: <?= esc($env['environment_url'] ?? 'N/A') ?> | DB: <?= esc($env['database_name'] ?? 'N/A') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Description Card -->
                        <?php if (!empty($application['description'])): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-file-text"></i> Description</h3>
                            <div class="detail-value" style="padding: 15px; background-color: #f8f9fa; border-radius: 6px; line-height: 1.6;">
                                <?= nl2br(esc($application['description'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- TAB 2: INFRASTRUCTURE -->
                    <div class="tab-pane fade" id="tab-infrastructure" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-diagram-3"></i> Infrastructure Details</h3>
                            <?php if (!empty($infrastructure)): ?>
                                <div class="info-grid">
                                    <div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-server"></i> Server Type</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($infrastructure['server_type'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-windows"></i> Operating System</span>
                                            <span class="detail-value"><?= esc($infrastructure['operating_system'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-tag"></i> OS Version</span>
                                            <span class="detail-value"><?= esc($infrastructure['os_version'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-cpu"></i> CPU Cores</span>
                                            <span class="detail-value"><?= esc($infrastructure['cpu_cores'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-memory"></i> Memory (GB)</span>
                                            <span class="detail-value"><?= esc($infrastructure['memory_gb'] ?? 'N/A') ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-hdd"></i> Storage (GB)</span>
                                            <span class="detail-value"><?= esc($infrastructure['storage_gb'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-database"></i> Database Type</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($infrastructure['database_type'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-server"></i> Database Server</span>
                                            <span class="detail-value"><?= esc($infrastructure['database_server'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-hdd-rack"></i> Storage Location</span>
                                            <span class="detail-value"><?= esc($infrastructure['storage_location'] ?? 'N/A') ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-cloud-arrow-down"></i> Backup Location</span>
                                            <span class="detail-value"><?= esc($infrastructure['backup_location'] ?? 'N/A') ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0"><i class="bi bi-info-circle"></i> No infrastructure details configured for this application.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAB 3: TECHNOLOGY -->
                    <div class="tab-pane fade" id="tab-technology" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-code-square"></i> Technology Stack</h3>
                            <?php if (!empty($techDetails)): ?>
                                <div class="info-grid">
                                    <div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-code"></i> Programming Language</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['programming_language'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-wrench-adjustable"></i> Framework</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['framework'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-palette"></i> Frontend Technology</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['frontend_technology'] ?? 'N/A') ?></span></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-layers"></i> Middleware</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['middleware'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-box-seam"></i> Container Technology</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['container_technology'] ?? 'N/A') ?></span></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label"><i class="bi bi-database"></i> Database Type</span>
                                            <span class="detail-value"><span class="badge bg-secondary"><?= esc($techDetails['database_type'] ?? 'N/A') ?></span></span>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0"><i class="bi bi-info-circle"></i> No technology details configured for this application.</div>
                            <?php endif; ?>

                            <?php if (!empty($technologies)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">Associated Technologies (<?= count($technologies) ?>)</strong>
                                <div style="padding: 10px;">
                                    <?php foreach ($technologies as $tech): ?>
                                        <span class="tech-badge">
                                            <i class="bi bi-cpu"></i> <?= esc($tech['technology_name'] ?? $tech['name'] ?? 'N/A') ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Integration & Dependencies -->
                        <div class="detail-card">
                            <h3><i class="bi bi-link-45deg"></i> Integration & Dependencies</h3>
                            <div class="detail-row">
                                <span class="detail-label"><i class="bi bi-github"></i> Repository URL</span>
                                <span class="detail-value"><?= !empty($application['repository_url']) ? '<a href="' . esc($application['repository_url']) . '" target="_blank" class="url-link"><i class="bi bi-box-arrow-up-right"></i> ' . esc($application['repository_url']) . '</a>' : 'N/A' ?></span>
                            </div>

                            <?php if (!empty($dependencies)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">Dependencies (<?= count($dependencies) ?>)</strong>
                                <?php foreach ($dependencies as $dep): ?>
                                    <div class="record-item">
                                        <div class="record-item-title"><?= esc($dep['dependency_name'] ?? 'N/A') ?></div>
                                        <div class="record-item-meta">Type: <span class="badge bg-info"><?= esc($dep['dependency_type'] ?? 'N/A') ?></span> | Critical: <?= ($dep['is_critical'] ?? false) ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAB 4: SECURITY & COMPLIANCE -->
                    <div class="tab-pane fade" id="tab-security" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-shield-check"></i> Security & Compliance</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-info-circle"></i> Data Classification</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['data_classification'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-person-check"></i> Personal Data Processing</span>
                                        <span class="detail-value">
                                            <?php if ($application['personal_data_flag'] ?? false): ?>
                                                <span class="badge bg-danger">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">No</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-lock"></i> Authentication Type</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= esc($application['authentication_type'] ?? 'N/A') ?></span></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-lock-fill"></i> Encryption Enabled</span>
                                        <span class="detail-value">
                                            <?php if ($application['encryption_enabled'] ?? false): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">No</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($compliance)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">Additional Security Details</strong>
                                <div class="detail-row">
                                    <span class="detail-label">Vulnerability Scan Status</span>
                                    <span class="detail-value"><span class="badge bg-info"><?= esc($compliance['vulnerability_scan_status'] ?? 'N/A') ?></span></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Last Vulnerability Scan</span>
                                    <span class="detail-value"><?= !empty($compliance['last_vulnerability_scan']) ? date('M d, Y', strtotime($compliance['last_vulnerability_scan'])) : 'N/A' ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Encryption Method</span>
                                    <span class="detail-value"><?= esc($compliance['encryption_method'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Security Certifications</span>
                                    <span class="detail-value"><?= esc($compliance['security_certifications'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAB 5: LIFECYCLE -->
                    <div class="tab-pane fade" id="tab-lifecycle" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-arrow-repeat"></i> Lifecycle Management</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-circle"></i> Lifecycle Stage</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['lifecycle_stage'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-power"></i> Status</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= esc($application['status_name'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-x"></i> End of Life Date</span>
                                        <span class="detail-value"><?= !empty($application['eol_date']) ? date('M d, Y', strtotime($application['eol_date'])) : 'N/A' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-file-pdf"></i> Upgrade Roadmap</span>
                                        <span class="detail-value"><?= esc($application['upgrade_roadmap'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-plus"></i> Created</span>
                                        <span class="detail-value"><?= date('M d, Y', strtotime($application['date_created'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-check"></i> Last Updated</span>
                                        <span class="detail-value"><?= date('M d, Y', strtotime($application['last_updated'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-clock-history"></i> Retirement Date</span>
                                        <span class="detail-value"><?= !empty($application['retirement_date']) ? date('M d, Y', strtotime($application['retirement_date'])) : 'N/A' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-exclamation-lg"></i> Sunset Notification Date</span>
                                        <span class="detail-value"><?= !empty($application['sunset_notification_date']) ? date('M d, Y', strtotime($application['sunset_notification_date'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: OPERATIONAL -->
                    <div class="tab-pane fade" id="tab-operational" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-speedometer2"></i> Operational Metrics</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-bullseye"></i> Availability SLA (%)</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= esc($application['availability_sla'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-eye"></i> Monitoring Tool</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['monitoring_tool'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-people"></i> Peak Users</span>
                                        <span class="detail-value"><?= esc($application['peak_users'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-exclamation-triangle"></i> Business Impact</span>
                                        <span class="detail-value"><?= esc($application['business_impact'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-event"></i> Last Security Review</span>
                                        <span class="detail-value"><?= !empty($application['last_security_review']) ? date('M d, Y', strtotime($application['last_security_review'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($metrics)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">Additional Operational Metrics</strong>
                                <div class="detail-row">
                                    <span class="detail-label">Criticality Level</span>
                                    <span class="detail-value"><span class="badge bg-secondary"><?= esc($metrics['criticality_level'] ?? 'N/A') ?></span></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Peak Transactions</span>
                                    <span class="detail-value"><?= esc($metrics['peak_transactions'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Last Incident</span>
                                    <span class="detail-value"><?= !empty($metrics['last_incident']) ? date('M d, Y', strtotime($metrics['last_incident'])) : 'No incidents recorded' ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">MTTR Target (minutes)</span>
                                    <span class="detail-value"><?= esc($metrics['mttr_target'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">RTO Target (minutes)</span>
                                    <span class="detail-value"><?= esc($metrics['rto_target'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">RPO Target (minutes)</span>
                                    <span class="detail-value"><?= esc($metrics['rpo_target'] ?? 'N/A') ?></span>
                                </div>
                                <?php if (!empty($metrics['incident_history'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label">Incident History</span>
                                    <span class="detail-value"><?= nl2br(esc($metrics['incident_history'])) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAB 7: LICENSING -->
                    <div class="tab-pane fade" id="tab-licensing" role="tabpanel">
                        <div class="detail-card">
                            <h3><i class="bi bi-credit-card"></i> Licensing & Cost</h3>
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-file-check"></i> License Type</span>
                                        <span class="detail-value"><span class="badge bg-secondary"><?= esc($application['license_type'] ?? 'N/A') ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-event"></i> License Expiry</span>
                                        <span class="detail-value"><?= !empty($application['license_expiry_date']) ? date('M d, Y', strtotime($application['license_expiry_date'])) : (!empty($application['license_expiry']) ? date('M d, Y', strtotime($application['license_expiry'])) : 'N/A') ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-briefcase"></i> Vendor / Contract Ref</span>
                                        <span class="detail-value"><?= esc($application['vendor_contract_ref'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-cash-coin"></i> Annual Cost</span>
                                        <span class="detail-value"><span class="badge bg-info"><?= !empty($application['annual_cost']) ? '$' . number_format($application['annual_cost'], 2) : 'N/A' ?></span></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-cloud-arrow-down"></i> Cloud Subscription Details</span>
                                        <span class="detail-value"><?= esc($application['cloud_subscription_details'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($licensing)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">License & Cost Details</strong>
                                <div class="detail-row">
                                    <span class="detail-label">License Key</span>
                                    <span class="detail-value"><?= esc($licensing['license_key'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Vendor Name</span>
                                    <span class="detail-value"><?= esc($licensing['vendor_name'] ?? 'N/A') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">License Expiry</span>
                                    <span class="detail-value"><?= !empty($licensing['license_expiry']) ? date('M d, Y', strtotime($licensing['license_expiry'])) : 'N/A' ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Cost Center</span>
                                    <span class="detail-value"><span class="badge bg-info"><?= esc($licensing['cost_center'] ?? 'N/A') ?></span></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Purchase Date</span>
                                    <span class="detail-value"><?= !empty($licensing['purchase_date']) ? date('M d, Y', strtotime($licensing['purchase_date'])) : 'N/A' ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Renewal Date</span>
                                    <span class="detail-value"><?= !empty($licensing['renewal_date']) ? date('M d, Y', strtotime($licensing['renewal_date'])) : 'N/A' ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($vendors)): ?>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                                <strong style="display: block; margin-bottom: 10px;">Associated Vendors (<?= count($vendors) ?>)</strong>
                                <?php foreach ($vendors as $vendor): ?>
                                    <div class="record-item">
                                        <div class="record-item-title"><?= esc($vendor['vendor_name'] ?? 'N/A') ?></div>
                                        <div class="record-item-meta">Contact: <?= esc($vendor['contact_email'] ?? 'N/A') ?> | Phone: <?= esc($vendor['contact_phone'] ?? 'N/A') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAB 8: RELATED DATA -->
                    <div class="tab-pane fade" id="tab-related" role="tabpanel">
                        <!-- Batch Jobs -->
                        <?php if (!empty($batchJobs)): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-hourglass-split"></i> Batch Jobs (<?= count($batchJobs) ?>)</h3>
                            <?php foreach ($batchJobs as $job): ?>
                                <div class="record-item">
                                    <div class="record-item-title"><?= esc($job['job_name'] ?? 'N/A') ?></div>
                                    <div class="record-item-meta">Schedule: <span class="badge bg-info"><?= esc($job['job_schedule'] ?? 'N/A') ?></span> | Status: <?= ($job['is_enabled'] ?? false) ? '<span class="badge bg-success">Enabled</span>' : '<span class="badge bg-secondary">Disabled</span>' ?> | Frequency: <?= esc($job['frequency'] ?? 'N/A') ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Related Contacts (Extended) -->
                        <?php if (!empty($contacts)): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-telephone"></i> Extended Contact Information (<?= count($contacts) ?>)</h3>
                            <div class="detail-value">
                                <?php foreach ($contacts as $contact): ?>
                                    <div class="info-grid" style="margin-bottom: 15px;">
                                        <div>
                                            <strong><?= esc($contact['contact_name'] ?? 'N/A') ?></strong>
                                            <div style="font-size: 12px; color: #6c757d; margin-top: 5px;">
                                                Role: <span class="badge bg-info"><?= esc($contact['role'] ?? 'N/A') ?></span>
                                            </div>
                                        </div>
                                        <div>
                                            <div style="font-size: 12px;">
                                                Email: <a href="mailto:<?= esc($contact['email'] ?? '') ?>"><?= esc($contact['email'] ?? 'N/A') ?></a><br/>
                                                Phone: <?= esc($contact['phone'] ?? 'N/A') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-lg-3">
                <div class="app-side-panel">
                    <h5 class="mb-3" style="color: #0d6efd; font-weight: 600; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
                        <i class="bi bi-gear"></i> Quick Actions
                    </h5>
                    
                    <a href="<?= site_url('applications/edit/' . $application['id']) ?>" class="btn btn-warning action-btn">
                        <i class="bi bi-pencil-square"></i> Edit Application
                    </a>
                    
                    <a href="<?= site_url('applications') ?>" class="btn btn-secondary action-btn">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    
                    <hr style="margin: 20px 0;">
                    
                    <h5 class="mb-3" style="color: #0d6efd; font-weight: 600; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
                        <i class="bi bi-info-circle"></i> Application Info
                    </h5>
                    
                    <div class="detail-row">
                        <span class="detail-label" style="font-size: 11px;"><i class="bi bi-shield-lock"></i> ID</span>
                        <span class="detail-value" style="font-size: 13px;"><span class="badge bg-secondary"><?= esc($application['id']) ?></span></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label" style="font-size: 11px;"><i class="bi bi-calendar-plus"></i> Created</span>
                        <span class="detail-value" style="font-size: 13px;"><?= date('M d, Y', strtotime($application['date_created'])) ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label" style="font-size: 11px;"><i class="bi bi-calendar-check"></i> Updated</span>
                        <span class="detail-value" style="font-size: 13px;"><?= date('M d, Y', strtotime($application['last_updated'])) ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label" style="font-size: 11px;"><i class="bi bi-grid-1x2"></i> Current Status</span>
                        <span class="detail-value" style="font-size: 13px;"><span class="badge bg-info"><?= esc($application['status_name'] ?? 'N/A') ?></span></span>
                    </div>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
