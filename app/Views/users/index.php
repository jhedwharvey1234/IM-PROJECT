<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .status-superadmin { background-color: #dc3545; color: #fff; }
        .status-readandwrite { background-color: #fd7e14; color: #fff; }
        .status-readonly { background-color: #28a745; color: #fff; }
        .pagination-info { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; margin-bottom: 15px; font-size: 14px; }
        .pagination-controls { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; margin-top: 15px; padding: 15px 0; border-top: 1px solid #dee2e6; }
        .pagination-controls button, .pagination-controls a { padding: 5px 10px; border: 1px solid #dee2e6; background: white; color: #0d6efd; text-decoration: none; cursor: pointer; border-radius: 3px; font-size: 13px; }
        .pagination-controls button:hover, .pagination-controls a:hover { background-color: #e9ecef; }
        .pagination-controls button:disabled { opacity: 0.5; cursor: not-allowed; }
        .pagination-controls button.active { background-color: #0d6efd; color: white; }
        .pagination-controls span { margin: 0 5px; }
        .rows-per-page { display: flex; align-items: center; gap: 8px; }
        .rows-per-page select { padding: 4px 6px; border: 1px solid #dee2e6; border-radius: 3px; font-size: 13px; }
        .breadcrumb-nav { padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; font-size: 14px; cursor: pointer; text-decoration: none; border-radius: 4px; margin: 0 2px; }
        .action-btn:hover { opacity: 0.8; color: white; transform: translateY(-1px); }
        .action-btn-view { background-color: #0dcaf0; }
        .action-btn-edit { background-color: #ffc107; color: #000; }
        .action-btn-delete { background-color: #dc3545; }
        .main-content:fullscreen,
        .main-content:-webkit-full-screen {
            margin: 0;
            padding: 20px;
            background-color: #eeeeee;
            width: 100%;
            height: 100%;
            overflow: auto;
        }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'User Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Users</span>
        </div>
        
        <!-- Toolbar with Search and Filters in Single Row -->
        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <!-- Action Buttons Group -->
            <div class="d-flex gap-1">
                <!-- Create Button -->
                <a href="<?= site_url('users/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
                    <i class="bi bi-plus-circle"></i>
                </a>

                <!-- Delete Selected Button -->
                <button class="btn btn-danger btn-sm" id="deleteSelectedBtn" disabled title="Delete" aria-label="Delete" data-bs-toggle="tooltip">
                    <i class="bi bi-trash"></i>
                </button>

                <!-- Refresh Button -->
                <button class="btn btn-info btn-sm" id="refreshBtn" title="Refresh" aria-label="Refresh" data-bs-toggle="tooltip">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <form action="<?= site_url('users/sync-entra') ?>" method="post" class="d-inline" onsubmit="return confirm('Sync Azure AD users now? This may take a while.');">
                    <button type="submit" class="btn btn-primary btn-sm" title="Sync Azure AD" aria-label="Sync Azure AD" data-bs-toggle="tooltip">
                        <i class="bi bi-cloud-arrow-down"></i>
                    </button>
                </form>

                <!-- Column Visibility Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" title="Columns" aria-label="Columns">
                        <i class="bi bi-sliders"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg" id="columnsList" aria-labelledby="columnsDropdown" style="max-height: 400px; overflow-y: auto; min-width: 250px;">
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="name" checked> Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="type" checked> Type</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="email" checked> Email</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="usertype" checked> Usertype</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_display_name"> Azure Display Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_user_principal_name"> Azure UPN</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_object_id"> Azure Object ID</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_account_enabled"> Azure Account Enabled</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_last_password_change_at"> Azure Last Password Change</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_job_title"> Azure Job Title</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_employee_id"> Azure Employee ID</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_department"> Azure Department</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_manager_display_name"> Azure Manager Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_manager_user_principal_name"> Azure Manager UPN</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="entra_manager_mail"> Azure Manager Email</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="assignable" checked> Assignable Status</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_at" checked> Created At</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="updated_at"> Updated At</label></li>
                    </ul>
                </div>

                <!-- Export Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" title="Export" aria-label="Export">
                        <i class="bi bi-box-arrow-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="#" id="exportCsv">Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#" id="exportExcel">Export as Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="exportPdf">Export as PDF</a></li>
                    </ul>
                </div>

                <!-- Print Button -->
                <button class="btn btn-warning btn-sm" id="printBtn" title="Print" aria-label="Print" data-bs-toggle="tooltip">
                    <i class="bi bi-printer"></i>
                </button>

                <!-- Fullscreen Button -->
                <button class="btn btn-primary btn-sm" id="fullscreenBtn" title="Fullscreen" aria-label="Fullscreen" data-bs-toggle="tooltip">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Advanced Search Button -->
                <button class="btn btn-secondary btn-sm" id="advancedSearchBtn" title="Advanced Search" aria-label="Advanced Search" data-bs-toggle="tooltip">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <!-- Vertical Divider -->
            <div class="vr" style="height: 32px;"></div>

            <!-- Search Input -->
            <div style="flex: 1; min-width: 200px;">
                <input type="text" id="users_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <!-- Usertype Filter -->
            <div style="width: 150px;">
                <select id="users_usertype" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Usertypes</option>
                    <option value="superadmin">Superadmin</option>
                    <option value="readandwrite">Read and Write</option>
                    <option value="readonly">Readonly</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div style="width: 130px;">
                <select id="users_type" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Types</option>
                    <option value="system">System User</option>
                    <option value="non_system">Assignable Only</option>
                </select>
            </div>

            <!-- Assignable Filter -->
            <div class="form-check" style="margin-top: 8px;">
                <input class="form-check-input" type="checkbox" id="users_assignable" style="margin-top: 6px;">
                <label class="form-check-label" for="users_assignable" style="margin-left: 5px; font-size: 13px;">
                    Assignable Only
                </label>
            </div>

            <!-- Clear Filters Button -->
            <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn" title="Clear filters" data-bs-toggle="tooltip" style="height: 32px; width: 32px; padding: 0;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Advanced Search Modal -->
        <div class="modal fade" id="advancedSearchModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Advanced Search</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_username" placeholder="Username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_email" placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Usertype</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_usertype">
                                    <option value="">All Usertypes</option>
                                    <option value="superadmin">Superadmin</option>
                                    <option value="readandwrite">Read and Write</option>
                                    <option value="readonly">Readonly</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Display Name</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_display_name" placeholder="Azure Display Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure UPN</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_upn" placeholder="Azure UPN">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Object ID</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_object_id" placeholder="Azure Object ID">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Account Enabled</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_entra_account_enabled">
                                    <option value="">All</option>
                                    <option value="enabled">Enabled</option>
                                    <option value="disabled">Disabled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Last Password Change</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_last_password_change_at" placeholder="e.g. 2026-03 or Mar 2026">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Job Title</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_job_title" placeholder="Azure Job Title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Employee ID</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_employee_id" placeholder="Azure Employee ID">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Department</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_department" placeholder="Azure Department">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Manager Name</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_manager_display_name" placeholder="Azure Manager Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Manager UPN</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_manager_upn" placeholder="Azure Manager UPN">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Manager Email</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_manager_mail" placeholder="Azure Manager Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Azure Email</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_entra_mail" placeholder="Azure Email">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" id="applyAdvancedSearch">Search</button>
                        <button type="button" class="btn btn-warning btn-sm" id="clearAdvancedSearch">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Pagination Info -->
        <div class="pagination-info">
            <span id="paginationInfo"><?= count($users) > 0 ? 'Showing 1 to ' . min(20, count($users)) . ' of ' . count($users) . ' rows' : 'No results found' ?></span>
            <div class="rows-per-page">
                <label for="rowsPerPageSelect">Rows per page:</label>
                <select id="rowsPerPageSelect">
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all users">
                    </th>
                    <th>ID</th>
                    <th data-column="name">Name</th>
                    <th data-column="type">Type</th>
                    <th data-column="email">Email</th>
                    <th data-column="usertype">Usertype</th>
                    <th data-column="entra_display_name" style="display: none;">Azure Display Name</th>
                    <th data-column="entra_user_principal_name" style="display: none;">Azure UPN</th>
                    <th data-column="entra_object_id" style="display: none;">Azure Object ID</th>
                    <th data-column="entra_account_enabled" style="display: none;">Azure Account Enabled</th>
                    <th data-column="entra_last_password_change_at" style="display: none;">Azure Last Password Change</th>
                    <th data-column="entra_job_title" style="display: none;">Azure Job Title</th>
                    <th data-column="entra_employee_id" style="display: none;">Azure Employee ID</th>
                    <th data-column="entra_department" style="display: none;">Azure Department</th>
                    <th data-column="entra_manager_display_name" style="display: none;">Azure Manager Name</th>
                    <th data-column="entra_manager_user_principal_name" style="display: none;">Azure Manager UPN</th>
                    <th data-column="entra_manager_mail" style="display: none;">Azure Manager Email</th>
                    <th data-column="assignable">Assignable</th>
                    <th data-column="created_at">Created At</th>
                    <th data-column="updated_at" style="display: none;">Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersTable">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <?php $detailsId = $user['is_system_user'] ? $user['id'] : 'A-' . $user['assignable_id']; ?>
                        <td>
                            <input type="checkbox" class="form-check-input userCheckbox" value="<?= $user['id'] ?>" title="Select this user">
                        </td>
                        <td><?= $user['id'] ?></td>
                        <td data-column="name"><?= esc($user['display_name']) ?></td>
                        <td data-column="type">
                            <?php if ($user['is_system_user']): ?>
                                <span class="badge bg-primary" title="System user with login credentials">
                                    <i class="bi bi-person-check-fill"></i> System User
                                </span>
                            <?php else: ?>
                                <span class="badge bg-info" title="Assignable user without system access">
                                    <i class="bi bi-person-badge"></i> Assignable Only
                                </span>
                            <?php endif; ?>
                        </td>
                        <td data-column="email"><?= $user['email'] ? esc($user['email']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="usertype">
                            <?php if ($user['usertype']): ?>
                                <span class="status-badge status-<?= $user['usertype'] ?>">
                                    <?= ucfirst($user['usertype']) ?>
                                </span>
                                <?php if (!empty($user['added_role_name'])): ?>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary">Added: <?= esc($user['added_role_name']) ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td data-column="entra_display_name" style="display: none;"><?= !empty($user['entra_display_name']) ? esc($user['entra_display_name']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_user_principal_name" style="display: none;"><?= !empty($user['entra_user_principal_name']) ? esc($user['entra_user_principal_name']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_object_id" style="display: none;"><?= !empty($user['entra_object_id']) ? esc($user['entra_object_id']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_account_enabled" style="display: none;">
                            <?php if (array_key_exists('entra_account_enabled', $user) && $user['entra_account_enabled'] !== null): ?>
                                <?php if ((string) $user['entra_account_enabled'] === '1'): ?>
                                    <span class="badge bg-success">Enabled</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Disabled</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td data-column="entra_last_password_change_at" style="display: none;"><?= !empty($user['entra_last_password_change_at']) ? date('M d, Y h:i A', strtotime($user['entra_last_password_change_at'])) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_job_title" style="display: none;"><?= !empty($user['entra_job_title']) ? esc($user['entra_job_title']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_employee_id" style="display: none;"><?= !empty($user['entra_employee_id']) ? esc($user['entra_employee_id']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_department" style="display: none;"><?= !empty($user['entra_department']) ? esc($user['entra_department']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_manager_display_name" style="display: none;"><?= !empty($user['entra_manager_display_name']) ? esc($user['entra_manager_display_name']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_manager_user_principal_name" style="display: none;"><?= !empty($user['entra_manager_user_principal_name']) ? esc($user['entra_manager_user_principal_name']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="entra_manager_mail" style="display: none;"><?= !empty($user['entra_manager_mail']) ? esc($user['entra_manager_mail']) : '<span class="text-muted">—</span>' ?></td>
                        <td data-column="assignable">
                            <?php if ($user['is_system_user']): ?>
                                <button class="btn btn-sm <?= $user['is_assignable'] ? 'btn-success' : 'btn-outline-secondary' ?> sync-toggle-btn" 
                                        data-user-id="<?= $user['id'] ?>" 
                                        data-synced="<?= $user['is_assignable'] ? '1' : '0' ?>" 
                                        title="<?= $user['is_assignable'] ? 'Synced to assignable users' : 'Not synced' ?>">
                                    <i class="bi bi-<?= $user['is_assignable'] ? 'check-circle-fill' : 'circle' ?>"></i>
                                    <?= $user['is_assignable'] ? 'Synced' : 'Not Synced' ?>
                                </button>
                            <?php else: ?>
                                <span class="badge bg-success" title="This is an assignable user">
                                    <i class="bi bi-check-circle-fill"></i> Yes
                                </span>
                            <?php endif; ?>
                        </td>
                        <td data-column="created_at"><?= date('M d, Y h:i A', strtotime($user['created_at'])) ?></td>
                        <td data-column="updated_at" style="display: none;"><?= date('M d, Y h:i A', strtotime($user['updated_at'])) ?></td>
                        <td>
                            <?php if ($user['is_system_user']): ?>
                                <a href="<?= site_url('users/details/' . $detailsId) ?>" class="action-btn action-btn-view" title="View" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="action-btn action-btn-edit" title="Edit" data-bs-toggle="tooltip"><i class="bi bi-pencil"></i></a>
                                <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
                            <?php else: ?>
                                <a href="<?= site_url('users/details/' . $detailsId) ?>" class="action-btn action-btn-view" title="View" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                <a href="<?= site_url('settings/assigned-users/edit/' . $user['assignable_id']) ?>" class="action-btn action-btn-edit" title="Edit" data-bs-toggle="tooltip"><i class="bi bi-pencil"></i></a>
                                <a href="<?= site_url('settings/assigned-users/delete/' . $user['assignable_id'] . '?from=users') ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="pagination-controls" id="paginationControls">
            <button id="prevPageBtn" title="Previous page">← Previous</button>
            <div id="pageNumbers" style="display: flex; gap: 3px;"></div>
            <button id="nextPageBtn" title="Next page">Next →</button>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('users_search');
            const usertypeFilter = document.getElementById('users_usertype');
            const typeFilter = document.getElementById('users_type');
            const assignableCheckbox = document.getElementById('users_assignable');
            const tableBody = document.getElementById('usersTable');
            const selectAllCheckbox = document.getElementById('selectAll');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
            const refreshBtn = document.getElementById('refreshBtn');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const advancedSearchBtn = document.getElementById('advancedSearchBtn');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const printBtn = document.getElementById('printBtn');
            const rowsPerPageSelect = document.getElementById('rowsPerPageSelect');
            const paginationInfoEl = document.getElementById('paginationInfo');
            const paginationControlsEl = document.getElementById('paginationControls');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const pageNumbersContainer = document.getElementById('pageNumbers');
            
            let allUsers = <?= json_encode($users) ?>;
            let filteredUsers = [...allUsers];
            let currentPage = 1;
            let rowsPerPage = 20;

            // Column visibility toggle
            document.querySelectorAll('.columnToggle').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const column = this.value;
                    const cells = document.querySelectorAll(`[data-column="${column}"]`);
                    cells.forEach(cell => {
                        cell.style.display = this.checked ? '' : 'none';
                    });
                });
            });

            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.userCheckbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateDeleteButtonState();
            });

            // Update delete button state
            function updateDeleteButtonState() {
                const checkedCount = document.querySelectorAll('.userCheckbox:checked').length;
                deleteSelectedBtn.disabled = checkedCount === 0;
            }

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('userCheckbox')) {
                    updateDeleteButtonState();
                }
            });

            // Delete selected users
            deleteSelectedBtn.addEventListener('click', function() {
                const selected = Array.from(document.querySelectorAll('.userCheckbox:checked')).map(cb => cb.value);
                if (selected.length > 0 && confirm(`Are you sure you want to delete ${selected.length} user(s)?`)) {
                    window.location.href = `<?= site_url('users/deleteMultiple') ?>?ids=${selected.join(',')}`;
                }
            });

            // Refresh button
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });

            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                usertypeFilter.value = '';
                typeFilter.value = '';
                assignableCheckbox.checked = false;
                document.querySelectorAll('.advSearchField').forEach(field => {
                    field.value = '';
                });
                filterUsers();
            });

            const fullscreenIcon = fullscreenBtn ? fullscreenBtn.querySelector('i') : null;

            function isFullscreenActive() {
                return !!(document.fullscreenElement || document.webkitFullscreenElement);
            }

            function updateFullscreenButtonState() {
                if (!fullscreenBtn) {
                    return;
                }

                const active = isFullscreenActive();
                fullscreenBtn.classList.toggle('btn-primary', !active);
                fullscreenBtn.classList.toggle('btn-danger', active);
                fullscreenBtn.setAttribute('title', active ? 'Exit Fullscreen' : 'Fullscreen');
                fullscreenBtn.setAttribute('aria-label', active ? 'Exit Fullscreen' : 'Fullscreen');

                if (fullscreenIcon) {
                    fullscreenIcon.className = active ? 'bi bi-fullscreen-exit' : 'bi bi-arrows-fullscreen';
                }
            }

            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function() {
                    const mainContent = document.querySelector('.main-content');
                    if (!mainContent) {
                        return;
                    }

                    if (!isFullscreenActive()) {
                        if (mainContent.requestFullscreen) {
                            mainContent.requestFullscreen().catch(err => {
                                alert('Could not enter fullscreen: ' + err.message);
                            });
                        } else if (mainContent.webkitRequestFullscreen) {
                            mainContent.webkitRequestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        }
                    }
                });
            }

            document.addEventListener('fullscreenchange', updateFullscreenButtonState);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButtonState);
            updateFullscreenButtonState();

            // Print table
            printBtn.addEventListener('click', function() {
                window.print();
            });

            // Advanced search modal
            advancedSearchBtn.addEventListener('click', function() {
                new bootstrap.Modal(document.getElementById('advancedSearchModal')).show();
            });

            document.getElementById('applyAdvancedSearch').addEventListener('click', function() {
                filterUsers();
                bootstrap.Modal.getInstance(document.getElementById('advancedSearchModal')).hide();
            });

            document.getElementById('clearAdvancedSearch').addEventListener('click', function() {
                document.querySelectorAll('.advSearchField').forEach(field => {
                    field.value = '';
                });
            });

            // Filter and search functionality
            let searchTimer;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(filterUsers, 250);
            });

            usertypeFilter.addEventListener('change', filterUsers);
            typeFilter.addEventListener('change', filterUsers);
            assignableCheckbox.addEventListener('change', filterUsers);

            function filterUsers() {
                const searchTerm = searchInput.value.toLowerCase();
                const usertypeValue = usertypeFilter.value.toLowerCase();
                const typeValue = typeFilter.value.toLowerCase();
                const assignableOnly = assignableCheckbox.checked;

                const advUsername = (document.getElementById('advSearch_username')?.value || '').toLowerCase();
                const advEmail = (document.getElementById('advSearch_email')?.value || '').toLowerCase();
                const advUsertype = (document.getElementById('advSearch_usertype')?.value || '').toLowerCase();
                const advEntraDisplayName = (document.getElementById('advSearch_entra_display_name')?.value || '').toLowerCase();
                const advEntraUpn = (document.getElementById('advSearch_entra_upn')?.value || '').toLowerCase();
                const advEntraObjectId = (document.getElementById('advSearch_entra_object_id')?.value || '').toLowerCase();
                const advEntraAccountEnabled = (document.getElementById('advSearch_entra_account_enabled')?.value || '').toLowerCase();
                const advEntraLastPasswordChange = (document.getElementById('advSearch_entra_last_password_change_at')?.value || '').toLowerCase();
                const advEntraJobTitle = (document.getElementById('advSearch_entra_job_title')?.value || '').toLowerCase();
                const advEntraEmployeeId = (document.getElementById('advSearch_entra_employee_id')?.value || '').toLowerCase();
                const advEntraDepartment = (document.getElementById('advSearch_entra_department')?.value || '').toLowerCase();
                const advEntraManagerDisplayName = (document.getElementById('advSearch_entra_manager_display_name')?.value || '').toLowerCase();
                const advEntraManagerUpn = (document.getElementById('advSearch_entra_manager_upn')?.value || '').toLowerCase();
                const advEntraManagerMail = (document.getElementById('advSearch_entra_manager_mail')?.value || '').toLowerCase();
                const advEntraMail = (document.getElementById('advSearch_entra_mail')?.value || '').toLowerCase();

                filteredUsers = allUsers.filter(user => {
                    const username = (user.username || '').toLowerCase();
                    const email = (user.email || '').toLowerCase();
                    const usertype = (user.usertype || '').toLowerCase();
                    const entraDisplayName = (user.entra_display_name || '').toLowerCase();
                    const entraUpn = (user.entra_user_principal_name || '').toLowerCase();
                    const entraObjectId = (user.entra_object_id || '').toLowerCase();
                    const entraAccountEnabledRaw = user.entra_account_enabled;
                    const entraAccountEnabled = (entraAccountEnabledRaw === 1 || entraAccountEnabledRaw === '1' || entraAccountEnabledRaw === true) ? 'enabled' : ((entraAccountEnabledRaw === 0 || entraAccountEnabledRaw === '0' || entraAccountEnabledRaw === false) ? 'disabled' : '');
                    const entraLastPasswordChange = (user.entra_last_password_change_at || '').toLowerCase();
                    const entraJobTitle = (user.entra_job_title || '').toLowerCase();
                    const entraEmployeeId = (user.entra_employee_id || '').toLowerCase();
                    const entraDepartment = (user.entra_department || '').toLowerCase();
                    const entraManagerDisplayName = (user.entra_manager_display_name || '').toLowerCase();
                    const entraManagerUpn = (user.entra_manager_user_principal_name || '').toLowerCase();
                    const entraManagerMail = (user.entra_manager_mail || '').toLowerCase();
                    const entraMail = (user.entra_mail || '').toLowerCase();

                    const matchSearch = !searchTerm || 
                        user.display_name.toLowerCase().includes(searchTerm) ||
                        username.includes(searchTerm) ||
                        email.includes(searchTerm) ||
                        usertype.includes(searchTerm) ||
                        entraDisplayName.includes(searchTerm) ||
                        entraUpn.includes(searchTerm) ||
                        entraObjectId.includes(searchTerm) ||
                        entraAccountEnabled.includes(searchTerm) ||
                        entraLastPasswordChange.includes(searchTerm) ||
                        entraJobTitle.includes(searchTerm) ||
                        entraEmployeeId.includes(searchTerm) ||
                        entraDepartment.includes(searchTerm) ||
                        entraManagerDisplayName.includes(searchTerm) ||
                        entraManagerUpn.includes(searchTerm) ||
                        entraManagerMail.includes(searchTerm) ||
                        entraMail.includes(searchTerm);

                    const matchUsertype = !usertypeValue || usertype === usertypeValue;

                    const matchAdvUsername = !advUsername || username.includes(advUsername);
                    const matchAdvEmail = !advEmail || email.includes(advEmail);
                    const matchAdvUsertype = !advUsertype || usertype === advUsertype;
                    const matchAdvEntraDisplayName = !advEntraDisplayName || entraDisplayName.includes(advEntraDisplayName);
                    const matchAdvEntraUpn = !advEntraUpn || entraUpn.includes(advEntraUpn);
                    const matchAdvEntraObjectId = !advEntraObjectId || entraObjectId.includes(advEntraObjectId);
                    const matchAdvEntraAccountEnabled = !advEntraAccountEnabled || entraAccountEnabled === advEntraAccountEnabled;
                    const matchAdvEntraLastPasswordChange = !advEntraLastPasswordChange || entraLastPasswordChange.includes(advEntraLastPasswordChange);
                    const matchAdvEntraJobTitle = !advEntraJobTitle || entraJobTitle.includes(advEntraJobTitle);
                    const matchAdvEntraEmployeeId = !advEntraEmployeeId || entraEmployeeId.includes(advEntraEmployeeId);
                    const matchAdvEntraDepartment = !advEntraDepartment || entraDepartment.includes(advEntraDepartment);
                    const matchAdvEntraManagerDisplayName = !advEntraManagerDisplayName || entraManagerDisplayName.includes(advEntraManagerDisplayName);
                    const matchAdvEntraManagerUpn = !advEntraManagerUpn || entraManagerUpn.includes(advEntraManagerUpn);
                    const matchAdvEntraManagerMail = !advEntraManagerMail || entraManagerMail.includes(advEntraManagerMail);
                    const matchAdvEntraMail = !advEntraMail || entraMail.includes(advEntraMail);
                    
                    const matchType = !typeValue || 
                        (typeValue === 'system' && user.is_system_user) ||
                        (typeValue === 'non_system' && !user.is_system_user);
                    
                    const matchAssignable = !assignableOnly || user.is_assignable;

                    return matchSearch
                        && matchUsertype
                        && matchAdvUsername
                        && matchAdvEmail
                        && matchAdvUsertype
                        && matchAdvEntraDisplayName
                        && matchAdvEntraUpn
                        && matchAdvEntraObjectId
                        && matchAdvEntraAccountEnabled
                        && matchAdvEntraLastPasswordChange
                        && matchAdvEntraJobTitle
                        && matchAdvEntraEmployeeId
                        && matchAdvEntraDepartment
                        && matchAdvEntraManagerDisplayName
                        && matchAdvEntraManagerUpn
                        && matchAdvEntraManagerMail
                        && matchAdvEntraMail
                        && matchType
                        && matchAssignable;
                });

                currentPage = 1;
                renderTable();
            }

            function renderTable() {
                const total = filteredUsers.length;
                const totalPages = Math.ceil(total / rowsPerPage);

                if (totalPages > 0) {
                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }
                    if (currentPage < 1) {
                        currentPage = 1;
                    }
                } else {
                    currentPage = 1;
                }

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageUsers = filteredUsers.slice(start, end);

                tableBody.innerHTML = '';

                if (pageUsers.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="21" class="text-center text-muted">No results found</td></tr>`;
                } else {
                    pageUsers.forEach(user => {
                        const isSystemUser = user.is_system_user || false;
                        const isSynced = user.is_assignable || false;
                        const updatedAtCell = document.querySelector('[data-column="updated_at"]')?.style.display !== 'none' 
                            ? `<td data-column="updated_at">${new Date(user.updated_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</td>`
                            : `<td data-column="updated_at" style="display: none;">${new Date(user.updated_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</td>`;

                        const typeHtml = isSystemUser 
                            ? '<span class="badge bg-primary" title="System user with login credentials"><i class="bi bi-person-check-fill"></i> System User</span>'
                            : '<span class="badge bg-info" title="Assignable user without system access"><i class="bi bi-person-badge"></i> Assignable Only</span>';
                        
                        const emailHtml = user.email ? escapeHtml(user.email) : '<span class="text-muted">—</span>';
                        const entraDisplayNameHtml = user.entra_display_name ? escapeHtml(user.entra_display_name) : '<span class="text-muted">—</span>';
                        const entraUpnHtml = user.entra_user_principal_name ? escapeHtml(user.entra_user_principal_name) : '<span class="text-muted">—</span>';
                        const entraObjectIdHtml = user.entra_object_id ? escapeHtml(user.entra_object_id) : '<span class="text-muted">—</span>';
                        const entraAccountEnabledHtml = (user.entra_account_enabled === 1 || user.entra_account_enabled === '1' || user.entra_account_enabled === true)
                            ? '<span class="badge bg-success">Enabled</span>'
                            : ((user.entra_account_enabled === 0 || user.entra_account_enabled === '0' || user.entra_account_enabled === false)
                                ? '<span class="badge bg-danger">Disabled</span>'
                                : '<span class="text-muted">—</span>');
                        const entraLastPasswordChangeHtml = user.entra_last_password_change_at
                            ? new Date(user.entra_last_password_change_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })
                            : '<span class="text-muted">—</span>';
                        const entraJobTitleHtml = user.entra_job_title ? escapeHtml(user.entra_job_title) : '<span class="text-muted">—</span>';
                        const entraEmployeeIdHtml = user.entra_employee_id ? escapeHtml(user.entra_employee_id) : '<span class="text-muted">—</span>';
                        const entraDepartmentHtml = user.entra_department ? escapeHtml(user.entra_department) : '<span class="text-muted">—</span>';
                        const entraManagerDisplayNameHtml = user.entra_manager_display_name ? escapeHtml(user.entra_manager_display_name) : '<span class="text-muted">—</span>';
                        const entraManagerUpnHtml = user.entra_manager_user_principal_name ? escapeHtml(user.entra_manager_user_principal_name) : '<span class="text-muted">—</span>';
                        const entraManagerMailHtml = user.entra_manager_mail ? escapeHtml(user.entra_manager_mail) : '<span class="text-muted">—</span>';
                        const addedRoleHtml = user.added_role_name
                            ? `<div class="mt-1"><span class="badge bg-secondary">Added: ${escapeHtml(user.added_role_name)}</span></div>`
                            : '';
                        const usertypeHtml = user.usertype
                            ? `<span class="status-badge status-${user.usertype}">${formatUsertype(user.usertype)}</span>${addedRoleHtml}`
                            : '<span class="text-muted">—</span>';
                        
                        const assignableHtml = isSystemUser
                            ? `<button class="btn btn-sm ${isSynced ? 'btn-success' : 'btn-outline-secondary'} sync-toggle-btn" 
                                    data-user-id="${user.id}" 
                                    data-synced="${isSynced ? '1' : '0'}" 
                                    title="${isSynced ? 'Synced to assignable users' : 'Not synced'}">
                                <i class="bi bi-${isSynced ? 'check-circle-fill' : 'circle'}"></i>
                                ${isSynced ? 'Synced' : 'Not Synced'}
                            </button>`
                            : '<span class="badge bg-success" title="This is an assignable user"><i class="bi bi-check-circle-fill"></i> Yes</span>';
                        
                                const detailsId = isSystemUser ? user.id : `A-${user.assignable_id}`;
                                const actionsHtml = isSystemUser
                                     ? `<a href="<?= site_url('users/details/') ?>${detailsId}" class="action-btn action-btn-view" title="View" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                         <a href="<?= site_url('users/edit/') ?>${user.id}" class="action-btn action-btn-edit" title="Edit" data-bs-toggle="tooltip"><i class="bi bi-pencil"></i></a>
                                         <a href="<?= site_url('users/delete/') ?>${user.id}" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>`
                                     : `<a href="<?= site_url('users/details/') ?>${detailsId}" class="action-btn action-btn-view" title="View" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                         <a href="<?= site_url('settings/assigned-users/edit/') ?>${user.assignable_id}" class="action-btn action-btn-edit" title="Edit" data-bs-toggle="tooltip"><i class="bi bi-pencil"></i></a>
                                         <a href="<?= site_url('settings/assigned-users/delete/') ?>${user.assignable_id}?from=users" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>`;

                        tableBody.innerHTML += `
                            <tr>
                                <td><input type="checkbox" class="form-check-input userCheckbox" value="${user.id}" title="Select this user"></td>
                                <td>${user.id}</td>
                                <td data-column="name">${escapeHtml(user.display_name)}</td>
                                <td data-column="type">${typeHtml}</td>
                                <td data-column="email">${emailHtml}</td>
                                <td data-column="usertype">${usertypeHtml}</td>
                                <td data-column="entra_display_name" style="display: none;">${entraDisplayNameHtml}</td>
                                <td data-column="entra_user_principal_name" style="display: none;">${entraUpnHtml}</td>
                                <td data-column="entra_object_id" style="display: none;">${entraObjectIdHtml}</td>
                                <td data-column="entra_account_enabled" style="display: none;">${entraAccountEnabledHtml}</td>
                                <td data-column="entra_last_password_change_at" style="display: none;">${entraLastPasswordChangeHtml}</td>
                                <td data-column="entra_job_title" style="display: none;">${entraJobTitleHtml}</td>
                                <td data-column="entra_employee_id" style="display: none;">${entraEmployeeIdHtml}</td>
                                <td data-column="entra_department" style="display: none;">${entraDepartmentHtml}</td>
                                <td data-column="entra_manager_display_name" style="display: none;">${entraManagerDisplayNameHtml}</td>
                                <td data-column="entra_manager_user_principal_name" style="display: none;">${entraManagerUpnHtml}</td>
                                <td data-column="entra_manager_mail" style="display: none;">${entraManagerMailHtml}</td>
                                <td data-column="assignable">${assignableHtml}</td>
                                <td data-column="created_at">${new Date(user.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</td>
                                ${updatedAtCell}
                                <td>${actionsHtml}</td>
                            </tr>`;
                    });
                }

                // Update pagination info
                const showing = pageUsers.length;
                if (total === 0) {
                    paginationInfoEl.textContent = 'No results found';
                } else {
                    paginationInfoEl.textContent = `Showing ${start + 1} to ${start + showing} of ${total} rows`;
                }

                // Render pagination controls
                renderPagination();

                // Apply column visibility
                document.querySelectorAll('.columnToggle').forEach(checkbox => {
                    const column = checkbox.value;
                    const cells = document.querySelectorAll(`[data-column="${column}"]`);
                    cells.forEach(cell => {
                        cell.style.display = checkbox.checked ? '' : 'none';
                    });
                });

                updateDeleteButtonState();
            }

            function renderPagination() {
                const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);
                pageNumbersContainer.innerHTML = '';

                if (totalPages <= 1) {
                    prevPageBtn.disabled = true;
                    nextPageBtn.disabled = true;
                    return;
                }

                // Page buttons
                const maxButtons = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(totalPages, startPage + maxButtons - 1);

                if (endPage - startPage < maxButtons - 1) {
                    startPage = Math.max(1, endPage - maxButtons + 1);
                }

                if (startPage > 1) {
                    const firstBtn = document.createElement('button');
                    firstBtn.textContent = '1';
                    firstBtn.addEventListener('click', () => {
                        currentPage = 1;
                        renderTable();
                    });
                    pageNumbersContainer.appendChild(firstBtn);

                    if (startPage > 2) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        pageNumbersContainer.appendChild(ellipsis);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    if (i === currentPage) {
                        pageBtn.classList.add('active');
                    }
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        renderTable();
                    });
                    pageNumbersContainer.appendChild(pageBtn);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        pageNumbersContainer.appendChild(ellipsis);
                    }

                    const lastBtn = document.createElement('button');
                    lastBtn.textContent = totalPages;
                    lastBtn.addEventListener('click', () => {
                        currentPage = totalPages;
                        renderTable();
                    });
                    pageNumbersContainer.appendChild(lastBtn);
                }

                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
            }

            if (prevPageBtn) {
                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
            }

            if (nextPageBtn) {
                nextPageBtn.addEventListener('click', function() {
                    const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                });
            }

            // Rows per page change
            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable();
            });

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function formatUsertype(usertype) {
                const usertypeMap = {
                    'readonly': 'Readonly',
                    'readandwrite': 'Read and Write',
                    'superadmin': 'Superadmin'
                };
                return usertypeMap[usertype] || usertype;
            }

            // Sync toggle functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.sync-toggle-btn')) {
                    const btn = e.target.closest('.sync-toggle-btn');
                    const userId = btn.getAttribute('data-user-id');
                    const isSynced = btn.getAttribute('data-synced') === '1';

                    if (confirm(`Are you sure you want to ${isSynced ? 'remove this user from' : 'add this user to'} assignable users?`)) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';

                        fetch(`<?= site_url('users/toggleSync/') ?>${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const newSynced = data.synced;
                                btn.setAttribute('data-synced', newSynced ? '1' : '0');
                                btn.className = `btn btn-sm ${newSynced ? 'btn-success' : 'btn-outline-secondary'} sync-toggle-btn`;
                                btn.innerHTML = `<i class="bi bi-${newSynced ? 'check-circle-fill' : 'circle'}"></i> ${newSynced ? 'Synced' : 'Not Synced'}`;
                                btn.title = newSynced ? 'Synced to assignable users' : 'Not synced';
                                
                                const user = allUsers.find(u => u.id == userId);
                                if (user) {
                                    user.is_assignable = newSynced;
                                }

                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                alertDiv.innerHTML = `<i class="bi bi-check-circle"></i> ${data.message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                                document.querySelector('.main-content').insertBefore(alertDiv, document.querySelector('.pagination-info'));
                                
                                setTimeout(() => {
                                    alertDiv.remove();
                                }, 3000);
                            } else {
                                alert('Error: ' + data.message);
                            }
                            btn.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while toggling sync status');
                            btn.disabled = false;
                            btn.innerHTML = `<i class="bi bi-${isSynced ? 'check-circle-fill' : 'circle'}"></i> ${isSynced ? 'Synced' : 'Not Synced'}`;
                        });
                    }
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initial render
            renderTable();
        });
    </script>
</body>
</html>

