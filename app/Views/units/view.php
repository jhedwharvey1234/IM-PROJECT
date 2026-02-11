<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        
        .detail-card { background-color: #fff; padding: 25px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .detail-card h3 { color: #212529; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; display: flex; align-items: center; }
        .detail-card h3 i { margin-right: 10px; color: #0d6efd; }
        
        .detail-row { margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .detail-label { font-weight: 600; color: #495057; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px; }
        .detail-value { color: #212529; font-size: 15px; word-break: break-word; }
        
        .type-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
        .type-asset { background-color: #cfe2ff; color: #084298; }
        .type-peripheral { background-color: #fff3cd; color: #856404; }
        .type-both { background-color: #d1e7dd; color: #0f5132; }
        
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-ready.to.deploy { background-color: #d1e7dd; color: #0f5132; }
        .status-available { background-color: #d1e7dd; color: #0f5132; }
        .status-in_use { background-color: #cfe2ff; color: #084298; }
        .status-standby { background-color: #fff3cd; color: #856404; }
        .status-under_repair { background-color: #ffeaa7; color: #cc5500; }
        .status-retired { background-color: #e2e3e5; color: #383d41; }
        .status-lost { background-color: #f8d7da; color: #842029; }
        .status-archived { background-color: #e2e3e5; color: #383d41; }
        .status-broken { background-color: #f8d7da; color: #842029; }
        
        .nav-tabs { border-bottom: 2px solid #e9ecef; }
        .nav-tabs .nav-link { color: #495057; border: none; border-bottom: 3px solid transparent; padding: 12px 16px; font-weight: 500; }
        .nav-tabs .nav-link:hover { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
        
        .action-btns { display: flex; gap: 10px; margin-bottom: 20px; }
        .action-btns .btn { display: inline-flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Unit Details']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('units') ?>" title="Go to Units">Units</a>
            <span class="separator">›</span>
            <span class="current"><?= esc($unit['unit_name']) ?></span>
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

        <!-- Action Buttons -->
        <div class="action-btns">
            <a href="<?= site_url('units/edit/' . $unit['id']) ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit Unit
            </a>
            <a href="<?= site_url('units') ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Units
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="unitDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-details-btn" data-bs-toggle="tab" data-bs-target="#tab-details" type="button" role="tab" aria-controls="tab-details" aria-selected="true">
                            <i class="bi bi-info-circle"></i> Unit Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-assets-btn" data-bs-toggle="tab" data-bs-target="#tab-assets" type="button" role="tab" aria-controls="tab-assets" aria-selected="false">
                            <i class="bi bi-laptop"></i> Assets (<?= count($unit_assets) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-peripherals-btn" data-bs-toggle="tab" data-bs-target="#tab-peripherals" type="button" role="tab" aria-controls="tab-peripherals" aria-selected="false">
                            <i class="bi bi-cpu"></i> Peripherals (<?= count($unit_peripherals) ?>)
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content mt-4" id="unitDetailsTabsContent">
                    <!-- Unit Details Tab -->
                    <div class="tab-pane fade show active" id="tab-details" role="tabpanel" aria-labelledby="tab-details-btn">
                        <div class="detail-card">
                            <h3><i class="bi bi-box"></i> Basic Information</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-hash"></i> Unit ID</span>
                                        <span class="detail-value"><?= $unit['id'] ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-tag"></i> Unit Name</span>
                                        <span class="detail-value"><?= esc($unit['unit_name']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-layers"></i> Unit Type</span>
                                        <span class="type-badge type-<?= $unit['unit_type'] ?>">
                                            <?php if ($unit['unit_type'] === 'asset'): ?>
                                                <i class="bi bi-laptop"></i> Asset
                                            <?php elseif ($unit['unit_type'] === 'peripheral'): ?>
                                                <i class="bi bi-cpu"></i> Peripheral
                                            <?php else: ?>
                                                <i class="bi bi-collection"></i> Both
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-plus"></i> Created At</span>
                                        <span class="detail-value"><?= !empty($unit['created_at']) ? date('M d, Y h:i A', strtotime($unit['created_at'])) : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-check"></i> Updated At</span>
                                        <span class="detail-value"><?= !empty($unit['updated_at']) ? date('M d, Y h:i A', strtotime($unit['updated_at'])) : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($unit['notes'])): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-sticky"></i> Notes</h3>
                            <div class="detail-value" style="padding: 10px; background-color: #f8f9fa; border-radius: 6px;">
                                <?= nl2br(esc($unit['notes'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Assets Tab -->
                    <div class="tab-pane fade" id="tab-assets" role="tabpanel" aria-labelledby="tab-assets-btn">
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0"><i class="bi bi-laptop"></i> Linked Assets</h3>
                            </div>

                            <?php if (empty($unit_assets)): ?>
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No assets linked to this unit.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="bi bi-hash"></i> ID</th>
                                                <th><i class="bi bi-credit-card"></i> Asset Tag</th>
                                                <th><i class="bi bi-laptop"></i> Model</th>
                                                <th><i class="bi bi-building"></i> Manufacturer</th>
                                                <th><i class="bi bi-key"></i> Serial #</th>
                                                <th><i class="bi bi-flag"></i> Status</th>
                                                <th><i class="bi bi-person"></i> Recipient</th>
                                                <th><i class="bi bi-link-45deg"></i> Linked</th>
                                                <th><i class="bi bi-gear"></i> Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($unit_assets as $asset): ?>
                                                <tr>
                                                    <td><?= $asset['id'] ?></td>
                                                    <td><code><?= esc($asset['asset_tag'] ?? '-') ?></code></td>
                                                    <td><?= esc($asset['model'] ?? '-') ?></td>
                                                    <td><?= esc($asset['manufacturer'] ?? '-') ?></td>
                                                    <td><small><?= esc($asset['serial_number'] ?? '-') ?></small></td>
                                                    <td>
                                                        <span class="status-badge status-<?= str_replace([' ', '-'], '.', strtolower($asset['status'])) ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $asset['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= esc($asset['recipient'] ?? '-') ?></td>
                                                    <td><small class="text-muted"><?= !empty($asset['linked_at']) ? date('M d, Y', strtotime($asset['linked_at'])) : '-' ?></small></td>
                                                    <td>
                                                        <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Asset">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Peripherals Tab -->
                    <div class="tab-pane fade" id="tab-peripherals" role="tabpanel" aria-labelledby="tab-peripherals-btn">
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0"><i class="bi bi-cpu"></i> Linked Peripherals</h3>
                            </div>

                            <?php if (empty($unit_peripherals)): ?>
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No peripherals linked to this unit.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="bi bi-hash"></i> ID</th>
                                                <th><i class="bi bi-device-ssd"></i> Type</th>
                                                <th><i class="bi bi-building"></i> Brand</th>
                                                <th><i class="bi bi-laptop"></i> Model</th>
                                                <th><i class="bi bi-key"></i> Serial #</th>
                                                <th><i class="bi bi-flag"></i> Status</th>
                                                <th><i class="bi bi-wrench"></i> Condition</th>
                                                <th><i class="bi bi-link-45deg"></i> Linked</th>
                                                <th><i class="bi bi-gear"></i> Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($unit_peripherals as $peripheral): ?>
                                                <tr>
                                                    <td><?= $peripheral['id'] ?></td>
                                                    <td><?= esc($peripheral['peripheral_type_name'] ?? '-') ?></td>
                                                    <td><?= esc($peripheral['brand'] ?? '-') ?></td>
                                                    <td><?= esc($peripheral['model'] ?? '-') ?></td>
                                                    <td><small><?= esc($peripheral['serial_number'] ?? '-') ?></small></td>
                                                    <td>
                                                        <span class="status-badge status-<?= $peripheral['status'] ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?= ucfirst($peripheral['condition_status'] ?? 'unknown') ?></span>
                                                    </td>
                                                    <td><small class="text-muted"><?= !empty($peripheral['linked_at']) ? date('M d, Y', strtotime($peripheral['linked_at'])) : '-' ?></small></td>
                                                    <td>
                                                        <a href="<?= site_url('peripherals/details/' . $peripheral['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Peripheral">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>
