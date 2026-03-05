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
<?php $isReadonlyUser = strtolower(trim((string) session()->get('usertype'))) === 'readonly'; ?>
<body class="<?= $isReadonlyUser ? 'readonly-user' : '' ?>">
    <?= view('partials/header', ['title' => 'DCF Management']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">DCF Management</span>
        </div>

        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <div class="d-flex gap-1">
                <a href="<?= site_url('dcf/create') ?>" class="btn btn-success btn-sm write-action" title="Create" aria-label="Create" data-bs-toggle="tooltip"><i class="bi bi-plus-circle"></i></a>
                <button class="btn btn-danger btn-sm write-action" id="deleteSelectedBtn" disabled title="Delete" aria-label="Delete" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></button>
                <button class="btn btn-info btn-sm" id="refreshBtn" title="Refresh" aria-label="Refresh" data-bs-toggle="tooltip"><i class="bi bi-arrow-clockwise"></i></button>

                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" title="Columns" aria-label="Columns"><i class="bi bi-sliders"></i></button>
                    <ul class="dropdown-menu dropdown-menu-lg" id="columnsList" aria-labelledby="columnsDropdown" style="max-height: 400px; overflow-y: auto; min-width: 250px;">
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="dcf_id" checked> ID</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="title" checked> Title</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="description" checked> Description</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="due_date" checked> Due Date</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="department_name" checked> Department</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_at"> Created</label></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" title="Export" aria-label="Export"><i class="bi bi-box-arrow-down"></i></button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="#" id="exportCsv">Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#" id="exportExcel">Export as Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="exportPdf">Export as PDF</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="exportSelected">Export Selected</a></li>
                    </ul>
                </div>

                <button class="btn btn-warning btn-sm" id="printBtn" title="Print" aria-label="Print" data-bs-toggle="tooltip"><i class="bi bi-printer"></i></button>
                <button class="btn btn-primary btn-sm" id="fullscreenBtn" title="Fullscreen" aria-label="Fullscreen" data-bs-toggle="tooltip"><i class="bi bi-arrows-fullscreen"></i></button>
                <button class="btn btn-secondary btn-sm" id="advancedSearchBtn" title="Advanced Search" aria-label="Advanced Search" data-bs-toggle="tooltip"><i class="bi bi-search"></i></button>
            </div>

            <div class="vr" style="height: 32px;"></div>
            <div style="flex: 1; min-width: 200px;"><input type="text" id="dcf_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;"></div>

            <div style="width: 180px;">
                <select id="dcf_department" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= esc($department['department_name']) ?>"><?= esc($department['department_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 180px;"><input type="date" id="dcf_due_date" class="form-control form-control-sm" style="height: 32px;" title="Filter by due date"></div>
            <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn" title="Clear filters" data-bs-toggle="tooltip" style="height: 32px; width: 32px; padding: 0;"><i class="bi bi-x-lg"></i></button>
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
                            <div class="col-md-6"><label class="form-label">Title</label><input type="text" class="form-control form-control-sm advSearchField" id="advSearch_title" placeholder="Title"></div>
                            <div class="col-md-6"><label class="form-label">Department</label><input type="text" class="form-control form-control-sm advSearchField" id="advSearch_department" placeholder="Department"></div>
                            <div class="col-md-6"><label class="form-label">Due Date</label><input type="date" class="form-control form-control-sm advSearchField" id="advSearch_dueDate"></div>
                            <div class="col-12"><label class="form-label">Description</label><input type="text" class="form-control form-control-sm advSearchField" id="advSearch_description" placeholder="Description"></div>
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
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="pagination-info">
            <span id="paginationInfo">Showing 1 to 20 of <?= count($dcfs) ?> rows</span>
            <div class="rows-per-page">
                <label for="rowsPerPageSelect">Rows per page:</label>
                <select id="rowsPerPageSelect"><option value="10">10</option><option value="20" selected>20</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 50px;" class="write-action"><input type="checkbox" id="selectAll" class="form-check-input" title="Select all DCFs"></th>
                    <th data-column="dcf_id">ID</th>
                    <th data-column="title">Title</th>
                    <th data-column="description">Description</th>
                    <th data-column="due_date">Due Date</th>
                    <th data-column="department_name">Department</th>
                    <th data-column="created_at" style="display: none;">Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dcfTable">
                <?php if (!empty($dcfs)): ?>
                    <?php foreach ($dcfs as $dcf): ?>
                        <tr>
                            <td class="write-action"><input type="checkbox" class="form-check-input dcfCheckbox" value="<?= $dcf['id'] ?>" title="Select this DCF"></td>
                            <td data-column="dcf_id"><?= $dcf['id'] ?></td>
                            <td data-column="title"><?= esc($dcf['title'] ?? 'N/A') ?></td>
                            <td data-column="description"><?= esc($dcf['description'] ?? 'N/A') ?></td>
                            <td data-column="due_date"><?= esc($dcf['due_date'] ?? 'N/A') ?></td>
                            <td data-column="department_name"><?= esc($dcf['department_name'] ?? 'N/A') ?></td>
                            <td data-column="created_at" style="display: none;"><?= esc($dcf['created_at'] ?? 'N/A') ?></td>
                            <td>
                                <a href="<?= site_url('dcf/details/' . $dcf['id']) ?>" class="action-btn" style="background-color: #17a2b8;" title="View Details" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                <a href="<?= site_url('dcf/edit/' . $dcf['id']) ?>" class="action-btn action-btn-edit write-action" title="Edit DCF" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                                <a href="<?= site_url('dcf/delete/' . $dcf['id']) ?>" class="action-btn action-btn-delete write-action" onclick="return confirm('Are you sure?')" title="Delete DCF" data-bs-toggle="tooltip"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted">No DCF records found.</td></tr>
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

    <style>
        .readonly-user .write-action { display: none !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

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
            const departmentSelect = document.getElementById('dcf_department');
            const dueDateInput = document.getElementById('dcf_due_date');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');

            function updateDeleteButton() {
                const selectedCount = Array.from(dcfCheckboxes).filter(cb => cb.checked).length;
                deleteSelectedBtn.disabled = selectedCount === 0;
                deleteSelectedBtn.title = selectedCount > 0 ? `Delete (${selectedCount})` : 'Delete';
            }

            function generatePageButtons(totalPages) {
                pageNumbersContainer.innerHTML = '';
                const maxButtons = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(totalPages, startPage + maxButtons - 1);
                if (endPage - startPage < maxButtons - 1) startPage = Math.max(1, endPage - maxButtons + 1);

                for (let i = startPage; i <= endPage; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    if (i === currentPage) btn.classList.add('active');
                    btn.addEventListener('click', () => { currentPage = i; updatePagination(); });
                    pageNumbersContainer.appendChild(btn);
                }
            }

            function updatePagination() {
                const allRows = document.querySelectorAll('#dcfTable tr');
                filteredRows = Array.from(allRows).filter(row => !row.classList.contains('no-results') && row.dataset.match !== '0');
                totalRows = filteredRows.length;

                if (totalRows === 0) {
                    paginationInfo.textContent = 'No results found';
                    prevPageBtn.disabled = true;
                    nextPageBtn.disabled = true;
                    pageNumbersContainer.innerHTML = '';
                    return;
                }

                const totalPages = Math.ceil(totalRows / rowsPerPage);
                currentPage = Math.min(Math.max(currentPage, 1), totalPages);

                const startRow = (currentPage - 1) * rowsPerPage + 1;
                const endRow = Math.min(currentPage * rowsPerPage, totalRows);
                paginationInfo.textContent = `Showing ${startRow} to ${endRow} of ${totalRows} rows`;

                document.querySelectorAll('#dcfTable tr').forEach(row => { if (!row.classList.contains('no-results')) row.style.display = 'none'; });
                for (let i = (currentPage - 1) * rowsPerPage; i < currentPage * rowsPerPage && i < totalRows; i++) filteredRows[i].style.display = '';

                generatePageButtons(totalPages);
                prevPageBtn.disabled = currentPage === 1;
                nextPageBtn.disabled = currentPage === totalPages;
                updateDeleteButton();
            }

            function getVisibleColumns() {
                const visible = [];
                columnToggles.forEach(toggle => { if (toggle.checked) visible.push(toggle.value); });
                return visible;
            }

            function filterTableRows() {
                const searchQuery = (searchInput.value || '').toLowerCase();
                const departmentFilter = (departmentSelect.value || '').toLowerCase();
                const dueDateFilter = dueDateInput.value || '';
                const advTitle = (document.getElementById('advSearch_title').value || '').toLowerCase();
                const advDepartment = (document.getElementById('advSearch_department').value || '').toLowerCase();
                const advDueDate = document.getElementById('advSearch_dueDate').value || '';
                const advDescription = (document.getElementById('advSearch_description').value || '').toLowerCase();
                const visibleColumns = getVisibleColumns();

                let visibleRowCount = 0;
                document.querySelectorAll('#dcfTable tr').forEach(row => {
                    if (row.classList.contains('no-results')) return;
                    let matches = true;

                    if (searchQuery) {
                        let found = false;
                        visibleColumns.forEach(col => {
                            const cell = row.querySelector(`td[data-column="${col}"]`);
                            if (cell && cell.textContent.toLowerCase().includes(searchQuery)) found = true;
                        });
                        if (!found) matches = false;
                    }

                    if (matches && departmentFilter) {
                        const cell = row.querySelector('td[data-column="department_name"]');
                        if (!cell || !cell.textContent.toLowerCase().includes(departmentFilter)) matches = false;
                    }

                    if (matches && dueDateFilter) {
                        const cell = row.querySelector('td[data-column="due_date"]');
                        if (!cell || !cell.textContent.includes(dueDateFilter)) matches = false;
                    }

                    if (matches && advTitle) {
                        const cell = row.querySelector('td[data-column="title"]');
                        if (!cell || !cell.textContent.toLowerCase().includes(advTitle)) matches = false;
                    }

                    if (matches && advDepartment) {
                        const cell = row.querySelector('td[data-column="department_name"]');
                        if (!cell || !cell.textContent.toLowerCase().includes(advDepartment)) matches = false;
                    }

                    if (matches && advDueDate) {
                        const cell = row.querySelector('td[data-column="due_date"]');
                        if (!cell || !cell.textContent.includes(advDueDate)) matches = false;
                    }

                    if (matches && advDescription) {
                        const cell = row.querySelector('td[data-column="description"]');
                        if (!cell || !cell.textContent.toLowerCase().includes(advDescription)) matches = false;
                    }

                    row.dataset.match = matches ? '1' : '0';
                    if (matches) visibleRowCount++;
                });

                const existingNoResult = tableBody.querySelector('.no-results');
                if (existingNoResult) existingNoResult.remove();
                if (visibleRowCount === 0) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results';
                    noResultsRow.innerHTML = '<td colspan="50" class="text-center text-muted p-4">No DCFs match your search criteria</td>';
                    tableBody.appendChild(noResultsRow);
                }

                currentPage = 1;
                updatePagination();
            }

            rowsPerPageSelect.addEventListener('change', function () { rowsPerPage = parseInt(this.value, 10); currentPage = 1; updatePagination(); });
            prevPageBtn.addEventListener('click', function () { if (currentPage > 1) { currentPage--; updatePagination(); } });
            nextPageBtn.addEventListener('click', function () { currentPage++; updatePagination(); });

            selectAllCheckbox.addEventListener('change', function () { dcfCheckboxes.forEach(cb => cb.checked = this.checked); updateDeleteButton(); });
            dcfCheckboxes.forEach(cb => cb.addEventListener('change', updateDeleteButton));

            deleteSelectedBtn.addEventListener('click', function () {
                const selected = Array.from(dcfCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                if (!selected.length) return;
                if (!confirm(`Delete ${selected.length} DCF(s)? This cannot be undone.`)) return;
                Promise.all(selected.map(id => fetch('<?= site_url('dcf/delete') ?>/' + id))).then(() => location.reload()).catch(() => alert('Error deleting DCFs'));
            });

            refreshBtn.addEventListener('click', () => location.reload());
            advancedSearchBtn.addEventListener('click', () => new bootstrap.Modal(document.getElementById('advancedSearchModal')).show());
            document.getElementById('applyAdvancedSearch').addEventListener('click', function () { filterTableRows(); bootstrap.Modal.getInstance(document.getElementById('advancedSearchModal')).hide(); });
            document.getElementById('clearAdvancedSearch').addEventListener('click', function () {
                document.querySelectorAll('.advSearchField').forEach(field => field.value = '');
                filterTableRows();
            });

            searchInput.addEventListener('keyup', filterTableRows);
            departmentSelect.addEventListener('change', filterTableRows);
            dueDateInput.addEventListener('change', filterTableRows);
            clearFiltersBtn.addEventListener('click', function () {
                searchInput.value = '';
                departmentSelect.value = '';
                dueDateInput.value = '';
                document.querySelectorAll('.advSearchField').forEach(field => field.value = '');
                filterTableRows();
            });

            columnToggles.forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const headers = document.querySelectorAll(`th[data-column="${this.value}"]`);
                    const cells = document.querySelectorAll(`td[data-column="${this.value}"]`);
                    headers.forEach(header => header.style.display = this.checked ? '' : 'none');
                    cells.forEach(cell => cell.style.display = this.checked ? '' : 'none');
                });
            });

            function downloadFile(content, filename, type) {
                const blob = new Blob([content], { type });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                a.click();
                window.URL.revokeObjectURL(url);
            }

            function exportTableToCSV(filename, selectedOnly = false) {
                const rows = selectedOnly ? Array.from(document.querySelectorAll('#dcfTable tr')).filter(tr => tr.querySelector('input[type="checkbox"]')?.checked) : document.querySelectorAll('#dcfTable tr');
                let csv = [];
                const headers = document.querySelectorAll('table th');
                csv.push(Array.from(headers).map(h => h.textContent.trim().replace(/"/g, '""')).join(','));
                rows.forEach(tr => {
                    if (tr.classList.contains('no-results')) return;
                    const cells = tr.querySelectorAll('td');
                    csv.push(Array.from(cells).map(cell => `"${cell.textContent.trim().replace(/"/g, '""')}"`).join(','));
                });
                downloadFile(csv.join('\n'), filename, 'text/csv');
            }

            exportCsvBtn.addEventListener('click', e => { e.preventDefault(); exportTableToCSV('dcf_' + new Date().toISOString().split('T')[0] + '.csv'); });
            exportExcelBtn.addEventListener('click', e => {
                e.preventDefault();
                const html = `<table>${document.querySelector('table').innerHTML}</table>`;
                const excelContent = `<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="UTF-8"></head><body>${html}</body></html>`;
                downloadFile(excelContent, 'dcf_' + new Date().toISOString().split('T')[0] + '.xls', 'application/vnd.ms-excel');
            });
            exportSelectedBtn.addEventListener('click', e => {
                e.preventDefault();
                const selected = Array.from(dcfCheckboxes).filter(cb => cb.checked).length;
                if (!selected) return alert('Please select at least one DCF to export');
                exportTableToCSV('dcf_selected_' + new Date().toISOString().split('T')[0] + '.csv', true);
            });
            exportPdfBtn.addEventListener('click', e => { e.preventDefault(); alert('Export to PDF functionality would be implemented here'); });

            printBtn.addEventListener('click', function () {
                const printWindow = window.open('', '', 'width=1200,height=600');
                const table = document.querySelector('table').outerHTML;
                printWindow.document.write(`<!DOCTYPE html><html><head><title>DCF Management - Print</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{padding:20px;}table{font-size:11px;}.btn{display:none;}</style></head><body><h2>DCF Management Report</h2><p>Generated: ${new Date().toLocaleString()}</p>${table}</body></html>`);
                printWindow.document.close();
                printWindow.print();
            });

            const fullscreenIcon = fullscreenBtn.querySelector('i');

            function isFullscreenActive() {
                return !!(document.fullscreenElement || document.webkitFullscreenElement);
            }

            function updateFullscreenButtonState() {
                const active = isFullscreenActive();
                fullscreenBtn.classList.toggle('btn-primary', !active);
                fullscreenBtn.classList.toggle('btn-danger', active);
                fullscreenBtn.setAttribute('title', active ? 'Exit Fullscreen' : 'Fullscreen');
                fullscreenBtn.setAttribute('aria-label', active ? 'Exit Fullscreen' : 'Fullscreen');
                if (fullscreenIcon) {
                    fullscreenIcon.className = active ? 'bi bi-fullscreen-exit' : 'bi bi-arrows-fullscreen';
                }
            }

            fullscreenBtn.addEventListener('click', function () {
                const mainContent = document.querySelector('.main-content');
                if (!mainContent) return;

                if (!isFullscreenActive()) {
                    if (mainContent.requestFullscreen) {
                        mainContent.requestFullscreen().catch(err => alert('Could not enter fullscreen: ' + err.message));
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

            document.addEventListener('fullscreenchange', updateFullscreenButtonState);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButtonState);
            updateFullscreenButtonState();

            document.querySelectorAll('#dcfTable tr').forEach(row => { if (!row.classList.contains('no-results')) row.dataset.match = '1'; });
            updatePagination();
        });
    </script>
</body>
</html>
