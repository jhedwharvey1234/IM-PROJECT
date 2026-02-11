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
            <span id="paginationInfo">Showing 1 to <?= min(20, count($users)) ?> of <?= count($users) ?> rows</span>
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
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
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
            <!-- Pagination buttons will be generated by JavaScript -->
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
                filterUsers();
            });

            // Fullscreen toggle
            fullscreenBtn.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            });

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

                filteredUsers = allUsers.filter(user => {
                    const matchSearch = !searchTerm || 
                        user.display_name.toLowerCase().includes(searchTerm) ||
                        (user.username && user.username.toLowerCase().includes(searchTerm)) ||
                        (user.email && user.email.toLowerCase().includes(searchTerm)) ||
                        (user.usertype && user.usertype.toLowerCase().includes(searchTerm));

                    const matchUsertype = !usertypeValue || (user.usertype && user.usertype.toLowerCase() === usertypeValue);
                    
                    const matchType = !typeValue || 
                        (typeValue === 'system' && user.is_system_user) ||
                        (typeValue === 'non_system' && !user.is_system_user);
                    
                    const matchAssignable = !assignableOnly || user.is_assignable;

                    return matchSearch && matchUsertype && matchType && matchAssignable;
                });

                currentPage = 1;
                renderTable();
            }

            function renderTable() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageUsers = filteredUsers.slice(start, end);

                tableBody.innerHTML = '';

                if (pageUsers.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="10" class="text-center text-muted">No results found</td></tr>`;
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
                        const usertypeHtml = user.usertype ? `<span class="status-badge status-${user.usertype}">${formatUsertype(user.usertype)}</span>` : '<span class="text-muted">—</span>';
                        
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
                                <td data-column="assignable">${assignableHtml}</td>
                                <td data-column="created_at">${new Date(user.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })}</td>
                                ${updatedAtCell}
                                <td>${actionsHtml}</td>
                            </tr>`;
                    });
                }

                // Update pagination info
                const showing = pageUsers.length;
                const total = filteredUsers.length;
                paginationInfoEl.textContent = `Showing ${start + 1} to ${start + showing} of ${total} rows`;

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
                paginationControlsEl.innerHTML = '';

                if (totalPages <= 1) return;

                // Previous button
                const prevBtn = document.createElement('button');
                prevBtn.textContent = 'Previous';
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                paginationControlsEl.appendChild(prevBtn);

                // Page buttons
                const maxButtons = 10;
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
                    paginationControlsEl.appendChild(firstBtn);

                    if (startPage > 2) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        paginationControlsEl.appendChild(ellipsis);
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
                    paginationControlsEl.appendChild(pageBtn);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        paginationControlsEl.appendChild(ellipsis);
                    }

                    const lastBtn = document.createElement('button');
                    lastBtn.textContent = totalPages;
                    lastBtn.addEventListener('click', () => {
                        currentPage = totalPages;
                        renderTable();
                    });
                    paginationControlsEl.appendChild(lastBtn);
                }

                // Next button
                const nextBtn = document.createElement('button');
                nextBtn.textContent = 'Next';
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                });
                paginationControlsEl.appendChild(nextBtn);
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

