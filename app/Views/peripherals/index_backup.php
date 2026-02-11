<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Peripherals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase; font-weight: bold; }
        .status-available { background-color: #28a745; color: white; }
        .status-in_use { background-color: #007bff; color: white; }
        .status-standby { background-color: #ffc107; color: #000; }
        .status-under_repair { background-color: #fd7e14; color: white; }
        .status-retired { background-color: #6c757d; color: white; }
        .status-lost { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Manage Peripherals']) ?>

    <div class="main-content">
        <h1>Peripherals Management</h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= site_url('peripherals/create') ?>" class="btn btn-primary">+ Add New Peripheral</a>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="peripherals_search" class="form-control" placeholder="Search (serial, brand, model)" style="width: 320.5px;">
                <input type="date" id="peripherals_start_date" class="form-control" style="width: 156.3px;" title="Purchase date start">
                <input type="date" id="peripherals_end_date" class="form-control" style="width: 156.3px;" title="Purchase date end">
            </div>
        </div>

        <div class="d-flex gap-2 align-items-center mb-3 justify-content-end">
            <select id="peripherals_status" class="form-select" style="width: 156.3px;">
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="in_use">In Use</option>
                <option value="standby">Standby</option>
                <option value="under_repair">Under Repair</option>
                <option value="retired">Retired</option>
                <option value="lost">Lost</option>
            </select>
            <select id="peripherals_condition" class="form-select" style="width: 156.3px;">
                <option value="">All Conditions</option>
                <option value="new">New</option>
                <option value="good">Good</option>
                <option value="fair">Fair</option>
                <option value="damaged">Damaged</option>
            </select>
            <select id="peripherals_peripheral_type" class="form-select" style="width: 156.3px;">
                <option value="">All Types</option>
                <?php foreach ($peripheral_types_map as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <select id="peripherals_location" class="form-select" style="width: 156.3px;">
                <option value="">All Locations</option>
                <?php foreach ($locations as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <select id="peripherals_department" class="form-select" style="width: 156.3px;">
                <option value="">All Departments</option>
                <?php foreach ($departments as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <select id="peripherals_workstation" class="form-select" style="width: 156.3px;">
                <option value="">All Workstations</option>
                <?php foreach ($workstations as $id => $code): ?>
                    <option value="<?= $id ?>"><?= $code ?></option>
                <?php endforeach; ?>
            </select>
            <select id="peripherals_assigned_to" class="form-select" style="width: 156.3px;">
                <option value="">All Users</option>
                <?php foreach ($assignable_users as $id => $name): ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
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
                    <th>Asset</th>
                    <th>Type</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Serial #</th>
                    <th>Purchase Date</th>
                    <th>Status</th>
                    <th>Condition</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="peripheralsTable">
                <?php foreach ($peripherals as $peripheral): ?>
                    <tr>
                        <td><?= $peripheral['id'] ?></td>
                        <td>
                            <?php if (!empty($peripheral['asset_id']) && isset($assets[$peripheral['asset_id']])): ?>
                                <a href="<?= site_url('assets/details/' . $peripheral['asset_id']) ?>"><?= $assets[$peripheral['asset_id']] ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= $peripheral_types_map[$peripheral['peripheral_type_id']] ?? 'Unknown' ?></td>
                        <td><?= $peripheral['brand'] ?? '-' ?></td>
                        <td><?= $peripheral['model'] ?? '-' ?></td>
                        <td><?= $peripheral['serial_number'] ?? '-' ?></td>
                        <td>
                            <?= !empty($peripheral['purchase_date']) ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '-' ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $peripheral['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge" style="background-color: #6c757d; color: white;">
                                <?= ucfirst($peripheral['condition_status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= site_url('peripherals/details/' . $peripheral['id']) ?>" class="btn btn-sm btn-info">Details</a>
                            <a href="<?= site_url('peripherals/edit/' . $peripheral['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('peripherals/delete/' . $peripheral['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
        const searchInput = document.getElementById('peripherals_search');
        const tableBody = document.getElementById('peripheralsTable');
        let timer;

        function fetchResults() {
            const q = searchInput.value;
            const status = document.getElementById('peripherals_status').value;
            const condition = document.getElementById('peripherals_condition').value;
            const start = document.getElementById('peripherals_start_date').value;
            const end = document.getElementById('peripherals_end_date').value;
            const peripheralType = document.getElementById('peripherals_peripheral_type').value;
            const location = document.getElementById('peripherals_location').value;
            const department = document.getElementById('peripherals_department').value;
            const workstation = document.getElementById('peripherals_workstation').value;
            const assignedTo = document.getElementById('peripherals_assigned_to').value;

            const params = new URLSearchParams();
            if (q) params.set('search', q);
            if (status) params.set('status', status);
            if (condition) params.set('condition_status', condition);
            if (start) params.set('start_date', start);
            if (end) params.set('end_date', end);
            if (peripheralType) params.set('peripheral_type_id', peripheralType);
            if (location) params.set('location_id', location);
            if (department) params.set('department_id', department);
            if (workstation) params.set('workstation_id', workstation);
            if (assignedTo) params.set('assigned_to_user_id', assignedTo);

            fetch(`<?= site_url('peripherals/search') ?>?${params.toString()}`)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const rows = doc.querySelectorAll('#peripheralsTable tr');
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

        // Add event listeners for all filter select elements
        ['peripherals_status', 'peripherals_condition', 'peripherals_peripheral_type', 
         'peripherals_location', 'peripherals_department', 'peripherals_workstation', 
         'peripherals_assigned_to'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', fetchResults);
            }
        });

        const startDate = document.getElementById('peripherals_start_date');
        const endDate = document.getElementById('peripherals_end_date');
        if (startDate) startDate.addEventListener('change', fetchResults);
        if (endDate) endDate.addEventListener('change', fetchResults);
    });
    </script>
    </body>
</html>
