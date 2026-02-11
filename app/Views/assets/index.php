<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-in_transit { background-color: #17a2b8; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .pagination-info { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; margin-bottom: 15px; font-size: 14px; }
        .pagination-controls { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; margin-top: 15px; padding: 15px 0; border-top: 1px solid #dee2e6; }
        .pagination-controls button, .pagination-controls a { padding: 5px 10px; border: 1px solid #dee2e6; background: white; color: #0d6efd; text-decoration: none; cursor: pointer; border-radius: 3px; font-size: 13px; }
        .pagination-controls button:hover, .pagination-controls a:hover { background-color: #e9ecef; }
        .pagination-controls button:disabled { opacity: 0.5; cursor: not-allowed; }
        .pagination-controls button.active { background-color: #0d6efd; color: white; }
        .pagination-controls span { margin: 0 5px; }
        .rows-per-page { display: flex; align-items: center; gap: 8px; }
        .rows-per-page select { padding: 4px 6px; border: 1px solid #dee2e6; border-radius: 3px; font-size: 13px; }
        .breadcrumb-nav {  padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; font-size: 14px; cursor: pointer; text-decoration: none; border-radius: 4px; margin: 0 2px; }
        .action-btn:hover { opacity: 0.8; color: white; transform: translateY(-1px); }
        .action-btn-details { background-color: #17a2b8; }
        .action-btn-pdf { background-color: #5a6c7d; }
        .action-btn-edit { background-color: #ffc107; color: #000; }
        .action-btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Asset Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Assets</span>
        </div>
        
        <!-- Toolbar with Search and Filters in Single Row -->
        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <!-- Action Buttons Group -->
            <div class="d-flex gap-1">
                <!-- Create Button -->
                <a href="<?= site_url('assets/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
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
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="serial_number" checked> Serial #</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="model" checked> Model</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="model_number" checked> Model #</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="manufacturer"> Manufacturer</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="category"> Category</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="qty"> Qty</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="box_number"> Box #</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="device_image" checked> Device Image</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="barcode" checked> Barcode</label></li>
                       
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="status" checked> Status</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="purchase_date"> Purchase Date</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="purchase_cost"> Purchase Cost</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="order_number"> Order #</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="supplier"> Supplier</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="department_id"> Department</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="location_id"> Location</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="workstation_id"> Workstation</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="assigned_to_user_id"> Assigned To</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="requestable"> Requestable</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="byod"> BYOD</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="date_updated" checked> Date Updated</label></li>
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
                <input type="text" id="assets_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <!-- Status Filter -->
            <div style="width: 150px;">
                <select id="assets_status" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="ready to deploy">Ready to Deploy</option>
                    <option value="archived">Archived</option>
                    <option value="broken - not fixable">Broken - Not Fixable</option>
                    <option value="lost/stolen">Lost/Stolen</option>
                    <option value="out for diagnostics">Out for Diagnostics</option>
                    <option value="out for repair">Out for Repair</option>
                </select>
            </div>

            <!-- Date From Filter -->
            <div style="width: 140px;">
                <input type="date" id="assets_start_date" class="form-control form-control-sm" title="Start date" placeholder="From date" style="height: 32px;">
            </div>

            <!-- Date To Filter -->
            <div style="width: 140px;">
                <input type="date" id="assets_end_date" class="form-control form-control-sm" title="End date" placeholder="To date" style="height: 32px;">
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
                                <label class="form-label">Asset Tag</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_assetTag" placeholder="Asset Tag">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_serial" placeholder="Serial Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_model" placeholder="Model">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Manufacturer</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_manufacturer" placeholder="Manufacturer">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_category" placeholder="Category">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_status">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="ready to deploy">Ready to Deploy</option>
                                    <option value="archived">Archived</option>
                                    <option value="broken - not fixable">Broken - Not Fixable</option>
                                    <option value="lost/stolen">Lost/Stolen</option>
                                    <option value="out for diagnostics">Out for Diagnostics</option>
                                    <option value="out for repair">Out for Repair</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sender</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_sender" placeholder="Sender">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Recipient</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_recipient" placeholder="Recipient">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purchase Date From</label>
                                <input type="date" class="form-control form-control-sm advSearchField" id="advSearch_purchaseDateFrom">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purchase Date To</label>
                                <input type="date" class="form-control form-control-sm advSearchField" id="advSearch_purchaseDateTo">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_department">
                                    <option value="">All Departments</option>
                                    <?php if(isset($departments)): foreach ($departments as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_location">
                                    <option value="">All Locations</option>
                                    <?php if(isset($locations)): foreach ($locations as $id => $name): ?>
                                        <option value="<?= $id ?>"><?= $name ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Supplier</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_supplier" placeholder="Supplier">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Box Number</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_boxNumber" placeholder="Box Number">
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
            <span id="paginationInfo">Showing 1 to 20 of <?= count($assets) ?> rows</span>
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
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all assets">
                    </th>
                    <th>Asset Tag</th>
                    <th data-column="serial_number">Serial #</th>
                    <th data-column="model">Model</th>
                    <th data-column="model_number">Model #</th>
                    <th data-column="manufacturer" style="display: none;">Manufacturer</th>
                    <th data-column="category" style="display: none;">Category</th>
                    <th data-column="qty" style="display: none;">Qty</th>
                    <th data-column="box_number" style="display: none;">Box #</th>
                    <th data-column="device_image">Device Image</th>
                    <th data-column="barcode">Barcode</th>
                    <th data-column="sender" style="display: none;">Sender</th>
                    <th data-column="recipient" style="display: none;">Recipient</th>
                    <th data-column="status">Status</th>
                    <th data-column="purchase_date" style="display: none;">Purchase Date</th>
                    <th data-column="purchase_cost" style="display: none;">Purchase Cost</th>
                    <th data-column="order_number" style="display: none;">Order #</th>
                    <th data-column="supplier" style="display: none;">Supplier</th>
                    <th data-column="department_id" style="display: none;">Department</th>
                    <th data-column="location_id" style="display: none;">Location</th>
                    <th data-column="workstation_id" style="display: none;">Workstation</th>
                    <th data-column="assigned_to_user_id" style="display: none;">Assigned To</th>
                    <th data-column="requestable" style="display: none;">Requestable</th>
                    <th data-column="byod" style="display: none;">BYOD</th>
                    <th data-column="date_updated">Date Updated</th>
                    <th data-column="created_at" style="display: none;">Created At</th>
                    <th data-column="updated_at" style="display: none;">Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="assetsTable">
                <?php foreach ($assets as $asset): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input assetCheckbox" value="<?= $asset['id'] ?>" title="Select this asset">
                        </td>
                        <td><?= $asset['asset_tag'] ?? '-' ?></td>
                        <td data-column="serial_number"><?= $asset['serial_number'] ?? '-' ?></td>
                        <td data-column="model"><?= $asset['model'] ?? '-' ?></td>
                        <td data-column="model_number"><?= $asset['model_number'] ?? '-' ?></td>
                        <td data-column="manufacturer" style="display: none;"><?= $asset['manufacturer'] ?? '-' ?></td>
                        <td data-column="category" style="display: none;"><?= $asset['category'] ?? '-' ?></td>
                        <td data-column="qty" style="display: none;"><?= $asset['qty'] ?? '-' ?></td>
                        <td data-column="box_number" style="display: none;"><?= $asset['box_number'] ?? '-' ?></td>
                        <td data-column="device_image">
                            <?php if (!empty($asset['device_image'])): ?>
                                <img src="<?= base_url('uploads/devices/' . $asset['device_image']) ?>" alt="device" style="max-height:60px; max-width:120px;" />
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td data-column="barcode">
                            <?php if (!empty($asset['barcode'])): ?>
                                <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height:60px; max-width:120px;" />
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td data-column="sender" style="display: none;"><?= $asset['sender'] ?? '-' ?></td>
                        <td data-column="recipient" style="display: none;"><?= $asset['recipient'] ?? '-' ?></td>
                        <td data-column="status">
                            <span class="status-badge status-<?= $asset['status'] ?>">
                                <?= ucfirst($asset['status']) ?>
                            </span>
                        </td>
                        <td data-column="purchase_date" style="display: none;"><?= !empty($asset['purchase_date']) ? date('M d, Y', strtotime($asset['purchase_date'])) : '-' ?></td>
                        <td data-column="purchase_cost" style="display: none;"><?= !empty($asset['purchase_cost']) ? '$' . number_format($asset['purchase_cost'], 2) : '-' ?></td>
                        <td data-column="order_number" style="display: none;"><?= $asset['order_number'] ?? '-' ?></td>
                        <td data-column="supplier" style="display: none;"><?= $asset['supplier'] ?? '-' ?></td>
                        <td data-column="department_id" style="display: none;"><?= isset($departments) && isset($departments[$asset['department_id']]) ? $departments[$asset['department_id']] : '-' ?></td>
                        <td data-column="location_id" style="display: none;"><?= isset($locations) && isset($locations[$asset['location_id']]) ? $locations[$asset['location_id']] : '-' ?></td>
                        <td data-column="workstation_id" style="display: none;"><?= isset($workstations) && isset($workstations[$asset['workstation_id']]) ? $workstations[$asset['workstation_id']] : '-' ?></td>
                        <td data-column="assigned_to_user_id" style="display: none;"><?= isset($assignable_users) && isset($assignable_users[$asset['assigned_to_user_id']]) ? $assignable_users[$asset['assigned_to_user_id']] : '-' ?></td>
                        <td data-column="requestable" style="display: none;"><?= !empty($asset['requestable']) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                        <td data-column="byod" style="display: none;"><?= !empty($asset['byod']) ? '<span class="badge bg-info">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                        <td data-column="date_updated">
                            <?= !empty($asset['date_updated']) ? date('M d, Y h:i A', strtotime($asset['date_updated'])) : '-' ?>
                        </td>
                        <td data-column="created_at" style="display: none;"><?= date('M d, Y h:i A', strtotime($asset['created_at'])) ?></td>
                        <td data-column="updated_at" style="display: none;"><?= date('M d, Y h:i A', strtotime($asset['updated_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="action-btn action-btn-details" title="View Details" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                           
                            <a href="<?= site_url('assets/edit/' . $asset['id']) ?>" class="action-btn action-btn-edit" title="Edit Asset" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= site_url('assets/delete/' . $asset['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure?')" title="Delete Asset" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
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
    const assetCheckboxes = document.querySelectorAll('.assetCheckbox');
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
    const searchInput = document.getElementById('assets_search');
    const tableBody = document.getElementById('assetsTable');
    const rowsPerPageSelect = document.getElementById('rowsPerPageSelect');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const pageNumbersContainer = document.getElementById('pageNumbers');
    const paginationInfo = document.getElementById('paginationInfo');

    // Initialize total rows and filtered rows
    function initializePagination() {
        const allRows = document.querySelectorAll('#assetsTable tr');
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
        const allRows = document.querySelectorAll('#assetsTable tr');
        
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
        assetCheckboxes.forEach(cb => cb.checked = false);
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
            assetCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }

    // Individual Asset Checkboxes
    assetCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(assetCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(assetCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            updateDeleteButton();
        });
    });

    // Update Delete Button State
    function updateDeleteButton() {
        const selectedCount = Array.from(assetCheckboxes).filter(cb => cb.checked).length;
        deleteSelectedBtn.disabled = selectedCount === 0;
        deleteSelectedBtn.title = selectedCount > 0 ? `Delete (${selectedCount})` : 'Delete';
    }

    // Delete Selected Assets
    deleteSelectedBtn.addEventListener('click', function() {
        const selected = Array.from(assetCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Please select at least one asset');
            return;
        }
        if (!confirm(`Delete ${selected.length} asset(s)? This cannot be undone.`)) {
            return;
        }
        // Implement batch delete
        const formData = new FormData();
        selected.forEach(id => formData.append('ids[]', id));
        fetch('<?= site_url('assets/batch-delete') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Assets deleted successfully');
                location.reload();
            } else {
                alert('Error deleting assets');
            }
        })
        .catch(() => alert('Error deleting assets'));
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

    // Status and date filter listeners
    const statusSelect = document.getElementById('assets_status');
    const startDateInput = document.getElementById('assets_start_date');
    const endDateInput = document.getElementById('assets_end_date');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (endDateInput) {
        endDateInput.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusSelect.value = '';
            startDateInput.value = '';
            endDateInput.value = '';
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
                <title>Asset Management - Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    table { font-size: 11px; }
                    .btn { display: none; }
                </style>
            </head>
            <body>
                <h2>Asset Management Report</h2>
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
            Array.from(document.querySelectorAll('#assetsTable tr')).filter(tr => 
                tr.querySelector('input[type="checkbox"]')?.checked
            ) : 
            document.querySelectorAll('#assetsTable tr');
        
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
        exportTableToCSV('assets_' + new Date().toISOString().split('T')[0] + '.csv');
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
        downloadFile(excelContent, 'assets_' + new Date().toISOString().split('T')[0] + '.xls', 'application/vnd.ms-excel');
    });

    exportSelectedBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const selected = Array.from(assetCheckboxes).filter(cb => cb.checked).length;
        if (selected === 0) {
            alert('Please select at least one asset to export');
            return;
        }
        exportTableToCSV('assets_selected_' + new Date().toISOString().split('T')[0] + '.csv', true);
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
        const statusFilter = document.getElementById('assets_status').value || '';
        const startDate = document.getElementById('assets_start_date').value || '';
        const endDate = document.getElementById('assets_end_date').value || '';
        
        // Advanced search fields
        const advTag = (document.getElementById('advSearch_assetTag')?.value || '').toLowerCase();
        const advSerial = (document.getElementById('advSearch_serial')?.value || '').toLowerCase();
        const advModel = (document.getElementById('advSearch_model')?.value || '').toLowerCase();
        const advMfg = (document.getElementById('advSearch_manufacturer')?.value || '').toLowerCase();
        const advCat = (document.getElementById('advSearch_category')?.value || '').toLowerCase();
        const advStatus = document.getElementById('advSearch_status')?.value || '';
        const advSender = (document.getElementById('advSearch_sender')?.value || '').toLowerCase();
        const advRecipient = (document.getElementById('advSearch_recipient')?.value || '').toLowerCase();
        const advSupplier = (document.getElementById('advSearch_supplier')?.value || '').toLowerCase();
        const advBox = (document.getElementById('advSearch_boxNumber')?.value || '').toLowerCase();
        const advDepartment = document.getElementById('advSearch_department')?.value || '';
        const advLocation = document.getElementById('advSearch_location')?.value || '';
        const advPurchaseDateFrom = document.getElementById('advSearch_purchaseDateFrom')?.value || '';
        const advPurchaseDateTo = document.getElementById('advSearch_purchaseDateTo')?.value || '';

        const allRows = document.querySelectorAll('#assetsTable tr');
        const visibleColumns = getVisibleColumns();

        let visibleRowCount = 0;

        allRows.forEach(row => {
            let matches = true;

            // Simple search - checks visible columns
            if (searchQuery) {
                let foundInVisibleColumn = false;

                const tagCell = row.querySelector('td:nth-child(2)');
                if (tagCell && tagCell.textContent.toLowerCase().includes(searchQuery)) {
                    foundInVisibleColumn = true;
                }

                if (!foundInVisibleColumn) {
                    visibleColumns.forEach(colName => {
                        const cell = row.querySelector(`td[data-column="${colName}"]`);
                        if (cell && cell.textContent.toLowerCase().includes(searchQuery)) {
                            foundInVisibleColumn = true;
                        }
                    });
                }

                if (!foundInVisibleColumn) {
                    matches = false;
                }
            }

            // Simple status filter from top bar
            if (matches && statusFilter) {
                const cell = row.querySelector('td[data-column="status"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(statusFilter.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            // Simple date range filter from top bar (using date_updated)
            if (matches && (startDate || endDate)) {
                const dateCell = row.querySelector('td[data-column="date_updated"]');
                if (dateCell) {
                    const dateText = dateCell.textContent.trim();
                    if (dateText && dateText !== '-') {
                        const cellDate = new Date(dateText);
                        if (startDate) {
                            const start = new Date(startDate);
                            if (cellDate < start) matches = false;
                        }
                        if (endDate && matches) {
                            const end = new Date(endDate);
                            end.setHours(23, 59, 59, 999);
                            if (cellDate > end) matches = false;
                        }
                    }
                }
            }

            // Advanced search filters
            if (matches && advTag) {
                const cell = row.querySelector('td:nth-child(2)');
                if (!cell || !cell.textContent.toLowerCase().includes(advTag)) matches = false;
            }

            if (matches && advSerial) {
                const cell = row.querySelector('td[data-column="serial_number"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advSerial)) matches = false;
            }

            if (matches && advModel) {
                const cell = row.querySelector('td[data-column="model"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advModel)) matches = false;
            }

            if (matches && advMfg) {
                const cell = row.querySelector('td[data-column="manufacturer"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advMfg)) matches = false;
            }

            if (matches && advCat) {
                const cell = row.querySelector('td[data-column="category"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advCat)) matches = false;
            }

            if (matches && advStatus) {
                const cell = row.querySelector('td[data-column="status"]');
                if (cell) {
                    const cellText = cell.textContent.toLowerCase();
                    if (!cellText.includes(advStatus.toLowerCase())) matches = false;
                } else {
                    matches = false;
                }
            }

            if (matches && advSender) {
                const cell = row.querySelector('td[data-column="sender"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advSender)) matches = false;
            }

            if (matches && advRecipient) {
                const cell = row.querySelector('td[data-column="recipient"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advRecipient)) matches = false;
            }

            if (matches && advSupplier) {
                const cell = row.querySelector('td[data-column="supplier"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advSupplier)) matches = false;
            }

            if (matches && advBox) {
                const cell = row.querySelector('td[data-column="box_number"]');
                if (!cell || !cell.textContent.toLowerCase().includes(advBox)) matches = false;
            }

            if (matches && advDepartment) {
                const cell = row.querySelector('td[data-column="department_id"]');
                if (!cell || !cell.textContent.includes(advDepartment)) matches = false;
            }

            if (matches && advLocation) {
                const cell = row.querySelector('td[data-column="location_id"]');
                if (!cell || !cell.textContent.includes(advLocation)) matches = false;
            }

            if (matches && advPurchaseDateFrom) {
                const cell = row.querySelector('td[data-column="purchase_date"]');
                if (cell) {
                    const dateText = cell.textContent.trim();
                    if (dateText && dateText !== '-') {
                        const cellDate = new Date(dateText);
                        const startDate = new Date(advPurchaseDateFrom);
                        if (cellDate < startDate) matches = false;
                    }
                }
            }

            if (matches && advPurchaseDateTo) {
                const cell = row.querySelector('td[data-column="purchase_date"]');
                if (cell) {
                    const dateText = cell.textContent.trim();
                    if (dateText && dateText !== '-') {
                        const cellDate = new Date(dateText);
                        const endDate = new Date(advPurchaseDateTo);
                        endDate.setHours(23, 59, 59, 999);
                        if (cellDate > endDate) matches = false;
                    }
                }
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
            noResultsRow.innerHTML = '<td colspan="50" class="text-center text-muted p-4">No assets match your search criteria</td>';
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
});</script>
    </body>
</html>
