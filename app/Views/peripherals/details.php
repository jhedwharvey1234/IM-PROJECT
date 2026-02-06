<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peripheral Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .detail-card { background: white; border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 20px; }
        .detail-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .detail-label { font-weight: bold; width: 250px; color: #007bff; }
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
    <?= view('partials/header', ['title' => 'Peripheral Details']) ?>

    <div class="main-content">
        <h1>Peripheral Details #<?= $peripheral['id'] ?></h1>
        
        <a href="<?= site_url('peripherals') ?>" class="btn btn-secondary mb-3">Back to Peripherals</a>

        <div class="detail-card">
            <div class="detail-row">
                <span class="detail-label">Asset Tag:</span>
                <span><?= $peripheral['asset_tag'] ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Peripheral Type:</span>
                <span><?= $peripheral_type_name ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Brand:</span>
                <span><?= $peripheral['brand'] ?? '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Model:</span>
                <span><?= $peripheral['model'] ?? '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Serial Number:</span>
                <span><?= $peripheral['serial_number'] ?? '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="status-badge status-<?= $peripheral['status'] ?>">
                    <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Condition:</span>
                <span class="status-badge" style="background-color: #6c757d; color: white;">
                    <?= ucfirst($peripheral['condition_status']) ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Criticality:</span>
                <span class="status-badge" style="background-color: #9c27b0; color: white;">
                    <?= ucfirst($peripheral['criticality']) ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Department:</span>
                <span><?= $department_name ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location:</span>
                <span><?= $location_name ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Assigned to User:</span>
                <span><?= $assigned_user_name ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Workstation:</span>
                <span><?= $workstation_code ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Purchase Date:</span>
                <span><?= $peripheral['purchase_date'] ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Warranty Expiry:</span>
                <span><?= $peripheral['warranty_expiry'] ? date('M d, Y', strtotime($peripheral['warranty_expiry'])) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Vendor:</span>
                <span><?= $peripheral['vendor'] ?? '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Maintenance Date:</span>
                <span><?= $peripheral['last_maintenance_date'] ? date('M d, Y', strtotime($peripheral['last_maintenance_date'])) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Next Maintenance Due:</span>
                <span><?= $peripheral['next_maintenance_due'] ? date('M d, Y', strtotime($peripheral['next_maintenance_due'])) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span><?= $peripheral['notes'] ? nl2br($peripheral['notes']) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created At:</span>
                <span><?= date('M d, Y H:i', strtotime($peripheral['created_at'])) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Updated At:</span>
                <span><?= date('M d, Y H:i', strtotime($peripheral['updated_at'])) ?></span>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <a href="<?= site_url('peripherals/edit/' . $peripheral['id']) ?>" class="btn btn-warning">Edit</a>
            <a href="<?= site_url('peripherals/delete/' . $peripheral['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
