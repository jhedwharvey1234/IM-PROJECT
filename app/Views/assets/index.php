<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-in_transit { background-color: #17a2b8; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Asset Management']) ?>

    <div class="main-content">
        <h1>Asset Management</h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="<?= site_url('assets/create') ?>" class="btn btn-primary me-2">Add New Asset</a>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="assets_search" class="form-control" placeholder="Search (tracking #, sender, recipient)" style="width: 260px;">
                <select id="assets_status" class="form-select" style="width: 160px;">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
                <input type="date" id="assets_start_date" class="form-control" style="width:160px;" title="Start date">
                <input type="date" id="assets_end_date" class="form-control" style="width:160px;" title="End date">
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tracking #</th>
                    <th>Barcode</th>
                    <th>Sender</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th></th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="assetsTable">
                <?php foreach ($assets as $asset): ?>
                    <tr>
                        <td><?= $asset['id'] ?></td>
                        <td><?= $asset['tracking_number'] ?? '-' ?></td>
                        <td>
                            <?php if (!empty($asset['barcode'])): ?>
                                <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height:60px; max-width:120px;" />
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= $asset['sender'] ?></td>
                        <td><?= $asset['recipient'] ?></td>
                        <td>
                            <span class="status-badge status-<?= $asset['status'] ?>">
                                <?= ucfirst($asset['status']) ?>
                            </span>
                        </td>
                        <td><span style="color: #666; margin: 0 5px;">on</span></td>
                        <td>
                            <?php
                                $displayDate = $asset['date_sent'];
                                if ($asset['status'] === 'in_transit' && !empty($asset['date_in_transit'])) {
                                    $displayDate = $asset['date_in_transit'];
                                } elseif ($asset['status'] === 'completed' && !empty($asset['date_received'])) {
                                    $displayDate = $asset['date_received'];
                                } elseif ($asset['status'] === 'rejected' && !empty($asset['date_rejected'])) {
                                    $displayDate = $asset['date_rejected'];
                                }
                            ?>
                            <?= date('M d, Y H:i', strtotime($displayDate)) ?>
                        </td>
                        <td>
                            <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-sm btn-info">Details</a>
                            <a href="<?= site_url('assets/export-pdf/' . $asset['id']) ?>" class="btn btn-sm btn-secondary" target="_blank">📄 PDF</a>
                            <a href="<?= site_url('assets/edit/' . $asset['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('assets/delete/' . $asset['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('assets_search');
    const tableBody = document.getElementById('assetsTable');
    let timer;

    function fetchResults() {
        const q = searchInput.value;
        const status = document.getElementById('assets_status').value;
        const start = document.getElementById('assets_start_date').value;
        const end = document.getElementById('assets_end_date').value;

        const params = new URLSearchParams();
        if (q) params.set('search', q);
        if (status) params.set('status', status);
        if (start) params.set('start_date', start);
        if (end) params.set('end_date', end);

        fetch(`<?= site_url('assets/search') ?>?${params.toString()}`)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const rows = doc.querySelectorAll('#assetsTable tr');
                tableBody.innerHTML = '';
                rows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            })
            .catch(() => console.error('Search failed'));
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(timer);
            timer = setTimeout(fetchResults, 300);
        });
    }

    const statusSelect = document.getElementById('assets_status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            fetchResults();
        });
    }

    const startDate = document.getElementById('assets_start_date');
    const endDate = document.getElementById('assets_end_date');
    if (startDate) startDate.addEventListener('change', fetchResults);
    if (endDate) endDate.addEventListener('change', fetchResults);
});
</script>
    </body>
</html>
