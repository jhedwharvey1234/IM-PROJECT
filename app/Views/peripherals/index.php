<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peripherals Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .status-available { background-color: #28a745; color: #fff; }
        .status-in_use { background-color: #007bff; color: #fff; }
        .status-standby { background-color: #ffc107; color: #000; }
        .status-under_repair { background-color: #fd7e14; color: #fff; }
        .status-retired { background-color: #6c757d; color: #fff; }
        .status-lost { background-color: #dc3545; color: #fff; }
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
    <?= view('partials/header', ['title' => 'Peripherals Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Peripherals</span>
        </div>
        
        <!-- Toolbar with Search and Filters in Single Row -->
        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <!-- Action Buttons Group -->
            <div class="d-flex gap-1">
                <!-- Create Button -->
                <a href="<?= site_url('peripherals/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
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
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="asset_id" checked> Asset</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="peripheral_type_id" checked> Type</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="brand" checked> Brand</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="model" checked> Model</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="model_number"> Model Number</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="serial_number" checked> Serial #</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="device_image"> Device Image</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="purchase_date"> Purchase Date</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="purchase_cost"> Purchase Cost</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="order_number"> Order Number</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="supplier"> Supplier</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="vendor"> Vendor</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="warranty_expiry"> Warranty Expiry</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="qty"> Quantity</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="requestable"> Requestable</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="byod"> BYOD</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="status" checked> Status</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="condition_status" checked> Condition</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="criticality"> Criticality</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="department_id"> Department</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="location_id"> Location</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="workstation_id"> Workstation</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="assigned_to_user_id"> Assigned To</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_at"> Created At</label></li>
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
                <input type="text" id="peripherals_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <!-- Status Filter -->
            <div style="width: 150px;">
                <select id="peripherals_status" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="in_use">In Use</option>
                    <option value="standby">Standby</option>
                    <option value="under_repair">Under Repair</option>
                    <option value="retired">Retired</option>
                    <option value="lost">Lost</option>
                </select>
            </div>

            <!-- Date From Filter -->
            <div style="width: 140px;">
                <input type="date" id="peripherals_start_date" class="form-control form-control-sm" title="Start date" placeholder="From date" style="height: 32px;">
            </div>

            <!-- Date To Filter -->
            <div style="width: 140px;">
                <input type="date" id="peripherals_end_date" class="form-control form-control-sm" title="End date" placeholder="To date" style="height: 32px;">
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
                                <label class="form-label">Type</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_type">
                                    <option value="">All Types</option>
                                    <?php foreach ($peripheral_types_map as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_brand" placeholder="Brand">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_model" placeholder="Model">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_serial" placeholder="Serial Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_status">
                                    <option value="">All Statuses</option>
                                    <option value="available">Available</option>
                                    <option value="in_use">In Use</option>
                                    <option value="standby">Standby</option>
                                    <option value="under_repair">Under Repair</option>
                                    <option value="retired">Retired</option>
                                    <option value="lost">Lost</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Condition</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_condition">
                                    <option value="">All Conditions</option>
                                    <option value="new">New</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="damaged">Damaged</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_department">
                                    <option value="">All Departments</option>
                                    <?php foreach ($departments as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_location">
                                    <option value="">All Locations</option>
                                    <?php foreach ($locations as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Workstation</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_workstation">
                                    <option value="">All Workstations</option>
                                    <?php foreach ($workstations as $id => $code): ?>
                                        <option value="<?= $id ?>"><?= $code ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assigned To</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_assigned">
                                    <option value="">All Users</option>
                                    <?php foreach ($assignable_users as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
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
            <span id="paginationInfo">Showing 1 to <?= min(20, count($peripherals)) ?> of <?= count($peripherals) ?> rows</span>
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
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all peripherals">
                    </th>
                    <th>ID</th>
                    <th data-column="asset_id">Asset</th>
                    <th data-column="peripheral_type_id">Type</th>
                    <th data-column="brand">Brand</th>
                    <th data-column="model">Model</th>
                    <th data-column="model_number" style="display: none;">Model Number</th>
                    <th data-column="serial_number">Serial #</th>
                    <th data-column="device_image" style="display: none;">Device Image</th>
                    <th data-column="purchase_date" style="display: none;">Purchase Date</th>
                    <th data-column="purchase_cost" style="display: none;">Purchase Cost</th>
                    <th data-column="order_number" style="display: none;">Order Number</th>
                    <th data-column="supplier" style="display: none;">Supplier</th>
                    <th data-column="vendor" style="display: none;">Vendor</th>
                    <th data-column="warranty_expiry" style="display: none;">Warranty Expiry</th>
                    <th data-column="qty" style="display: none;">Quantity</th>
                    <th data-column="requestable" style="display: none;">Requestable</th>
                    <th data-column="byod" style="display: none;">BYOD</th>
                    <th data-column="status">Status</th>
                    <th data-column="condition_status">Condition</th>
                    <th data-column="criticality" style="display: none;">Criticality</th>
                    <th data-column="department_id" style="display: none;">Department</th>
                    <th data-column="location_id" style="display: none;">Location</th>
                    <th data-column="workstation_id" style="display: none;">Workstation</th>
                    <th data-column="assigned_to_user_id" style="display: none;">Assigned To</th>
                    <th data-column="created_at" style="display: none;">Created At</th>
                    <th data-column="updated_at" style="display: none;">Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="peripheralsTable">
                <?php foreach ($peripherals as $peripheral): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input peripheralCheckbox" value="<?= $peripheral['id'] ?>" title="Select this peripheral">
                        </td>
                        <td><?= $peripheral['id'] ?></td>
                        <td data-column="asset_id">
                            <?php if (!empty($peripheral['asset_id']) && isset($assets[$peripheral['asset_id']])): ?>
                                <a href="<?= site_url('assets/details/' . $peripheral['asset_id']) ?>"><?= $assets[$peripheral['asset_id']] ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td data-column="peripheral_type_id"><?= $peripheral_types_map[$peripheral['peripheral_type_id']] ?? 'Unknown' ?></td>
                        <td data-column="brand"><?= $peripheral['brand'] ?? '-' ?></td>
                        <td data-column="model"><?= $peripheral['model'] ?? '-' ?></td>
                        <td data-column="model_number" style="display: none;"><?= $peripheral['model_number'] ?? '-' ?></td>
                        <td data-column="serial_number"><?= $peripheral['serial_number'] ?? '-' ?></td>
                        <td data-column="device_image" style="display: none;">
                            <?php if (!empty($peripheral['device_image'])): ?>
                                <img src="<?= base_url('uploads/devices/' . $peripheral['device_image']) ?>" alt="Device" style="max-width: 50px; max-height: 50px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td data-column="purchase_date" style="display: none;">
                            <?= !empty($peripheral['purchase_date']) ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '-' ?>
                        </td>
                        <td data-column="purchase_cost" style="display: none;">
                            <?= !empty($peripheral['purchase_cost']) ? '₱' . number_format($peripheral['purchase_cost'], 2) : '-' ?>
                        </td>
                        <td data-column="order_number" style="display: none;"><?= $peripheral['order_number'] ?? '-' ?></td>
                        <td data-column="supplier" style="display: none;"><?= $peripheral['supplier'] ?? '-' ?></td>
                        <td data-column="vendor" style="display: none;"><?= $peripheral['vendor'] ?? '-' ?></td>
                        <td data-column="warranty_expiry" style="display: none;">
                            <?= !empty($peripheral['warranty_expiry']) ? date('M d, Y', strtotime($peripheral['warranty_expiry'])) : '-' ?>
                        </td>
                        <td data-column="qty" style="display: none;"><?= $peripheral['qty'] ?? '1' ?></td>
                        <td data-column="requestable" style="display: none;">
                            <?php if (!empty($peripheral['requestable'])): ?>
                                <span class="badge bg-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td data-column="byod" style="display: none;">
                            <?php if (!empty($peripheral['byod'])): ?>
                                <span class="badge bg-info">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td data-column="status">
                            <span class="status-badge status-<?= $peripheral['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                            </span>
                        </td>
                        <td data-column="condition_status">
                            <span class="badge bg-secondary">
                                <?= ucfirst($peripheral['condition_status']) ?>
                            </span>
                        </td>
                        <td data-column="criticality" style="display: none;"><?= ucfirst($peripheral['criticality'] ?? '-') ?></td>
                        <td data-column="department_id" style="display: none;"><?= isset($departments[$peripheral['department_id']]) ? $departments[$peripheral['department_id']] : '-' ?></td>
                        <td data-column="location_id" style="display: none;"><?= isset($locations[$peripheral['location_id']]) ? $locations[$peripheral['location_id']] : '-' ?></td>
                        <td data-column="workstation_id" style="display: none;"><?= isset($workstations[$peripheral['workstation_id']]) ? $workstations[$peripheral['workstation_id']] : '-' ?></td>
                        <td data-column="assigned_to_user_id" style="display: none;"><?= isset($assignable_users[$peripheral['assigned_to_user_id']]) ? $assignable_users[$peripheral['assigned_to_user_id']] : '-' ?></td>
                        <td data-column="created_at" style="display: none;"><?= date('M d, Y h:i A', strtotime($peripheral['created_at'])) ?></td>
                        <td data-column="updated_at" style="display: none;"><?= date('M d, Y h:i A', strtotime($peripheral['updated_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('peripherals/details/' . $peripheral['id']) ?>" class="action-btn action-btn-details" title="View Details" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                            <a href="<?= site_url('peripherals/edit/' . $peripheral['id']) ?>" class="action-btn action-btn-edit" title="Edit Peripheral" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= site_url('peripherals/delete/' . $peripheral['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure?')" title="Delete Peripheral" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
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
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Column visibility toggle
    const columnToggles = document.querySelectorAll('.columnToggle');
    columnToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const column = this.value;
            const isChecked = this.checked;
            const headers = document.querySelectorAll(`th[data-column="${column}"]`);
            const cells = document.querySelectorAll(`td[data-column="${column}"]`);
            
            headers.forEach(header => {
                header.style.display = isChecked ? '' : 'none';
            });
            cells.forEach(cell => {
                cell.style.display = isChecked ? '' : 'none';
            });
        });
    });

    // Search functionality
    let searchTimer;
    const searchInput = document.getElementById('peripherals_search');
    const tableBody = document.getElementById('peripheralsTable');

    function fetchResults() {
        const q = searchInput.value;
        const status = document.getElementById('peripherals_status').value;
        const start = document.getElementById('peripherals_start_date').value;
        const end = document.getElementById('peripherals_end_date').value;

        const params = new URLSearchParams();
        if (q) params.set('search', q);
        if (status) params.set('status', status);
        if (start) params.set('start_date', start);
        if (end) params.set('end_date', end);

        // Simple client-side filtering for now
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = !q || text.includes(q.toLowerCase());
            const statusCell = row.querySelector('[data-column="status"]')?.textContent.toLowerCase();
            const matchesStatus = !status || statusCell?.includes(status.replace('_', ' '));
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(fetchResults, 300);
        });
    }

    document.getElementById('peripherals_status')?.addEventListener('change', fetchResults);
    document.getElementById('peripherals_start_date')?.addEventListener('change', fetchResults);
    document.getElementById('peripherals_end_date')?.addEventListener('change', fetchResults);

    // Clear filters
    document.getElementById('clearFiltersBtn')?.addEventListener('click', function() {
        searchInput.value = '';
        document.getElementById('peripherals_status').value = '';
        document.getElementById('peripherals_start_date').value = '';
        document.getElementById('peripherals_end_date').value = '';
        fetchResults();
    });

    // Advanced search modal
    document.getElementById('advancedSearchBtn')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('advancedSearchModal'));
        modal.show();
    });

    // Refresh button
    document.getElementById('refreshBtn')?.addEventListener('click', function() {
        location.reload();
    });

    // Fullscreen
    document.getElementById('fullscreenBtn')?.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    });

    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.peripheralCheckbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButton();
    });

    // Individual checkboxes
    document.querySelectorAll('.peripheralCheckbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.peripheralCheckbox:checked').length;
        document.getElementById('deleteSelectedBtn').disabled = checked === 0;
    }

    // Print function
    document.getElementById('printBtn')?.addEventListener('click', function() {
        window.print();
    });

    // Export functions (placeholder)
    document.getElementById('exportCsv')?.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Export to CSV functionality would be implemented here');
    });

    document.getElementById('exportExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Export to Excel functionality would be implemented here');
    });

    document.getElementById('exportPdf')?.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Export to PDF functionality would be implemented here');
    });
});
</script>
</body>
</html>
