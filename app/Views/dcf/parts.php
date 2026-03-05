<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parts Management</title>
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
        .action-btn-view { background-color: #17a2b8; }
        .table-row { word-wrap: break-word; overflow-wrap: break-word; }
        .empty-state { text-align: center; padding: 40px 20px; color: #6c757d; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
    </style>
</head>
<?php $isReadonlyUser = strtolower(trim((string) session()->get('usertype'))) === 'readonly'; ?>
<body class="<?= $isReadonlyUser ? 'readonly-user' : '' ?>">
    <?= view('partials/header', ['title' => 'Parts Management']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('dcf') ?>" title="Go to DCF Management">DCF Management</a>
            <span class="separator">›</span>
            <span class="current">Parts</span>
        </div>

        <div class="d-flex gap-2 align-items-center mb-3 flex-wrap" style="padding: 10px; border-radius: 5px;">
            <div class="d-flex gap-1">
                <button class="btn btn-info btn-sm" id="refreshBtn" title="Refresh" aria-label="Refresh" data-bs-toggle="tooltip"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-primary btn-sm" id="fullscreenBtn" title="Fullscreen" aria-label="Fullscreen" data-bs-toggle="tooltip"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>

            <div class="vr" style="height: 32px;"></div>
            <div style="flex: 1; min-width: 200px;"><input type="text" id="parts_search" class="form-control form-control-sm" placeholder="Search in parts..." style="height: 32px;"></div>

            <div style="width: 200px;">
                <select id="parts_department" class="form-select form-select-sm" style="height: 32px;">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= esc($department['department_name']) ?>"><?= esc($department['department_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn" title="Clear filters" data-bs-toggle="tooltip" style="height: 32px; width: 32px; padding: 0;"><i class="bi bi-x-lg"></i></button>
        </div>

        <?php if (empty($parts)): ?>
            <div class="empty-state">
                <div><i class="bi bi-inbox"></i></div>
                <h5>No Parts Found</h5>
                <p>There are no saved parts yet. Create a DCF with parts to get started.</p>
                <a href="<?= site_url('dcf/create') ?>" class="btn btn-success btn-sm write-action">Create DCF</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="partsTable">
                    <thead style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 15%;">Part Title</th>
                            <th style="width: 25%;">Description</th>
                            <th style="width: 12%;">Questions</th>
                            <th style="width: 18%;">DCF Title</th>
                            <th style="width: 18%;">Department</th>
                            <th style="width: 12%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parts as $part): ?>
                            <tr class="table-row">
                                <td><strong><?= esc($part['title']) ?></strong></td>
                                <td><?= esc(substr($part['description'] ?? '', 0, 50)) ?><?= strlen($part['description'] ?? '') > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php
                                    $questionCount = 0;
                                    if (!empty($part['dcf_id'])) {
                                        $questionModel = new \App\Models\DcfQuestion();
                                        $questionCount = $questionModel
                                            ->where('dcf_id', $part['dcf_id'])
                                            ->where('part_id', $part['id'])
                                            ->countAllResults();
                                    }
                                    ?>
                                    <span class="badge bg-info"><?= $questionCount ?></span>
                                </td>
                                <td><?= esc($part['dcf_title']) ?></td>
                                <td><?= esc($part['department_name'] ?? '-') ?></td>
                                <td class="text-end">
                                    <a href="<?= site_url('dcf/details/' . esc($part['dcf_id'])) ?>" class="action-btn action-btn-view" title="View DCF" data-bs-toggle="tooltip"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .readonly-user .write-action { display: none !important; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            const searchInput = document.getElementById('parts_search');
            const departmentSelect = document.getElementById('parts_department');
            const clearBtn = document.getElementById('clearFiltersBtn');
            const table = document.getElementById('partsTable');

            if (searchInput && table) {
                searchInput.addEventListener('input', filterTable);
            }
            if (departmentSelect && table) {
                departmentSelect.addEventListener('change', filterTable);
            }
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (departmentSelect) departmentSelect.value = '';
                    if (table) filterTable();
                });
            }

            function filterTable() {
                const searchTerm = (searchInput?.value ?? '').toLowerCase();
                const selectedDept = (departmentSelect?.value ?? '').toLowerCase();

                if (!table) return;

                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const titleCell = row.children[0]?.textContent.toLowerCase() || '';
                    const descriptionCell = row.children[1]?.textContent.toLowerCase() || '';
                    const dcfTitleCell = row.children[3]?.textContent.toLowerCase() || '';
                    const deptCell = row.children[4]?.textContent.toLowerCase() || '';

                    const matchesSearch = titleCell.includes(searchTerm) || descriptionCell.includes(searchTerm) || dcfTitleCell.includes(searchTerm);
                    const matchesDept = !selectedDept || deptCell.includes(selectedDept);

                    row.style.display = (matchesSearch && matchesDept) ? '' : 'none';
                });
            }

            // Refresh button
            document.getElementById('refreshBtn')?.addEventListener('click', function() {
                location.reload();
            });

            // Fullscreen button
            document.getElementById('fullscreenBtn')?.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            });
        });
    </script>
</body>
</html>
