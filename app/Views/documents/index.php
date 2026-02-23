<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; font-size: 14px; cursor: pointer; text-decoration: none; border-radius: 4px; margin: 0 2px; }
        .action-btn:hover { opacity: 0.85; color: white; transform: translateY(-1px); }
        .action-btn-details { background-color: #17a2b8; }
        .action-btn-edit { background-color: #ffc107; color: #000; }
        .action-btn-delete { background-color: #dc3545; }
        .pagination-info { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; margin-bottom: 15px; font-size: 14px; }
        .pagination-controls { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; margin-top: 15px; padding: 15px 0; border-top: 1px solid #dee2e6; }
        .pagination-controls button { padding: 5px 10px; border: 1px solid #dee2e6; background: white; color: #0d6efd; cursor: pointer; border-radius: 3px; font-size: 13px; }
        .pagination-controls button:hover { background-color: #e9ecef; }
        .pagination-controls button:disabled { opacity: 0.5; cursor: not-allowed; }
        .pagination-controls button.active { background-color: #0d6efd; color: white; }
        .rows-per-page { display: flex; align-items: center; gap: 8px; }
        .rows-per-page select { padding: 4px 6px; border: 1px solid #dee2e6; border-radius: 3px; font-size: 13px; }
        .adv-search-results { max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; background: #fff; }
        .adv-result-item { padding: 10px 12px; border-bottom: 1px solid #f1f3f5; }
        .adv-result-item:last-child { border-bottom: none; }
        .adv-result-link { display: block; text-decoration: none; color: inherit; }
        .adv-result-link:hover { background-color: #f8f9fa; }
        .adv-result-title { font-weight: 600; color: #212529; margin-bottom: 2px; }
        .adv-result-subject { font-size: 13px; color: #6c757d; margin-bottom: 4px; }
        .adv-result-snippet { font-size: 13px; color: #495057; }
        .adv-result-empty { padding: 14px; color: #6c757d; font-size: 13px; }
        .adv-highlight { background-color: #fff3cd; color: #856404; padding: 0 2px; border-radius: 2px; }
    </style>
</head>
<body>
    <?php $isReadOnly = !empty($isReadOnly); ?>
    <?= view('partials/header', ['title' => 'Document Management']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Document Management</span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <div class="d-flex gap-1">
                <?php if (!$isReadOnly): ?>
                    <a href="<?= site_url('documents/create') ?>" class="btn btn-success btn-sm" title="Create" aria-label="Create" data-bs-toggle="tooltip">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                <?php endif; ?>

                <?php if (!$isReadOnly): ?>
                    <button class="btn btn-danger btn-sm" id="deleteSelectedBtn" disabled title="Delete" aria-label="Delete" data-bs-toggle="tooltip">
                        <i class="bi bi-trash"></i>
                    </button>
                <?php endif; ?>

                <button class="btn btn-info btn-sm" id="refreshBtn" title="Refresh" aria-label="Refresh" data-bs-toggle="tooltip">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="columnsDropdown" data-bs-toggle="dropdown" title="Columns" aria-label="Columns">
                        <i class="bi bi-sliders"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg" id="columnsList" aria-labelledby="columnsDropdown" style="max-height: 400px; overflow-y: auto; min-width: 250px;">
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="doc_id" checked> ID</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="title" checked> Title</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="subject" checked> Subject</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="document_category_name" checked> Category</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="document_type_name" checked> Type</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="description" checked> Description</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="details" checked> Details</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_by_name" checked> Created By</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="created_at" checked> Created</label></li>
                        <li><label class="dropdown-item"><input type="checkbox" class="columnToggle" value="updated_at"> Updated</label></li>
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
                <input type="text" id="documents_search" class="form-control form-control-sm" placeholder="Search in visible columns..." style="height: 32px;">
            </div>

            <div style="width: 190px;">
                <select id="documents_subject" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Subjects</option>
                    <?php
                    $subjects = array_values(array_unique(array_filter(array_map(static fn($doc) => trim((string) ($doc['subject'] ?? '')), $documents ?? []))));
                    sort($subjects);
                    foreach ($subjects as $subject):
                    ?>
                        <option value="<?= esc($subject) ?>"><?= esc($subject) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 170px;">
                <select id="documents_creator" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Creators</option>
                    <?php
                    $creators = array_values(array_unique(array_filter(array_map(static fn($doc) => trim((string) ($doc['created_by_name'] ?? '')), $documents ?? []))));
                    sort($creators);
                    foreach ($creators as $creator):
                    ?>
                        <option value="<?= esc($creator) ?>"><?= esc($creator) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 190px;">
                <select id="documents_category" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Categories</option>
                    <?php
                    $categories = array_values(array_unique(array_filter(array_map(static fn($doc) => trim((string) ($doc['document_category_name'] ?? '')), $documents ?? []))));
                    sort($categories);
                    foreach ($categories as $category):
                    ?>
                        <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 170px;">
                <select id="documents_type" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Types</option>
                    <?php
                    $types = array_values(array_unique(array_filter(array_map(static fn($doc) => trim((string) ($doc['document_type_name'] ?? '')), $documents ?? []))));
                    sort($types);
                    foreach ($types as $type):
                    ?>
                        <option value="<?= esc($type) ?>"><?= esc($type) ?></option>
                    <?php endforeach; ?>
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
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_title" placeholder="Title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_subject" placeholder="Subject">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_description" placeholder="Description">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Details</label>
                                <input type="text" class="form-control form-control-sm advSearchField" id="advSearch_details" placeholder="Details">
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Search Results</h6>
                            <small class="text-muted" id="advSearchResultsCount">0 result(s)</small>
                        </div>
                        <div id="advSearchResults" class="adv-search-results">
                            <div class="adv-result-empty">Enter search filters and click Search to preview matching documents.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning btn-sm" id="clearAdvancedSearch">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="batchDeleteForm" action="<?= site_url('documents/batch-delete') ?>" method="post" class="d-none">
            <?= csrf_field() ?>
            <div id="batchDeleteInputs"></div>
        </form>

        <div class="pagination-info">
            <span id="paginationInfo">Showing 0 to 0 of 0 rows</span>
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

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="selectAll" class="form-check-input" title="Select all documents">
                    </th>
                    <th data-column="doc_id">ID</th>
                    <th data-column="title">Title</th>
                    <th data-column="subject">Subject</th>
                    <th data-column="document_category_name">Category</th>
                    <th data-column="document_type_name">Type</th>
                    <th data-column="description">Description</th>
                    <th data-column="details">Details</th>
                    <th data-column="created_by_name">Created By</th>
                    <th data-column="created_at">Created</th>
                    <th data-column="updated_at" style="display: none;">Updated</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody id="documentsTable">
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $document): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input documentCheckbox" value="<?= $document['id'] ?>" title="Select this document">
                            </td>
                            <td data-column="doc_id"><span class="badge bg-secondary">#<?= esc($document['id']) ?></span></td>
                            <td data-column="title"><?= esc($document['title']) ?></td>
                            <td data-column="subject"><?= esc($document['subject'] ?? 'N/A') ?></td>
                            <td data-column="document_category_name"><?= esc($document['document_category_name'] ?? 'N/A') ?></td>
                            <td data-column="document_type_name"><?= esc($document['document_type_name'] ?? 'N/A') ?></td>
                            <?php
                                $descriptionFull = trim((string) ($document['description'] ?? ''));
                                $descriptionDisplay = $descriptionFull !== '' ? (mb_strlen($descriptionFull) > 30 ? mb_substr($descriptionFull, 0, 30) . '...' : $descriptionFull) : 'N/A';
                                $detailsFull = trim((string) strip_tags((string) ($document['details'] ?? '')));
                                $detailsDisplay = $detailsFull !== '' ? (mb_strlen($detailsFull) > 30 ? mb_substr($detailsFull, 0, 30) . '...' : $detailsFull) : 'N/A';
                            ?>
                            <td data-column="description" data-full="<?= esc($descriptionFull !== '' ? $descriptionFull : 'N/A', 'attr') ?>" title="<?= esc($descriptionFull !== '' ? $descriptionFull : 'N/A', 'attr') ?>"><?= esc($descriptionDisplay) ?></td>
                            <td data-column="details" data-full="<?= esc($detailsFull !== '' ? $detailsFull : 'N/A', 'attr') ?>" title="<?= esc($detailsFull !== '' ? $detailsFull : 'N/A', 'attr') ?>"><?= esc($detailsDisplay) ?></td>
                            <td data-column="created_by_name"><?= esc($document['created_by_name'] ?? 'N/A') ?></td>
                            <td data-column="created_at"><?= !empty($document['created_at']) ? esc(date('M d, Y h:i A', strtotime($document['created_at']))) : 'N/A' ?></td>
                            <td data-column="updated_at" style="display: none;"><?= !empty($document['updated_at']) ? esc(date('M d, Y h:i A', strtotime($document['updated_at']))) : 'N/A' ?></td>
                            <td>
                                <a href="<?= site_url('documents/details/' . $document['id']) ?>" class="action-btn action-btn-details" title="View Details"><i class="bi bi-eye"></i></a>
                                <?php if (!$isReadOnly): ?>
                                    <a href="<?= site_url('documents/edit/' . $document['id']) ?>" class="action-btn action-btn-edit" title="Edit Document"><i class="bi bi-pencil-square"></i></a>
                                    <a href="<?= site_url('documents/delete/' . $document['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure you want to delete this document?')" title="Delete Document"><i class="bi bi-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">No documents found.</td>
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
        let filteredRows = [];

        const selectAllCheckbox = document.getElementById('selectAll');
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
        const searchInput = document.getElementById('documents_search');
        const subjectFilter = document.getElementById('documents_subject');
        const creatorFilter = document.getElementById('documents_creator');
        const categoryFilter = document.getElementById('documents_category');
        const typeFilter = document.getElementById('documents_type');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const tableBody = document.getElementById('documentsTable');
        const rowsPerPageSelect = document.getElementById('rowsPerPageSelect');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const pageNumbersContainer = document.getElementById('pageNumbers');
        const paginationInfo = document.getElementById('paginationInfo');

        const batchDeleteForm = document.getElementById('batchDeleteForm');
        const batchDeleteInputs = document.getElementById('batchDeleteInputs');

        const advancedSearchModalElement = document.getElementById('advancedSearchModal');
        const advancedSearchModal = new bootstrap.Modal(advancedSearchModalElement);
        const advancedResultsContainer = document.getElementById('advSearchResults');
        const advancedResultsCount = document.getElementById('advSearchResultsCount');

        function getAllRows() {
            return Array.from(tableBody.querySelectorAll('tr')).filter(row => !row.classList.contains('no-results-row'));
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function escapeRegExp(value) {
            return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function getAdvancedSearchFilters() {
            return {
                title: document.getElementById('advSearch_title')?.value.trim().toLowerCase() || '',
                subject: document.getElementById('advSearch_subject')?.value.trim().toLowerCase() || '',
                description: document.getElementById('advSearch_description')?.value.trim().toLowerCase() || '',
                details: document.getElementById('advSearch_details')?.value.trim().toLowerCase() || '',
            };
        }

        function getAdvancedSearchTerms(filters) {
            return Object.values(filters)
                .map(value => String(value || '').trim())
                .filter(value => value !== '');
        }

        function rowMatchesAdvancedFilters(row, filters) {
            const title = (row.querySelector('td[data-column="title"]')?.textContent || '').trim().toLowerCase();
            const subject = (row.querySelector('td[data-column="subject"]')?.textContent || '').trim().toLowerCase();
            const descriptionCell = row.querySelector('td[data-column="description"]');
            const detailsCell = row.querySelector('td[data-column="details"]');
            const description = ((descriptionCell?.dataset.full || descriptionCell?.textContent) || '').trim().toLowerCase();
            const details = ((detailsCell?.dataset.full || detailsCell?.textContent) || '').trim().toLowerCase();

            if (filters.title && !title.includes(filters.title)) {
                return false;
            }

            if (filters.subject && !subject.includes(filters.subject)) {
                return false;
            }

            if (filters.description && !description.includes(filters.description)) {
                return false;
            }

            if (filters.details && !details.includes(filters.details)) {
                return false;
            }

            return true;
        }

        function highlightText(value, terms) {
            const plainText = String(value || '');
            if (!plainText || !terms.length) {
                return escapeHtml(plainText);
            }

            const uniqueTerms = Array.from(new Set(terms.map(term => term.toLowerCase())));
            const pattern = uniqueTerms.map(escapeRegExp).join('|');

            if (!pattern) {
                return escapeHtml(plainText);
            }

            const regex = new RegExp(`(${pattern})`, 'gi');
            return escapeHtml(plainText).replace(regex, '<span class="adv-highlight">$1</span>');
        }

        function buildSnippet(value, terms, maxLength = 140) {
            const text = String(value || '').replace(/\s+/g, ' ').trim();
            if (!text) {
                return '';
            }

            if (!terms.length) {
                return text.length > maxLength ? `${text.slice(0, maxLength)}...` : text;
            }

            const loweredText = text.toLowerCase();
            let matchIndex = -1;
            let matchedTermLength = 0;

            terms.forEach(term => {
                const cleanTerm = term.toLowerCase();
                if (!cleanTerm) {
                    return;
                }
                const index = loweredText.indexOf(cleanTerm);
                if (index !== -1 && (matchIndex === -1 || index < matchIndex)) {
                    matchIndex = index;
                    matchedTermLength = cleanTerm.length;
                }
            });

            if (matchIndex === -1) {
                return text.length > maxLength ? `${text.slice(0, maxLength)}...` : text;
            }

            const context = Math.max(10, Math.floor((maxLength - matchedTermLength) / 2));
            const start = Math.max(0, matchIndex - context);
            const end = Math.min(text.length, matchIndex + matchedTermLength + context);

            let snippet = text.slice(start, end);
            if (start > 0) {
                snippet = `...${snippet}`;
            }
            if (end < text.length) {
                snippet = `${snippet}...`;
            }

            return snippet;
        }

        function renderAdvancedSearchResults() {
            if (!advancedResultsContainer || !advancedResultsCount) {
                return;
            }

            const filters = getAdvancedSearchFilters();
            const terms = getAdvancedSearchTerms(filters);
            const matchingRows = getAllRows().filter(row => rowMatchesAdvancedFilters(row, filters));

            advancedResultsCount.textContent = `${matchingRows.length} result(s)`;

            if (!terms.length) {
                advancedResultsContainer.innerHTML = '<div class="adv-result-empty">Enter search filters to preview matching documents.</div>';
                return;
            }

            if (!matchingRows.length) {
                advancedResultsContainer.innerHTML = '<div class="adv-result-empty">No matching documents found.</div>';
                return;
            }

            const html = matchingRows.map(row => {
                const title = row.querySelector('td[data-column="title"]')?.textContent.trim() || 'Untitled';
                const subject = row.querySelector('td[data-column="subject"]')?.textContent.trim() || 'N/A';
                const detailsHref = row.querySelector('a.action-btn-details')?.getAttribute('href') || '#';
                const descriptionCell = row.querySelector('td[data-column="description"]');
                const detailsCell = row.querySelector('td[data-column="details"]');
                const description = ((descriptionCell?.dataset.full || descriptionCell?.textContent) || '').trim();
                const details = ((detailsCell?.dataset.full || detailsCell?.textContent) || '').trim();

                const descriptionHasTerm = terms.some(term => description.toLowerCase().includes(term.toLowerCase()));
                const detailsHasTerm = terms.some(term => details.toLowerCase().includes(term.toLowerCase()));

                const preferredText = descriptionHasTerm ? description : (detailsHasTerm ? details : (description && description !== 'N/A' ? description : details));
                const snippet = buildSnippet(preferredText, terms) || 'No description/details available.';

                return `
                    <a href="${escapeHtml(detailsHref)}" class="adv-result-link">
                        <div class="adv-result-item">
                            <div class="adv-result-title">${highlightText(title, terms)}</div>
                            <div class="adv-result-subject">Subject: ${highlightText(subject, terms)}</div>
                            <div class="adv-result-snippet">${highlightText(snippet, terms)}</div>
                        </div>
                    </a>
                `;
            }).join('');

            advancedResultsContainer.innerHTML = html;
        }

        function updateDeleteButtonState() {
            if (!deleteSelectedBtn) {
                return;
            }
            const checkboxes = document.querySelectorAll('.documentCheckbox');
            const selectedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
            deleteSelectedBtn.disabled = selectedCount === 0;
        }

        function updateSelectAllState() {
            const visibleRows = filteredRows.filter(row => row.style.display !== 'none');
            const visibleCheckboxes = visibleRows.map(row => row.querySelector('.documentCheckbox')).filter(Boolean);

            if (!visibleCheckboxes.length) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
                return;
            }

            const checked = visibleCheckboxes.filter(cb => cb.checked).length;
            selectAllCheckbox.checked = checked === visibleCheckboxes.length;
            selectAllCheckbox.indeterminate = checked > 0 && checked < visibleCheckboxes.length;
        }

        function applyColumnVisibility() {
            columnToggles.forEach(toggle => {
                const columnName = toggle.value;
                const shouldShow = toggle.checked;

                document.querySelectorAll(`th[data-column="${columnName}"], td[data-column="${columnName}"]`).forEach(cell => {
                    cell.style.display = shouldShow ? '' : 'none';
                });
            });
        }

        function rowMatchesFilters(row) {
            const cells = row.querySelectorAll('td[data-column]');
            const searchValue = searchInput.value.trim().toLowerCase();
            const selectedSubject = subjectFilter.value.trim().toLowerCase();
            const selectedCreator = creatorFilter.value.trim().toLowerCase();
            const selectedCategory = categoryFilter.value.trim().toLowerCase();
            const selectedType = typeFilter.value.trim().toLowerCase();

            const visibleCells = Array.from(cells).filter(cell => cell.style.display !== 'none');
            const visibleText = visibleCells.map(cell => cell.textContent.toLowerCase()).join(' ');

            const subject = (row.querySelector('td[data-column="subject"]')?.textContent || '').trim().toLowerCase();
            const creator = (row.querySelector('td[data-column="created_by_name"]')?.textContent || '').trim().toLowerCase();
            const category = (row.querySelector('td[data-column="document_category_name"]')?.textContent || '').trim().toLowerCase();
            const type = (row.querySelector('td[data-column="document_type_name"]')?.textContent || '').trim().toLowerCase();

            if (searchValue && !visibleText.includes(searchValue)) {
                return false;
            }

            if (selectedSubject && subject !== selectedSubject) {
                return false;
            }

            if (selectedCreator && creator !== selectedCreator) {
                return false;
            }

            if (selectedCategory && category !== selectedCategory) {
                return false;
            }

            if (selectedType && type !== selectedType) {
                return false;
            }

            return true;
        }

        function applyFiltersAndPagination() {
            const allRows = getAllRows();
            filteredRows = allRows.filter(rowMatchesFilters);

            allRows.forEach(row => {
                row.style.display = 'none';
            });

            const totalFiltered = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalFiltered / rowsPerPage));

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, totalFiltered);
            const pageRows = filteredRows.slice(startIndex, endIndex);

            pageRows.forEach(row => {
                row.style.display = '';
            });

            const noResultsExisting = tableBody.querySelector('.no-results-row');
            if (noResultsExisting) {
                noResultsExisting.remove();
            }

            if (!totalFiltered) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = '<td colspan="12" class="text-center text-muted py-4">No documents found.</td>';
                tableBody.appendChild(noResultsRow);
            }

            const from = totalFiltered ? startIndex + 1 : 0;
            const to = totalFiltered ? endIndex : 0;
            paginationInfo.textContent = `Showing ${from} to ${to} of ${totalFiltered} rows`;

            renderPageNumbers(totalPages);
            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= totalPages;

            updateSelectAllState();
            updateDeleteButtonState();
        }

        function renderPageNumbers(totalPages) {
            pageNumbersContainer.innerHTML = '';

            const maxButtons = 7;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxButtons - 1);

            if (endPage - startPage + 1 < maxButtons) {
                startPage = Math.max(1, endPage - maxButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.className = i === currentPage ? 'active' : '';
                button.addEventListener('click', () => {
                    currentPage = i;
                    applyFiltersAndPagination();
                });
                pageNumbersContainer.appendChild(button);
            }
        }

        function getVisibleRowsForExport(selectedOnly = false) {
            const rows = getAllRows().filter(row => row.style.display !== 'none');
            if (!selectedOnly) {
                return rows;
            }
            return rows.filter(row => row.querySelector('.documentCheckbox')?.checked);
        }

        function extractRowData(row) {
            return {
                id: row.querySelector('td[data-column="doc_id"]')?.textContent.trim().replace('#', '') || '',
                title: row.querySelector('td[data-column="title"]')?.textContent.trim() || '',
                subject: row.querySelector('td[data-column="subject"]')?.textContent.trim() || '',
                category: row.querySelector('td[data-column="document_category_name"]')?.textContent.trim() || '',
                type: row.querySelector('td[data-column="document_type_name"]')?.textContent.trim() || '',
                description: row.querySelector('td[data-column="description"]')?.dataset.full || row.querySelector('td[data-column="description"]')?.textContent.trim() || '',
                details: row.querySelector('td[data-column="details"]')?.dataset.full || row.querySelector('td[data-column="details"]')?.textContent.trim() || '',
                createdBy: row.querySelector('td[data-column="created_by_name"]')?.textContent.trim() || '',
                createdAt: row.querySelector('td[data-column="created_at"]')?.textContent.trim() || '',
                updatedAt: row.querySelector('td[data-column="updated_at"]')?.textContent.trim() || '',
            };
        }

        function downloadFile(content, filename, mimeType) {
            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        function exportAsCsv(selectedOnly = false) {
            const rows = getVisibleRowsForExport(selectedOnly);
            if (!rows.length) {
                alert(selectedOnly ? 'No selected rows to export.' : 'No rows to export.');
                return;
            }

            const headers = ['ID', 'Title', 'Subject', 'Category', 'Type', 'Description', 'Details', 'Created By', 'Created', 'Updated'];
            const csvRows = [headers.join(',')];

            rows.forEach(row => {
                const data = extractRowData(row);
                const values = [data.id, data.title, data.subject, data.category, data.type, data.description, data.details, data.createdBy, data.createdAt, data.updatedAt]
                    .map(value => `"${String(value).replace(/"/g, '""')}"`);
                csvRows.push(values.join(','));
            });

            downloadFile(csvRows.join('\n'), 'documents.csv', 'text/csv;charset=utf-8;');
        }

        function exportAsExcel() {
            const rows = getVisibleRowsForExport(false);
            if (!rows.length) {
                alert('No rows to export.');
                return;
            }

            let html = '<table><tr><th>ID</th><th>Title</th><th>Subject</th><th>Category</th><th>Type</th><th>Description</th><th>Details</th><th>Created By</th><th>Created</th><th>Updated</th></tr>';
            rows.forEach(row => {
                const data = extractRowData(row);
                html += `<tr><td>${data.id}</td><td>${data.title}</td><td>${data.subject}</td><td>${data.category}</td><td>${data.type}</td><td>${data.description}</td><td>${data.details}</td><td>${data.createdBy}</td><td>${data.createdAt}</td><td>${data.updatedAt}</td></tr>`;
            });
            html += '</table>';

            downloadFile(html, 'documents.xls', 'application/vnd.ms-excel');
        }

        document.querySelectorAll('.documentCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateDeleteButtonState();
                updateSelectAllState();
            });
        });

        selectAllCheckbox.addEventListener('change', function () {
            getAllRows().forEach(row => {
                if (row.style.display === 'none') {
                    return;
                }
                const cb = row.querySelector('.documentCheckbox');
                if (cb) {
                    cb.checked = this.checked;
                }
            });
            updateDeleteButtonState();
            updateSelectAllState();
        });

        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', function () {
                const selectedIds = Array.from(document.querySelectorAll('.documentCheckbox:checked')).map(checkbox => checkbox.value);
                if (!selectedIds.length) {
                    alert('Please select at least one document to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete selected document(s)?')) {
                    return;
                }

                batchDeleteInputs.innerHTML = '';
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'document_ids[]';
                    input.value = id;
                    batchDeleteInputs.appendChild(input);
                });

                batchDeleteForm.submit();
            });
        }

        refreshBtn.addEventListener('click', function () {
            window.location.reload();
        });

        printBtn.addEventListener('click', function () {
            window.print();
        });

        fullscreenBtn.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });

        advancedSearchBtn.addEventListener('click', function () {
            advancedSearchModal.show();
            renderAdvancedSearchResults();
        });

        document.getElementById('clearAdvancedSearch').addEventListener('click', function () {
            document.querySelectorAll('.advSearchField').forEach(field => {
                field.value = '';
            });
            renderAdvancedSearchResults();
        });

        document.querySelectorAll('.advSearchField').forEach(field => {
            field.addEventListener('input', function () {
                renderAdvancedSearchResults();
            });
        });

        searchInput.addEventListener('input', function () {
            currentPage = 1;
            applyFiltersAndPagination();
        });

        subjectFilter.addEventListener('change', function () {
            currentPage = 1;
            applyFiltersAndPagination();
        });

        creatorFilter.addEventListener('change', function () {
            currentPage = 1;
            applyFiltersAndPagination();
        });

        categoryFilter.addEventListener('change', function () {
            currentPage = 1;
            applyFiltersAndPagination();
        });

        typeFilter.addEventListener('change', function () {
            currentPage = 1;
            applyFiltersAndPagination();
        });

        clearFiltersBtn.addEventListener('click', function () {
            searchInput.value = '';
            subjectFilter.value = '';
            creatorFilter.value = '';
            categoryFilter.value = '';
            typeFilter.value = '';
            document.querySelectorAll('.advSearchField').forEach(field => {
                field.value = '';
            });
            currentPage = 1;
            applyFiltersAndPagination();
            renderAdvancedSearchResults();
        });

        columnToggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                applyColumnVisibility();
                currentPage = 1;
                applyFiltersAndPagination();
            });
        });

        rowsPerPageSelect.addEventListener('change', function () {
            rowsPerPage = parseInt(this.value, 10) || 20;
            currentPage = 1;
            applyFiltersAndPagination();
        });

        prevPageBtn.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                applyFiltersAndPagination();
            }
        });

        nextPageBtn.addEventListener('click', function () {
            const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));
            if (currentPage < totalPages) {
                currentPage++;
                applyFiltersAndPagination();
            }
        });

        exportCsvBtn.addEventListener('click', function (event) {
            event.preventDefault();
            exportAsCsv(false);
        });

        exportExcelBtn.addEventListener('click', function (event) {
            event.preventDefault();
            exportAsExcel();
        });

        exportPdfBtn.addEventListener('click', function (event) {
            event.preventDefault();
            window.print();
        });

        exportSelectedBtn.addEventListener('click', function (event) {
            event.preventDefault();
            exportAsCsv(true);
        });

        applyColumnVisibility();
        applyFiltersAndPagination();
        updateDeleteButtonState();
    });
    </script>
</body>
</html>
