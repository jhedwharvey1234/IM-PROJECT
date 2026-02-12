<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .status-active { background-color: #28a745; color: #fff; }
        .status-inactive { background-color: #6c757d; color: #fff; }
        .status-maintenance { background-color: #ffc107; color: #000; }
        .status-deprecated { background-color: #dc3545; color: #fff; }
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
        .action-btn-details { background-color: #17a2b8; }
        .action-btn-edit { background-color: #ffc107; color: #000; }
        .action-btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Application Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Applications</span>
        </div>
        
        <!-- Toolbar with Search and Filters in Single Row -->
        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <!-- Action Buttons Group -->
            <div class="d-flex gap-1">
                <!-- Create Button -->
                <a href="<?= site_url('applications/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
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
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="app_code" checked> App Code</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="app_name" checked> App Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="department_name" checked> Department</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="owner_name" checked> Owner</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="business_criticality" checked> Criticality</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="status_name" checked> Status</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="version"> Version</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="vendor"> Vendor</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="license_type"> License Type</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="license_expiry"> License Expiry</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="date_created" checked> Created</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="date_updated"> Updated</label></li>
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
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="exportSelected">Export Selected</a></li>
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
                <input type="text" id="applications_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <!-- Criticality Filter -->
            <div style="width: 150px;">
                <select id="applications_criticality" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Criticality</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div style="width: 150px;">
                <select id="applications_status" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Deprecated">Deprecated</option>
                </select>
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
                                <label class="form-label">App Code</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_appCode" placeholder="App Code">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">App Name</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_appName" placeholder="App Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Owner</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_owner" placeholder="Owner">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vendor</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_vendor" placeholder="Vendor">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Business Criticality</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_criticality">
                                    <option value="">All</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_status">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Deprecated">Deprecated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_department" placeholder="Department">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Version</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_version" placeholder="Version">
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
            <span id="paginationInfo">Showing 1 to 20 of <?= count($applications) ?> rows</span>
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
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all applications">
                    </th>
                    <th data-column="app_code">App Code</th>
                    <th data-column="app_name">App Name</th>
                    <th data-column="department_name">Department</th>
                    <th data-column="owner_name">Owner</th>
                    <th data-column="business_criticality">Criticality</th>
                    <th data-column="status_name">Status</th>
                    <th data-column="version" style="display: none;">Version</th>
                    <th data-column="vendor" style="display: none;">Vendor</th>
                    <th data-column="license_type" style="display: none;">License Type</th>
                    <th data-column="license_expiry" style="display: none;">License Expiry</th>
                    <th data-column="date_created">Created</th>
                    <th data-column="date_updated" style="display: none;">Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="applicationsTable">
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input applicationCheckbox" value="<?= $app['id'] ?>" title="Select this application">
                        </td>
                        <td data-column="app_code">
                            <span class="badge bg-info"><?= esc($app['app_code']) ?></span>
                        </td>
                        <td data-column="app_name"><?= esc($app['app_name']) ?></td>
                        <td data-column="department_name"><?= esc($app['department_name'] ?? 'N/A') ?></td>
                        <td data-column="owner_name"><?= esc($app['owner_name'] ?? 'N/A') ?></td>
                        <td data-column="business_criticality">
                            <?php if ($app['business_criticality'] === 'High'): ?>
                                <span class="badge bg-danger">High</span>
                            <?php elseif ($app['business_criticality'] === 'Medium'): ?>
                                <span class="badge bg-warning">Medium</span>
                            <?php elseif ($app['business_criticality'] === 'Low'): ?>
                                <span class="badge bg-success">Low</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td data-column="status_name">
                            <span class="badge bg-secondary">
                                <?= esc($app['status_name'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td data-column="version" style="display: none;"><?= esc($app['version'] ?? 'N/A') ?></td>
                        <td data-column="vendor" style="display: none;"><?= esc($app['vendor'] ?? 'N/A') ?></td>
                        <td data-column="license_type" style="display: none;"><?= esc($app['license_type'] ?? 'N/A') ?></td>
                        <td data-column="license_expiry" style="display: none;"><?= esc($app['license_expiry'] ?? 'N/A') ?></td>
                        <td data-column="date_created"><?= esc($app['date_created'] ?? 'N/A') ?></td>
                        <td data-column="date_updated" style="display: none;"><?= esc($app['date_updated'] ?? 'N/A') ?></td>
                        <td>
                            <a href="<?= site_url('applications/details/' . $app['id']) ?>" class="action-btn action-btn-details" title="View Details" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                            <a href="<?= site_url('applications/edit/' . $app['id']) ?>" class="action-btn action-btn-edit" title="Edit Application" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= site_url('applications/delete/' . $app['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure?')" title="Delete Application" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="pagination-controls">
            <button id="prevPageBtn" title="Previous page">← Previous</button>
            <div id="pageNumbers" style="display: flex; gap: 3px;"></div>
            <button id="nextPageBtn" title="Next page">Next →</button>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Pagination variables
    let currentPage = 1;
    let rowsPerPage = 20;
    let totalRows = 0;
    let filteredRows = [];

    const selectAllCheckbox = document.getElementById('selectAll');
    const applicationCheckboxes = document.querySelectorAll('.applicationCheckbox');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const refreshBtn = document.getElementById('refreshBtn');
    const advancedSearchBtn = document.getElementById('advancedSearchBtn');
    const printBtn = document.getElementById('printBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const columnToggles = document.querySelectorAll('.columnToggle');
    const exportCsvBtn = document.getElementById('exportCsv');
    const exportExcelBtn = document.getElementById('exportExcel');
    const exportPdfBtn = document.getElementById('exportPdf');
    const exportSelectedBtn = document.getElementById('exportSelected');
    const searchInput = document.getElementById('applications_search');
    const tableBody = document.getElementById('applicationsTable');
    const rowsPerPageSelect = document.getElementById('rowsPerPageSelect');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const pageNumbersContainer = document.getElementById('pageNumbers');
    const paginationInfo = document.getElementById('paginationInfo');

    // Initialize total rows and filtered rows
    function initializePagination() {
        const allRows = document.querySelectorAll('#applicationsTable tr');
        totalRows = allRows.length;
        filteredRows = Array.from(allRows);
        currentPage = 1;
        updatePagination();
    }

    // Update rows per page
    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            updatePagination();
        });
    }

    // Update pagination display
    function updatePagination() {
        const allRows = document.querySelectorAll('#applicationsTable tr');
        
        // Filter out no-results row if it exists
        filteredRows = Array.from(allRows).filter(row => !row.classList.contains('no-results'));
        totalRows = filteredRows.length;

        if (totalRows === 0) {
            paginationInfo.textContent = 'No results found';
            prevPageBtn.disabled = true;
            nextPageBtn.disabled = true;
            pageNumbersContainer.innerHTML = '';
            return;
        }

        const totalPages = Math.ceil(totalRows / rowsPerPage);
        
        // Clamp current page
        if (currentPage > totalPages) {
            currentPage = totalPages;
        }
        if (currentPage < 1) {
            currentPage = 1;
        }

        // Calculate displayed rows
        const startRow = (currentPage - 1) * rowsPerPage + 1;
        const endRow = Math.min(currentPage * rowsPerPage, totalRows);
        paginationInfo.textContent = `Showing ${startRow} to ${endRow} of ${totalRows} rows`;

        // Hide all data rows
        filteredRows.forEach(row => row.style.display = 'none');

        // Show only rows for current page
        for (let i = (currentPage - 1) * rowsPerPage; i < currentPage * rowsPerPage && i < totalRows; i++) {
            filteredRows[i].style.display = '';
        }

        // Uncheck all checkboxes when changing page
        applicationCheckboxes.forEach(cb => cb.checked = false);
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        updateDeleteButton();

        // Generate page number buttons
        generatePageButtons(totalPages);

        // Update prev/next buttons
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    }

    // Generate page number buttons
    function generatePageButtons(totalPages) {
        pageNumbersContainer.innerHTML = '';

        const maxButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);

        if (endPage - startPage < maxButtons - 1) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        // Add first page and ellipsis if needed
        if (startPage > 1) {
            const btn1 = document.createElement('button');
            btn1.textContent = '1';
            btn1.addEventListener('click', () => {
                currentPage = 1;
                updatePagination();
            });
            pageNumbersContainer.appendChild(btn1);

            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                pageNumbersContainer.appendChild(ellipsis);
            }
        }

        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            if (i === currentPage) {
                btn.classList.add('active');
            }
            btn.addEventListener('click', () => {
                currentPage = i;
                updatePagination();
            });
            pageNumbersContainer.appendChild(btn);
        }

        // Add last page and ellipsis if needed
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                pageNumbersContainer.appendChild(ellipsis);
            }
            const btnLast = document.createElement('button');
            btnLast.textContent = totalPages;
            btnLast.addEventListener('click', () => {
                currentPage = totalPages;
                updatePagination();
            });
            pageNumbersContainer.appendChild(btnLast);
        }
    }

    // Previous/Next page buttons
    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });
    }

    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
    }

    // Select All Checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Individual Application Checkboxes
    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(applicationCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            updateDeleteButton();
        });
    });

    // Update Delete Button State
    function updateDeleteButton() {
        const selectedCount = Array.from(applicationCheckboxes).filter(cb => cb.checked).length;
        deleteSelectedBtn.disabled = selectedCount === 0;
        deleteSelectedBtn.title = selectedCount > 0 ? `Delete (${selectedCount})` : 'Delete';
    }

    // Delete Selected Applications
    deleteSelectedBtn.addEventListener('click', function() {
        const selected = Array.from(applicationCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Please select at least one application');
            return;
        }
        if (!confirm(`Delete ${selected.length} application(s)? This cannot be undone.`)) {
            return;
        }
        // Implement batch delete
        const formData = new FormData();
        selected.forEach(id => formData.append('ids[]', id));
        fetch('<?= site_url('applications/batch-delete') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Applications deleted successfully');
                location.reload();
            } else {
                alert('Error deleting applications');
            }
        })
        .catch(() => alert('Error deleting applications'));
    });

    // Refresh Button
    refreshBtn.addEventListener('click', function() {
        location.reload();
    });

    // Advanced Search Toggle (Open Modal)
    advancedSearchBtn.addEventListener('click', function() {
        const advModal = new bootstrap.Modal(document.getElementById('advancedSearchModal'));
        advModal.show();
    });

    // Criticality and status filter listeners
    const criticalitySelect = document.getElementById('applications_criticality');
    const statusSelect = document.getElementById('applications_status');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    if (criticalitySelect) {
        criticalitySelect.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            criticalitySelect.value = '';
            statusSelect.value = '';
            if (typeof advSearchFields !== 'undefined') {
                advSearchFields.forEach(field => field.value = '');
            }
            filterTableRows();
        });
    }

    // Advanced Search handlers
    const applyAdvancedSearchBtn = document.getElementById('applyAdvancedSearch');
    const clearAdvancedSearchBtn = document.getElementById('clearAdvancedSearch');
    const advSearchFields = document.querySelectorAll('.advSearchField');

    if (applyAdvancedSearchBtn) {
        applyAdvancedSearchBtn.addEventListener('click', function() {
            filterTableRows();
            bootstrap.Modal.getInstance(document.getElementById('advancedSearchModal')).hide();
        });
    }

    if (clearAdvancedSearchBtn) {
        clearAdvancedSearchBtn.addEventListener('click', function() {
            advSearchFields.forEach(field => field.value = '');
            filterTableRows();
        });
    }

    advSearchFields.forEach(field => {
        field.addEventListener('change', function() {
            filterTableRows();
        });
    });

    // Column Visibility Toggle
    columnToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const columnName = this.value;
            const isVisible = this.checked;
            const headers = document.querySelectorAll(`th[data-column="${columnName}"]`);
            const cells = document.querySelectorAll(`td[data-column="${columnName}"]`);
            
            headers.forEach(header => {
                header.style.display = isVisible ? '' : 'none';
            });
            cells.forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
        });
    });

    // Print Button
    printBtn.addEventListener('click', function() {
        const printWindow = window.open('', '', 'width=1200,height=600');
        const table = document.querySelector('table').outerHTML;
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Application Management - Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    table { font-size: 11px; }
                    .btn { display: none; }
                </style>
            </head>
            <body>
                <h2>Application Management Report</h2>
                <p>Generated: ${new Date().toLocaleString()}</p>
                ${table}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });

    // Fullscreen Button
    fullscreenBtn.addEventListener('click', function() {
        const mainContent = document.querySelector('.main-content');
        if (!document.fullscreenElement) {
            mainContent.requestFullscreen().catch(err => {
                alert('Could not enter fullscreen: ' + err.message);
            });
        } else {
            document.exitFullscreen();
        }
    });

    // Export Functions
    function exportTableToCSV(filename, selectedOnly = false) {
        const rows = selectedOnly ? 
            Array.from(document.querySelectorAll('#applicationsTable tr')).filter(tr => 
                tr.querySelector('input[type="checkbox"]')?.checked
            ) : 
            document.querySelectorAll('#applicationsTable tr');
        
        let csv = [];
        const headers = document.querySelectorAll('table th');
        csv.push(Array.from(headers).map(h => h.textContent.trim().replace(/"/g, '""')).join(','));
        
        rows.forEach(tr => {
            const cells = tr.querySelectorAll('td');
            csv.push(Array.from(cells).map(cell => `"${cell.textContent.trim().replace(/"/g, '""')}"`).join(','));
        });
        
        downloadFile(csv.join('\n'), filename, 'text/csv');
    }

    function downloadFile(content, filename, type) {
        const blob = new Blob([content], { type });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    exportCsvBtn.addEventListener('click', (e) => {
        e.preventDefault();
        exportTableToCSV('applications_' + new Date().toISOString().split('T')[0] + '.csv');
    });

    exportExcelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const html = `
            <table>
                ${document.querySelector('table').innerHTML}
            </table>
        `;
        const excelContent = `
            <html xmlns:x="urn:schemas-microsoft-com:office:excel">
            <head><meta charset="UTF-8"></head>
            <body>${html}</body>
            </html>
        `;
        downloadFile(excelContent, 'applications_' + new Date().toISOString().split('T')[0] + '.xls', 'application/vnd.ms-excel');
    });

    exportSelectedBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const selected = Array.from(applicationCheckboxes).filter(cb => cb.checked).length;
        if (selected === 0) {
            alert('Please select at least one application to export');
            return;
        }
        exportTableToCSV('applications_selected_' + new Date().toISOString().split('T')[0] + '.csv', true);
    });

    // Enhanced Search Functionality - Client-side filtering
    function getVisibleColumns() {
        const visible = [];
        columnToggles.forEach(toggle => {
            if (toggle.checked) {
                visible.push(toggle.value);
            }
        });
        return visible;
    }

    function filterTableRows() {
        const searchQuery = (searchInput.value || '').toLowerCase();
        
        // Simple filters from top bar
        const criticalityFilter = document.getElementById('applications_criticality').value || '';
        const statusFilter = document.getElementById('applications_status').value || '';
        
        // Advanced search fields
        const advAppCode = (document.getElementById('advSearch_appCode')?.value || '').toLowerCase();
        const advAppName = (document.getElementById('advSearch_appName')?.value || '').toLowerCase();
        const advOwner = (document.getElementById('advSearch_owner')?.value || '').toLowerCase();
        const advVendor = (document.getElementById('advSearch_vendor')?.value || '').toLowerCase();
        const advCriticality = document.getElementById('advSearch_criticality')?.value || '';
        const advStatus = document.getElementById('advSearch_status')?.value || '';
        const advDepartment = (document.getElementById('advSearch_department')?.value || '').toLowerCase();
        const advVersion = (document.getElementById('advSearch_version')?.value || '').toLowerCase();

        const allRows = document.querySelectorAll('#applicationsTable tr');
        const visibleColumns = getVisibleColumns();

        let visibleRowCount = 0;

        allRows.forEach(row => {
            let matches = true;

            // Simple search - checks visible columns
            if (searchQuery) {
                let foundInVisibleColumn = false;

                visibleColumns.forEach(colName => {
                    const cell = row.querySelector(`td[data-column="${colName}"]`);
                    if (cell && cell.textContent.toLowerCase().includes(searchQuery)) {
                        foundInVisibleColumn = true;
                    }
                });

                if (!foundInVisibleColumn) {
                    matches = false;
                }
            }

            // Simple criticality filter from top bar
            if (matches && criticalityFilter) {
                const cell = row.querySelector('td[data-column="business_criticality"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(criticalityFilter.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            // Simple status filter from top bar
            if (matches && statusFilter) {
                const cell = row.querySelector('td[data-column="status_name"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(statusFilter.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            // Advanced search filters
            if (matches && advAppCode) {
                const cell = row.querySelector('td[data-column="app_code"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advAppCode)) matches = false;
            }

            if (matches && advAppName) {
                const cell = row.querySelector('td[data-column="app_name"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advAppName)) matches = false;
            }

            if (matches && advOwner) {
                const cell = row.querySelector('td[data-column="owner_name"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advOwner)) matches = false;
            }

            if (matches && advVendor) {
                const cell = row.querySelector('td[data-column="vendor"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advVendor)) matches = false;
            }

            if (matches && advCriticality) {
                const cell = row.querySelector('td[data-column="business_criticality"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(advCriticality.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            if (matches && advStatus) {
                const cell = row.querySelector('td[data-column="status_name"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(advStatus.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            if (matches && advDepartment) {
                const cell = row.querySelector('td[data-column="department_name"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advDepartment)) matches = false;
            }

            if (matches && advVersion) {
                const cell = row.querySelector('td[data-column="version"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advVersion)) matches = false;
            }

            row.style.display = matches ? '' : 'none';
            if (matches) visibleRowCount++;
        });

        // Show message if no results
        if (visibleRowCount === 0) {
            if (tableBody.querySelector('.no-results')) {
                tableBody.querySelector('.no-results').remove();
            }
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results';
            noResultsRow.innerHTML = '<td colspan="50" class="text-center text-muted p-4">No applications match your search criteria</td>';
            tableBody.appendChild(noResultsRow);
        } else {
            if (tableBody.querySelector('.no-results')) {
                tableBody.querySelector('.no-results').remove();
            }
        }

        // Reset to first page and update pagination
        currentPage = 1;
        updatePagination();
    }

    // Trigger search on input
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterTableRows();
        });
    }

    // Initialize pagination on page load
    initializePagination();
});
</script>
</body>
</html>
