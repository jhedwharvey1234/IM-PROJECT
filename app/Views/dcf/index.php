<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCF Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
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
        .action-btn-edit { background-color: #ffc107; color: #000; }
        .action-btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'DCF Management']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">DCF Management</span>
        </div>

        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <div class="d-flex gap-1">
                <a href="<?= site_url('dcf/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
                    <i class="bi bi-plus-circle"></i>
                </a>

                <button class="btn btn-danger btn-sm" id="deleteSelectedBtn" disabled title="Delete" aria-label="Delete" data-bs-toggle="tooltip">
                    <i class="bi bi-trash"></i>
                </button>

                <button class="btn btn-info btn-sm" id="refreshBtn" title="Refresh" aria-label="Refresh" data-bs-toggle="tooltip">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" title="Columns" aria-label="Columns">
                        <i class="bi bi-sliders"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg" id="columnsList" aria-labelledby="columnsDropdown" style="max-height: 400px; overflow-y: auto; min-width: 250px;">
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="dcf_id" checked> ID</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="name" checked> Name</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="description" checked> Description</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="status" checked> Status</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_at"> Created</label></li>
                    </ul>
                </div>

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

                <button class="btn btn-warning btn-sm" id="printBtn" title="Print" aria-label="Print" data-bs-toggle="tooltip">
                    <i class="bi bi-printer"></i>
                </button>

                <button class="btn btn-primary btn-sm" id="fullscreenBtn" title="Fullscreen" aria-label="Fullscreen" data-bs-toggle="tooltip">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <button class="btn btn-secondary btn-sm" id="advancedSearchBtn" title="Advanced Search" aria-label="Advanced Search" data-bs-toggle="tooltip">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <div class="vr" style="height: 32px;"></div>

            <div style="flex: 1; min-width: 200px;">
                <input type="text" id="dcf_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <div style="width: 150px;">
                <select id="dcf_status" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn" title="Clear filters" data-bs-toggle="tooltip" style="height: 32px; width: 32px; padding: 0;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

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
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_name" placeholder="Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm advSearchField" id="advSearch_status">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_description" placeholder="Description">
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

        <div class="pagination-info">
            <span id="paginationInfo">Showing 1 to 20 of <?= count($dcfs) ?> rows</span>
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
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all DCFs">
                    </th>
                    <th data-column="dcf_id">ID</th>
                    <th data-column="name">Name</th>
                    <th data-column="description">Description</th>
                    <th data-column="status">Status</th>
                    <th data-column="created_at" style="display: none;">Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dcfTable">
                <?php if (!empty($dcfs)): ?>
                    <?php foreach ($dcfs as $dcf): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input dcfCheckbox" value="<?= $dcf['id'] ?>" title="Select this DCF">
                            </td>
                            <td data-column="dcf_id"><?= $dcf['id'] ?></td>
                            <td data-column="name"><?= esc($dcf['name']) ?></td>
                            <td data-column="description"><?= esc($dcf['description'] ?? 'N/A') ?></td>
                            <td data-column="status">
                                <?php if (!empty($dcf['is_active'])): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td data-column="created_at" style="display: none;"><?= esc($dcf['created_at'] ?? 'N/A') ?></td>
                            <td>
                                <a href="<?= site_url('dcf/edit/' . $dcf['id']) ?>" class="action-btn action-btn-edit" title="Edit DCF" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                                <a href="<?= site_url('dcf/delete/' . $dcf['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure?')" title="Delete DCF" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No DCF records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

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

        let currentPage = 1;
        let rowsPerPage = 20;
        let totalRows = 0;
        let filteredRows = [];

        const selectAllCheckbox = document.getElementById('selectAll');
        const dcfCheckboxes = document.querySelectorAll('.dcfCheckbox');
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
        const searchInput = document.getElementById('dcf_search');
        const tableBody = document.getElementById('dcfTable');
        const rowsPerPageSelect = document.getElementById('rowsPerPageSelect');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const pageNumbersContainer = document.getElementById('pageNumbers');
        const paginationInfo = document.getElementById('paginationInfo');

        function initializePagination() {
            const allRows = document.querySelectorAll('#dcfTable tr');
            totalRows = allRows.length;
            filteredRows = Array.from(allRows);
            currentPage = 1;
            updatePagination();
        }

        if (rowsPerPageSelect) {
            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                updatePagination();
            });
        }

        function updatePagination() {
            const allRows = document.querySelectorAll('#dcfTable tr');
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
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startRow = (currentPage - 1) * rowsPerPage + 1;
            const endRow = Math.min(currentPage * rowsPerPage, totalRows);
            paginationInfo.textContent = `Showing ${startRow} to ${endRow} of ${totalRows} rows`;

            filteredRows.forEach(row => row.style.display = 'none');
            for (let i = (currentPage - 1) * rowsPerPage; i < currentPage * rowsPerPage && i < totalRows; i++) {
                filteredRows[i].style.display = '';
            }

            dcfCheckboxes.forEach(cb => cb.checked = false);
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            updateDeleteButton();

            generatePageButtons(totalPages);
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;
        }

        function generatePageButtons(totalPages) {
            pageNumbersContainer.innerHTML = '';
            const maxButtons = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxButtons - 1);

            if (endPage - startPage < maxButtons - 1) {
                startPage = Math.max(1, endPage - maxButtons + 1);
            }

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

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                if (i === currentPage) btn.classList.add('active');
                btn.addEventListener('click', () => {
                    currentPage = i;
                    updatePagination();
                });
                pageNumbersContainer.appendChild(btn);
            }

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

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                dcfCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });
        }

        dcfCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(dcfCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(dcfCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
                updateDeleteButton();
            });
        });

        function updateDeleteButton() {
            const selectedCount = Array.from(dcfCheckboxes).filter(cb => cb.checked).length;
            deleteSelectedBtn.disabled = selectedCount === 0;
            deleteSelectedBtn.title = selectedCount > 0 ? `Delete (${selectedCount})` : 'Delete';
        }

        deleteSelectedBtn.addEventListener('click', function() {
            const selected = Array.from(dcfCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Please select at least one DCF');
                return;
            }
            if (!confirm(`Delete ${selected.length} DCF(s)? This cannot be undone.`)) {
                return;
            }
            Promise.all(selected.map(id => fetch('<?= site_url('dcf/delete') ?>/' + id)))
                .then(() => location.reload())
                .catch(() => alert('Error deleting DCFs'));
        });

        refreshBtn.addEventListener('click', function() {
            location.reload();
        });

        advancedSearchBtn.addEventListener('click', function() {
            const advModal = new bootstrap.Modal(document.getElementById('advancedSearchModal'));
            advModal.show();
        });

        const statusSelect = document.getElementById('dcf_status');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                filterTableRows();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                statusSelect.value = '';
                if (typeof advSearchFields !== 'undefined') {
                    advSearchFields.forEach(field => field.value = '');
                }
                filterTableRows();
            });
        }

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

        printBtn.addEventListener('click', function() {
            const printWindow = window.open('', '', 'width=1200,height=600');
            const table = document.querySelector('table').outerHTML;
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>DCF Management - Print</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        table { font-size: 11px; }
                        .btn { display: none; }
                    </style>
                </head>
                <body>
                    <h2>DCF Management Report</h2>
                    <p>Generated: ${new Date().toLocaleString()}</p>
                    ${table}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        });

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

        function exportTableToCSV(filename, selectedOnly = false) {
            const rows = selectedOnly ?
                Array.from(document.querySelectorAll('#dcfTable tr')).filter(tr =>
                    tr.querySelector('input[type="checkbox"]')?.checked
                ) :
                document.querySelectorAll('#dcfTable tr');

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
            exportTableToCSV('dcf_' + new Date().toISOString().split('T')[0] + '.csv');
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
            downloadFile(excelContent, 'dcf_' + new Date().toISOString().split('T')[0] + '.xls', 'application/vnd.ms-excel');
        });

        exportSelectedBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const selected = Array.from(dcfCheckboxes).filter(cb => cb.checked).length;
            if (selected === 0) {
                alert('Please select at least one DCF to export');
                return;
            }
            exportTableToCSV('dcf_selected_' + new Date().toISOString().split('T')[0] + '.csv', true);
        });

        exportPdfBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Export to PDF functionality would be implemented here');
        });

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
            const statusFilter = document.getElementById('dcf_status').value || '';

            const advName = (document.getElementById('advSearch_name')?.value || '').toLowerCase();
            const advStatus = document.getElementById('advSearch_status')?.value || '';
            const advDescription = (document.getElementById('advSearch_description')?.value || '').toLowerCase();

            const allRows = document.querySelectorAll('#dcfTable tr');
            const visibleColumns = getVisibleColumns();

            let visibleRowCount = 0;

            allRows.forEach(row => {
                let matches = true;

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

                if (matches && statusFilter) {
                    const cell = row.querySelector('td[data-column="status"]');
                    if (cell) {
                        const cellText = cell.textContent.toLowerCase();
                        if (!cellText.includes(statusFilter.toLowerCase())) matches = false;
                    } else {
                        matches = false;
                    }
                }

                if (matches && advName) {
                    const cell = row.querySelector('td[data-column="name"]');
                    if (!cell || !cell.textContent.toLowerCase().includes(advName)) matches = false;
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

                if (matches && advDescription) {
                    const cell = row.querySelector('td[data-column="description"]');
                    if (!cell || !cell.textContent.toLowerCase().includes(advDescription)) matches = false;
                }

                row.style.display = matches ? '' : 'none';
                if (matches) visibleRowCount++;
            });

            if (visibleRowCount === 0) {
                if (tableBody.querySelector('.no-results')) {
                    tableBody.querySelector('.no-results').remove();
                }
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results';
                noResultsRow.innerHTML = '<td colspan="50" class="text-center text-muted p-4">No DCFs match your search criteria</td>';
                tableBody.appendChild(noResultsRow);
            } else {
                if (tableBody.querySelector('.no-results')) {
                    tableBody.querySelector('.no-results').remove();
                }
            }

            currentPage = 1;
            updatePagination();
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                filterTableRows();
            });
        }

        initializePagination();
    });
    </script>
</body>
</html>
