<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Details</title>
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
        
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-in_transit { background-color: #cfe2ff; color: #084298; }
        .status-completed { background-color: #d1e7dd; color: #0f5132; }
        .status-rejected { background-color: #f8d7da; color: #842029; }
        .status-available { background-color: #d1e7dd; color: #0f5132; }
        .status-in_use { background-color: #cfe2ff; color: #084298; }
        .status-standby { background-color: #fff3cd; color: #856404; }
        .status-under_repair { background-color: #ffeaa7; color: #cc5500; }
        .status-retired { background-color: #e2e3e5; color: #383d41; }
        .status-lost { background-color: #f8d7da; color: #842029; }
        
        .asset-side-panel { background: white; border-radius: 8px; padding: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-top: 75px; }
        .asset-image { background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 20px; min-height: 250px; display: flex; align-items: center; justify-content: center; }
        .asset-image img { max-width: 100%; max-height: 240px; object-fit: contain; }
        .asset-image .placeholder { color: #adb5bd; font-size: 14px; }
        
        .action-btn { width: 100%; margin-bottom: 12px; }
        .action-btn i { margin-right: 8px; }
        
        .nav-tabs { border-bottom: 2px solid #e9ecef; }
        .nav-tabs .nav-link { color: #495057; border: none; border-bottom: 3px solid transparent; padding: 12px 16px; font-weight: 500; }
        .nav-tabs .nav-link:hover { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
        
        .info-section { background: #f8f9fa; padding: 16px; border-radius: 6px; border-left: 4px solid #0d6efd; }
        .info-section h5 { color: #0d6efd; font-weight: 600; margin-bottom: 15px; font-size: 13px; text-transform: uppercase; }
        
        .back-link { display: inline-flex; align-items: center; margin-bottom: 15px; color: #0d6efd; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        .back-link i { margin-right: 8px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Asset Details']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('assets') ?>" title="Go to Assets">Assets</a>
            <span class="separator">›</span>
            <span class="current"><?= !empty($asset['model']) ? esc($asset['model']) : 'Asset Details' ?></span>
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

        <div class="row">
            <div class="col-lg-9">
                <ul class="nav nav-tabs" id="assetDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-info-btn" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab" aria-controls="tab-info" aria-selected="true"><i class="bi bi-info-circle"></i> Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-peripherals-btn" data-bs-toggle="tab" data-bs-target="#tab-peripherals" type="button" role="tab" aria-controls="tab-peripherals" aria-selected="false"><i class="bi bi-cpu"></i> Peripherals</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-notes-btn" data-bs-toggle="tab" data-bs-target="#tab-notes" type="button" role="tab" aria-controls="tab-notes" aria-selected="false"><i class="bi bi-sticky"></i> Notes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab" aria-controls="tab-history" aria-selected="false"><i class="bi bi-clock-history"></i> History</button>
                    </li>
                </ul>
                
                <div class="tab-content mt-4" id="assetDetailsTabsContent">
                    <div class="tab-pane fade show active" id="tab-info" role="tabpanel" aria-labelledby="tab-info-btn">
                        <!-- Asset Details Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-tag"></i> Basic Information</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-credit-card"></i> Asset Tag</span>
                                        <span class="detail-value"><?= $asset['asset_tag'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-hash"></i> Serial Number</span>
                                        <span class="detail-value"><?= $asset['serial_number'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-laptop"></i> Model</span>
                                        <span class="detail-value"><?= $asset['model'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-key"></i> Model Number</span>
                                        <span class="detail-value"><?= $asset['model_number'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-building"></i> Manufacturer</span>
                                        <span class="detail-value"><?= $asset['manufacturer'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-tag-fill"></i> Category</span>
                                        <span class="detail-value"><?= $asset['category'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-stack"></i> Status</span>
                                        <span class="status-badge status-<?= $asset['status'] ?>">
                                            <i class="bi bi-flag-fill"></i> <?= ucfirst($asset['status']) ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-event"></i> Purchase Date</span>
                                        <span class="detail-value"><?= !empty($asset['purchase_date']) ? date('M d, Y', strtotime($asset['purchase_date'])) : '<span class="text-muted">Not set</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-currency-dollar"></i> Purchase Cost</span>
                                        <span class="detail-value"><?= !empty($asset['purchase_cost']) ? '$' . number_format($asset['purchase_cost'], 2) : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-receipt"></i> Order Number</span>
                                        <span class="detail-value"><?= !empty($asset['order_number']) ? $asset['order_number'] : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-shop"></i> Supplier</span>
                                        <span class="detail-value"><?= !empty($asset['supplier']) ? $asset['supplier'] : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-box"></i> Qty</span>
                                        <span class="detail-value"><?= $asset['qty'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barcode Card -->
                        <?php if (!empty($asset['barcode'])): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-barcode"></i> Barcode</h3>
                            <div style="text-align: center; padding: 20px 0;">
                                <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Location & Assignment Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-geo-alt"></i> Location & Assignment</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-building"></i> Department</span>
                                        <span class="detail-value"><?= !empty($asset['department_id']) && isset($departments[$asset['department_id']]) ? $departments[$asset['department_id']] : '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-pin-map"></i> Location</span>
                                        <span class="detail-value"><?= !empty($asset['location_id']) && isset($locations[$asset['location_id']]) ? $locations[$asset['location_id']] : '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-house-door"></i> Workstation</span>
                                        <span class="detail-value"><?= !empty($asset['workstation_id']) && isset($workstations[$asset['workstation_id']]) ? $workstations[$asset['workstation_id']] : '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                </div>

                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-person-badge"></i> Assigned To</span>
                                        <span class="detail-value"><?= !empty($asset['assigned_to_user_id']) && isset($assignable_users[$asset['assigned_to_user_id']]) ? $assignable_users[$asset['assigned_to_user_id']] : '<span class="text-muted">Unassigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-hand-thumbs-up"></i> Requestable</span>
                                        <span class="detail-value"><?= !empty($asset['requestable']) ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-phone"></i> BYOD</span>
                                        <span class="detail-value"><?= !empty($asset['byod']) ? '<span class="badge bg-info"><i class="bi bi-check-circle"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                       

                        <?php if ($asset['description']): ?>
                        <div class="detail-card">
                            <h3><i class="bi bi-file-text"></i> Description</h3>
                            <div class="detail-value" style="padding: 10px; background-color: #f8f9fa; border-radius: 6px;">
                                <?= nl2br($asset['description']) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Peripherals Summary Card -->
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0"><i class="bi bi-cpu"></i> Peripherals</h3>
                            </div>

                            <?php if (empty($peripherals)): ?>
                                <div class="alert alert-info mb-0"><i class="bi bi-info-circle"></i> No peripherals linked to this asset.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="bi bi-device-ssd"></i> Type</th>
                                                <th><i class="bi bi-building"></i> Brand</th>
                                                <th><i class="bi bi-laptop"></i> Model</th>
                                                <th><i class="bi bi-key"></i> Serial #</th>
                                                <th><i class="bi bi-flag"></i> Status</th>
                                                <th><i class="bi bi-wrench"></i> Condition</th>
                                                <th><i class="bi bi-gear"></i> Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($peripherals as $peripheral): ?>
                                                <tr>
                                                    <td><?= $peripheral_types[$peripheral['peripheral_type_id']] ?? 'Unknown' ?></td>
                                                    <td><?= $peripheral['brand'] ?? '-' ?></td>
                                                    <td><?= $peripheral['model'] ?? '-' ?></td>
                                                    <td><code><?= $peripheral['serial_number'] ?? '-' ?></code></td>
                                                    <td>
                                                        <span class="status-badge status-<?= $peripheral['status'] ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?= ucfirst($peripheral['condition_status']) ?></span>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPeripheralModal"
                                                            data-peripheral-type="<?= esc($peripheral_types[$peripheral['peripheral_type_id']] ?? 'Unknown') ?>"
                                                            data-brand="<?= esc($peripheral['brand'] ?? '-') ?>"
                                                            data-model="<?= esc($peripheral['model'] ?? '-') ?>"
                                                            data-model-number="<?= esc($peripheral['model_number'] ?? '-') ?>"
                                                            data-serial="<?= esc($peripheral['serial_number'] ?? '-') ?>"
                                                            data-device-image="<?= !empty($peripheral['device_image']) ? base_url('uploads/devices/' . $peripheral['device_image']) : '' ?>"
                                                            data-status="<?= esc(ucfirst(str_replace('_', ' ', $peripheral['status']))) ?>"
                                                            data-condition="<?= esc(ucfirst($peripheral['condition_status'])) ?>"
                                                            data-department="<?= esc($departments[$peripheral['department_id']] ?? '-') ?>"
                                                            data-location="<?= esc($locations[$peripheral['location_id']] ?? '-') ?>"
                                                            data-workstation="<?= esc($workstations[$peripheral['workstation_id']] ?? '-') ?>"
                                                            data-assigned="<?= esc($assignable_users[$peripheral['assigned_to_user_id']] ?? '-') ?>"
                                                            data-purchase-date="<?= !empty($peripheral['purchase_date']) ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '-' ?>"
                                                            data-purchase-cost="<?= !empty($peripheral['purchase_cost']) ? '₱' . number_format($peripheral['purchase_cost'], 2) : '-' ?>"
                                                            data-order-number="<?= esc($peripheral['order_number'] ?? '-') ?>"
                                                            data-supplier="<?= esc($peripheral['supplier'] ?? '-') ?>"
                                                            data-vendor="<?= esc($peripheral['vendor'] ?? '-') ?>"
                                                            data-warranty-expiry="<?= !empty($peripheral['warranty_expiry']) ? date('M d, Y', strtotime($peripheral['warranty_expiry'])) : '-' ?>"
                                                            data-qty="<?= esc($peripheral['qty'] ?? '1') ?>"
                                                            data-requestable="<?= !empty($peripheral['requestable']) ? 'Yes' : 'No' ?>"
                                                            data-byod="<?= !empty($peripheral['byod']) ? 'Yes' : 'No' ?>"
                                                        >
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPeripheralModal"
                                                            data-peripheral-id="<?= (int) $peripheral['id'] ?>"
                                                            data-peripheral-type-id="<?= (int) $peripheral['peripheral_type_id'] ?>"
                                                            data-brand="<?= esc($peripheral['brand'] ?? '') ?>"
                                                            data-model="<?= esc($peripheral['model'] ?? '') ?>"
                                                            data-model-number="<?= esc($peripheral['model_number'] ?? '') ?>"
                                                            data-serial="<?= esc($peripheral['serial_number'] ?? '') ?>"
                                                            data-department-id="<?= (int) ($peripheral['department_id'] ?? 0) ?>"
                                                            data-location-id="<?= (int) ($peripheral['location_id'] ?? 0) ?>"
                                                            data-workstation-id="<?= (int) ($peripheral['workstation_id'] ?? 0) ?>"
                                                            data-assigned-id="<?= (int) ($peripheral['assigned_to_user_id'] ?? 0) ?>"
                                                            data-status="<?= esc($peripheral['status']) ?>"
                                                            data-condition="<?= esc($peripheral['condition_status']) ?>"
                                                            data-criticality="<?= esc($peripheral['criticality']) ?>"
                                                            data-purchase-date="<?= esc($peripheral['purchase_date'] ?? '') ?>"
                                                            data-purchase-cost="<?= esc($peripheral['purchase_cost'] ?? '') ?>"
                                                            data-order-number="<?= esc($peripheral['order_number'] ?? '') ?>"
                                                            data-supplier="<?= esc($peripheral['supplier'] ?? '') ?>"
                                                            data-qty="<?= esc($peripheral['qty'] ?? '1') ?>"
                                                            data-vendor="<?= esc($peripheral['vendor'] ?? '') ?>"
                                                            data-warranty-expiry="<?= esc($peripheral['warranty_expiry'] ?? '') ?>"
                                                            data-requestable="<?= (int) ($peripheral['requestable'] ?? 0) ?>"
                                                            data-byod="<?= (int) ($peripheral['byod'] ?? 0) ?>"
                                                            title="Edit"
                                                        >
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deletePeripheralModal"
                                                            data-peripheral-id="<?= (int) $peripheral['id'] ?>"
                                                            data-peripheral-label="<?= esc(($peripheral_types[$peripheral['peripheral_type_id']] ?? 'Peripheral') . ' ' . ($peripheral['brand'] ?? '') . ' ' . ($peripheral['model'] ?? '')) ?>"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Metadata Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-clock"></i> Metadata</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-plus"></i> Created At</span>
                                        <span class="detail-value"><?= date('M d, Y \a\t h:i A', strtotime($asset['created_at'])) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-pencil-square"></i> Updated At</span>
                                        <span class="detail-value"><?= date('M d, Y \a\t h:i A', strtotime($asset['updated_at'])) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peripherals Tab Content -->
                    <div class="tab-pane fade" id="tab-peripherals" role="tabpanel" aria-labelledby="tab-peripherals-btn">
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3><i class="bi bi-cpu"></i> Peripherals in this Asset</h3>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPeripheralModal">
                                    <i class="bi bi-plus-circle"></i> Add Peripheral
                                </button>
                            </div>
                            
                            <?php if (empty($peripherals)): ?>
                                <div class="alert alert-info"><i class="bi bi-info-circle"></i> No peripherals found. Click "Add Peripheral" button above to add one.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="bi bi-hash"></i> ID</th>
                                                <th><i class="bi bi-device-ssd"></i> Type</th>
                                                <th><i class="bi bi-building"></i> Brand</th>
                                                <th><i class="bi bi-laptop"></i> Model</th>
                                                <th><i class="bi bi-key"></i> Serial #</th>
                                                <th><i class="bi bi-flag"></i> Status</th>
                                                <th><i class="bi bi-wrench"></i> Condition</th>
                                                <th><i class="bi bi-gear"></i> Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($peripherals as $peripheral): ?>
                                                <tr>
                                                    <td><?= $peripheral['id'] ?></td>
                                                    <td><?= $peripheral_types[$peripheral['peripheral_type_id']] ?? 'Unknown' ?></td>
                                                    <td><?= $peripheral['brand'] ?? '-' ?></td>
                                                    <td><?= $peripheral['model'] ?? '-' ?></td>
                                                    <td><code><?= $peripheral['serial_number'] ?? '-' ?></code></td>
                                                    <td>
                                                        <span class="status-badge status-<?= $peripheral['status'] ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?= ucfirst($peripheral['condition_status']) ?></span>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewPeripheralModal"
                                                            data-peripheral-type="<?= esc($peripheral_types[$peripheral['peripheral_type_id']] ?? 'Unknown') ?>"
                                                            data-brand="<?= esc($peripheral['brand'] ?? '-') ?>"
                                                            data-model="<?= esc($peripheral['model'] ?? '-') ?>"
                                                            data-model-number="<?= esc($peripheral['model_number'] ?? '-') ?>"
                                                            data-serial="<?= esc($peripheral['serial_number'] ?? '-') ?>"
                                                            data-device-image="<?= !empty($peripheral['device_image']) ? base_url('uploads/devices/' . $peripheral['device_image']) : '' ?>"
                                                            data-status="<?= esc(ucfirst(str_replace('_', ' ', $peripheral['status']))) ?>"
                                                            data-condition="<?= esc(ucfirst($peripheral['condition_status'])) ?>"
                                                            data-department="<?= esc($departments[$peripheral['department_id']] ?? '-') ?>"
                                                            data-location="<?= esc($locations[$peripheral['location_id']] ?? '-') ?>"
                                                            data-workstation="<?= esc($workstations[$peripheral['workstation_id']] ?? '-') ?>"
                                                            data-assigned="<?= esc($assignable_users[$peripheral['assigned_to_user_id']] ?? '-') ?>"
                                                            data-purchase-date="<?= !empty($peripheral['purchase_date']) ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '-' ?>"
                                                            data-purchase-cost="<?= !empty($peripheral['purchase_cost']) ? '₱' . number_format($peripheral['purchase_cost'], 2) : '-' ?>"
                                                            data-order-number="<?= esc($peripheral['order_number'] ?? '-') ?>"
                                                            data-supplier="<?= esc($peripheral['supplier'] ?? '-') ?>"
                                                            data-vendor="<?= esc($peripheral['vendor'] ?? '-') ?>"
                                                            data-warranty-expiry="<?= !empty($peripheral['warranty_expiry']) ? date('M d, Y', strtotime($peripheral['warranty_expiry'])) : '-' ?>"
                                                            data-qty="<?= esc($peripheral['qty'] ?? '1') ?>"
                                                            data-requestable="<?= !empty($peripheral['requestable']) ? 'Yes' : 'No' ?>"
                                                            data-byod="<?= !empty($peripheral['byod']) ? 'Yes' : 'No' ?>"
                                                            title="View Details"
                                                        >
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPeripheralModal"
                                                            data-peripheral-id="<?= (int) $peripheral['id'] ?>"
                                                            data-peripheral-type-id="<?= (int) $peripheral['peripheral_type_id'] ?>"
                                                            data-brand="<?= esc($peripheral['brand'] ?? '') ?>"
                                                            data-model="<?= esc($peripheral['model'] ?? '') ?>"
                                                            data-model-number="<?= esc($peripheral['model_number'] ?? '') ?>"
                                                            data-serial="<?= esc($peripheral['serial_number'] ?? '') ?>"
                                                            data-department-id="<?= (int) ($peripheral['department_id'] ?? 0) ?>"
                                                            data-location-id="<?= (int) ($peripheral['location_id'] ?? 0) ?>"
                                                            data-workstation-id="<?= (int) ($peripheral['workstation_id'] ?? 0) ?>"
                                                            data-assigned-id="<?= (int) ($peripheral['assigned_to_user_id'] ?? 0) ?>"
                                                            data-status="<?= esc($peripheral['status']) ?>"
                                                            data-condition="<?= esc($peripheral['condition_status']) ?>"
                                                            data-criticality="<?= esc($peripheral['criticality']) ?>"
                                                            data-purchase-date="<?= esc($peripheral['purchase_date'] ?? '') ?>"
                                                            data-purchase-cost="<?= esc($peripheral['purchase_cost'] ?? '') ?>"
                                                            data-order-number="<?= esc($peripheral['order_number'] ?? '') ?>"
                                                            data-supplier="<?= esc($peripheral['supplier'] ?? '') ?>"
                                                            data-qty="<?= esc($peripheral['qty'] ?? '1') ?>"
                                                            data-vendor="<?= esc($peripheral['vendor'] ?? '') ?>"
                                                            data-warranty-expiry="<?= esc($peripheral['warranty_expiry'] ?? '') ?>"
                                                            data-requestable="<?= (int) ($peripheral['requestable'] ?? 0) ?>"
                                                            data-byod="<?= (int) ($peripheral['byod'] ?? 0) ?>"
                                                            title="Edit"
                                                        >
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deletePeripheralModal"
                                                            data-peripheral-id="<?= (int) $peripheral['id'] ?>"
                                                            data-peripheral-label="<?= esc(($peripheral_types[$peripheral['peripheral_type_id']] ?? 'Peripheral') . ' ' . ($peripheral['brand'] ?? '') . ' ' . ($peripheral['model'] ?? '')) ?>"
                                                            title="Delete"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Notes Tab Content -->
                    <div class="tab-pane fade" id="tab-notes" role="tabpanel" aria-labelledby="tab-notes-btn">
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0"><i class="bi bi-sticky"></i> Asset Notes</h3>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                                    <i class="bi bi-plus-circle"></i> Add Note
                                </button>
                            </div>

                            <?php if (!empty($notes)): ?>
                                <div class="notes-list">
                                    <?php foreach ($notes as $note): ?>
                                        <div class="note-item" style="background: #f8f9fa; border-left: 4px solid #0d6efd; border-radius: 6px; padding: 16px; margin-bottom: 15px;">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong style="color: #212529;"><i class="bi bi-person-circle"></i> <?= esc($note['username'] ?? 'Unknown User') ?></strong>
                                                    <small class="text-muted ms-2"><i class="bi bi-calendar-event"></i> <?= date('M d, Y \a\t h:i A', strtotime($note['created_at'])) ?></small>
                                                </div>
                                                <?php if ($note['user_id'] == session()->get('user_id') || session()->get('usertype') === 'superadmin'): ?>
                                                    <a href="<?= site_url('assets/note/delete/' . $note['id']) ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Delete this note?')"
                                                       title="Delete note">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="note-content" style="color: #495057; line-height: 1.6;">
                                                <?= nl2br(esc($note['note'])) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info"><i class="bi bi-info-circle"></i> No notes yet. Click "Add Note" to add the first note.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- History Tab Content -->
                    <div class="tab-pane fade" id="tab-history" role="tabpanel" aria-labelledby="tab-history-btn">
                        <div class="detail-card">
                            <h3><i class="bi bi-clock-history"></i> Change History</h3>

                            <?php if (!empty($history)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 150px;"><i class="bi bi-calendar-event"></i> Date & Time</th>
                                                <th style="width: 130px;"><i class="bi bi-person"></i> User</th>
                                                <th style="width: 110px;"><i class="bi bi-lightning-fill"></i> Action</th>
                                                <th style="width: 130px;"><i class="bi bi-tag"></i> Field</th>
                                                <th><i class="bi bi-text-left"></i> Changes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($history as $entry): ?>
                                                <tr>
                                                    <td>
                                                        <small><strong><?= date('M d, Y', strtotime($entry['created_at'])) ?></strong><br>
                                                        <span class="text-muted"><?= date('h:i A', strtotime($entry['created_at'])) ?></span></small>
                                                    </td>
                                                    <td>
                                                        <strong><?= esc($entry['username'] ?? 'System') ?></strong>
                                                        <?php if (!empty($entry['usertype'])): ?>
                                                            <br><small class="text-muted"><?= esc($entry['usertype']) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $actionBadges = [
                                                            'created' => 'success',
                                                            'updated' => 'primary',
                                                            'deleted' => 'danger',
                                                            'status_changed' => 'warning',
                                                            'assigned' => 'info',
                                                            'unassigned' => 'secondary',
                                                        ];
                                                        $badgeClass = $actionBadges[$entry['action']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $badgeClass ?>"><i class="bi bi-record-fill"></i> <?= esc(ucfirst(str_replace('_', ' ', $entry['action']))) ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($entry['field_name'])): ?>
                                                            <code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px;"><?= esc($entry['field_name']) ?></code>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($entry['description'])): ?>
                                                            <div class="mb-1"><?= esc($entry['description']) ?></div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($entry['old_value']) || !empty($entry['new_value'])): ?>
                                                            <div class="small">
                                                                <?php if (!empty($entry['old_value'])): ?>
                                                                    <span class="text-danger"><i class="bi bi-dash-circle"></i> <del><?= esc($entry['old_value']) ?></del></span>
                                                                <?php endif; ?>
                                                                <?php if (!empty($entry['new_value'])): ?>
                                                                    <?php if (!empty($entry['old_value'])): ?>
                                                                        <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                                    <?php endif; ?>
                                                                    <span class="text-success"><i class="bi bi-check-circle"></i> <strong><?= esc($entry['new_value']) ?></strong></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($entry['ip_address'])): ?>
                                                            <div class="small text-muted mt-1">
                                                                <i class="bi bi-geo-alt"></i> IP: <?= esc($entry['ip_address']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> No history records found for this asset.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Panel -->
            <div class="col-lg-3">
                <div class="asset-side-panel">
                    <!-- Asset Image -->
                    <div class="asset-image">
                        <?php if (!empty($asset['device_image'])): ?>
                            <img src="<?= base_url('uploads/devices/' . $asset['device_image']) ?>" alt="device" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 6px;">
                        <?php else: ?>
                            <div class="text-muted"><i class="bi bi-image" style="font-size: 48px; margin-bottom: 10px;"></i><br>No image available</div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions Section -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #0d6efd;">
                        <h6 style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 15px;"><i class="bi bi-lightning"></i> Quick Actions</h6>
                        <a href="<?= site_url('assets/edit/' . $asset['id']) ?>" class="btn btn-warning action-btn" title="Edit this asset"><i class="bi bi-pencil-square"></i> Edit Asset</a>
                        <button id="quickAddPeripheralBtn" class="btn btn-info action-btn" data-bs-toggle="modal" data-bs-target="#addPeripheralModal" type="button" title="Add a new peripheral"><i class="bi bi-plus-square"></i> Add Peripheral</button>
                        <button class="btn btn-primary action-btn" data-bs-toggle="modal" data-bs-target="#addNoteModal" title="Add a note"><i class="bi bi-sticky"></i> Add Note</button>
                        <a href="<?= site_url('assets/delete/' . $asset['id']) ?>" class="btn btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this asset?')" title="Delete this asset"><i class="bi bi-trash"></i> Delete Asset</a>
                    </div>

                    <!-- Status Summary -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #0d6efd; margin-top: 15px;">
                        <h6 style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 12px;"><i class="bi bi-info-circle"></i> Status Summary</h6>
                        <div style="display: grid; gap: 10px;">
                            <div style="padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #0d6efd;">
                                <div style="font-size: 11px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Asset Status</div>
                                <div style="margin-top: 4px;">
                                    <span class="status-badge status-<?= $asset['status'] ?>" style="font-size: 12px;">
                                        <i class="bi bi-flag-fill"></i> <?= ucfirst(str_replace('_', ' ', $asset['status'])) ?>
                                    </span>
                                </div>
                            </div>
                            <div style="padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #0d6efd;">
                                <div style="font-size: 11px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Category</div>
                                <div style="margin-top: 4px; font-weight: 500; color: #212529; font-size: 13px;">
                                    <?= $asset['category'] ?? 'Not Set' ?>
                                </div>
                            </div>
                            <div style="padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #0d6efd;">
                                <div style="font-size: 11px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Assigned To</div>
                                <div style="margin-top: 4px; font-weight: 500; color: #212529; font-size: 13px;">
                                    <?= !empty($asset['assigned_to_user_id']) && isset($assignable_users[$asset['assigned_to_user_id']]) ? $assignable_users[$asset['assigned_to_user_id']] : 'Unassigned' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- View Peripheral Modal -->
        <div class="modal fade" id="viewPeripheralModal" tabindex="-1" aria-labelledby="viewPeripheralModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border: 1px solid #e9ecef; border-top: 4px solid #0d6efd;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="modal-title" id="viewPeripheralModalLabel"><i class="bi bi-cpu"></i> Peripheral Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Type</span>
                                    <span class="detail-value" id="viewPeripheralType">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Status</span>
                                    <span class="detail-value" id="viewPeripheralStatus">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Brand</span>
                                    <span class="detail-value" id="viewPeripheralBrand">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Model</span>
                                    <span class="detail-value" id="viewPeripheralModel">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Model Number</span>
                                    <span class="detail-value" id="viewPeripheralModelNumber">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Serial #</span>
                                    <span class="detail-value" id="viewPeripheralSerial">-</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="detail-row">
                                    <span class="detail-label">Device Image</span>
                                    <span class="detail-value" id="viewPeripheralImage">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Condition</span>
                                    <span class="detail-value" id="viewPeripheralCondition">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Department</span>
                                    <span class="detail-value" id="viewPeripheralDepartment">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Location</span>
                                    <span class="detail-value" id="viewPeripheralLocation">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Workstation</span>
                                    <span class="detail-value" id="viewPeripheralWorkstation">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Assigned To</span>
                                    <span class="detail-value" id="viewPeripheralAssigned">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Purchase Date</span>
                                    <span class="detail-value" id="viewPeripheralPurchaseDate">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Purchase Cost</span>
                                    <span class="detail-value" id="viewPeripheralPurchaseCost">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Order Number</span>
                                    <span class="detail-value" id="viewPeripheralOrderNumber">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Supplier</span>
                                    <span class="detail-value" id="viewPeripheralSupplier">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Vendor</span>
                                    <span class="detail-value" id="viewPeripheralVendor">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Warranty Expiry</span>
                                    <span class="detail-value" id="viewPeripheralWarrantyExpiry">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Quantity</span>
                                    <span class="detail-value" id="viewPeripheralQty">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">Requestable</span>
                                    <span class="detail-value" id="viewPeripheralRequestable">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <span class="detail-label">BYOD</span>
                                    <span class="detail-value" id="viewPeripheralByod">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Peripheral Modal -->
        <div class="modal fade" id="editPeripheralModal" tabindex="-1" aria-labelledby="editPeripheralModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" style="border: 1px solid #e9ecef; border-top: 4px solid #0d6efd;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="modal-title" id="editPeripheralModalLabel"><i class="bi bi-pencil"></i> Edit Peripheral</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPeripheralForm" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="edit_peripheral_type_id" class="form-label">Peripheral Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_peripheral_type_id" name="peripheral_type_id" required>
                                        <option value="">Select Type</option>
                                        <?php foreach ($peripheral_types as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="edit_brand" name="brand">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="edit_model" name="model">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_model_number" class="form-label">Model Number</label>
                                    <input type="text" class="form-control" id="edit_model_number" name="model_number">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control" id="edit_serial_number" name="serial_number">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_department_id" class="form-label">Department</label>
                                    <select class="form-select" id="edit_department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        <?php foreach ($departments as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_location_id" class="form-label">Location</label>
                                    <select class="form-select" id="edit_location_id" name="location_id">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_workstation_id" class="form-label">Workstation</label>
                                    <select class="form-select" id="edit_workstation_id" name="workstation_id">
                                        <option value="">Select Workstation</option>
                                        <?php foreach ($workstations as $id => $code): ?>
                                            <option value="<?= $id ?>"><?= $code ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_assigned_to_user_id" class="form-label">Assigned To</label>
                                    <select class="form-select" id="edit_assigned_to_user_id" name="assigned_to_user_id">
                                        <option value="">Select User</option>
                                        <?php foreach ($assignable_users as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_status" name="status" required>
                                        <option value="available">Available</option>
                                        <option value="in_use">In Use</option>
                                        <option value="standby">Standby</option>
                                        <option value="under_repair">Under Repair</option>
                                        <option value="retired">Retired</option>
                                        <option value="lost">Lost</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_condition_status" class="form-label">Condition <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_condition_status" name="condition_status" required>
                                        <option value="new">New</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_criticality" class="form-label">Criticality <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_criticality" name="criticality" required>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" id="edit_purchase_date" name="purchase_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_purchase_cost" class="form-label">Purchase Cost</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_purchase_cost" name="purchase_cost">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_order_number" class="form-label">Order Number</label>
                                    <input type="text" class="form-control" id="edit_order_number" name="order_number">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_supplier" class="form-label">Supplier</label>
                                    <input type="text" class="form-control" id="edit_supplier" name="supplier">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_qty" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="edit_qty" name="qty">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_vendor" class="form-label">Vendor</label>
                                    <input type="text" class="form-control" id="edit_vendor" name="vendor">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control" id="edit_warranty_expiry" name="warranty_expiry">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_device_image" class="form-label">Device Image</label>
                                    <input type="file" class="form-control" id="edit_device_image" name="device_image" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_requestable" name="requestable" value="1">
                                        <label class="form-check-label" for="edit_requestable">
                                            Requestable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="edit_byod" name="byod" value="1">
                                        <label class="form-check-label" for="edit_byod">
                                            BYOD
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary" form="editPeripheralForm"><i class="bi bi-save"></i> Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Peripheral Modal -->
        <div class="modal fade" id="deletePeripheralModal" tabindex="-1" aria-labelledby="deletePeripheralModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border: 1px solid #e9ecef; border-top: 4px solid #dc3545;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="modal-title" id="deletePeripheralModalLabel"><i class="bi bi-trash"></i> Delete Peripheral</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to delete <strong id="deletePeripheralLabel">this peripheral</strong>?</p>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                        <form id="deletePeripheralForm" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Peripheral Modal -->
        <div class="modal fade" id="addPeripheralModal" tabindex="-1" aria-labelledby="addPeripheralModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" style="border: 1px solid #e9ecef; border-top: 4px solid #198754;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="modal-title" id="addPeripheralModalLabel"><i class="bi bi-plus-square"></i> Add New Peripheral</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="addPeripheralForm" action="<?= site_url('assets/peripheral/store') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="modal_peripheral_type_id" class="form-label">Peripheral Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modal_peripheral_type_id" name="peripheral_type_id" required>
                                        <option value="">Select Type</option>
                                        <?php foreach ($peripheral_types as $id => $name): ?>
                                            <option value="<?= $id ?>" <?= old('peripheral_type_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="modal_brand" name="brand" value="<?= old('brand') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="modal_model" name="model" value="<?= old('model') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_model_number" class="form-label">Model Number</label>
                                    <input type="text" class="form-control" id="modal_model_number" name="model_number" value="<?= old('model_number') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control" id="modal_serial_number" name="serial_number" value="<?= old('serial_number') ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_department_id" class="form-label">Department</label>
                                    <select class="form-select" id="modal_department_id" name="department_id">
                                        <option value="">Select Department</option>
                                        <?php foreach ($departments as $id => $name): ?>
                                            <option value="<?= $id ?>" <?= old('department_id', $asset['department_id'] ?? '') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_location_id" class="form-label">Location</label>
                                    <select class="form-select" id="modal_location_id" name="location_id">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $id => $name): ?>
                                            <option value="<?= $id ?>" <?= old('location_id', $asset['location_id'] ?? '') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_workstation_id" class="form-label">Workstation</label>
                                    <select class="form-select" id="modal_workstation_id" name="workstation_id">
                                        <option value="">Select Workstation</option>
                                        <?php foreach ($workstations as $id => $code): ?>
                                            <option value="<?= $id ?>" <?= old('workstation_id', $asset['workstation_id'] ?? '') == $id ? 'selected' : '' ?>><?= $code ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_assigned_to_user_id" class="form-label">Assigned To</label>
                                    <select class="form-select" id="modal_assigned_to_user_id" name="assigned_to_user_id">
                                        <option value="">Select User</option>
                                        <?php foreach ($assignable_users as $id => $name): ?>
                                            <option value="<?= $id ?>" <?= old('assigned_to_user_id', $asset['assigned_to_user_id'] ?? '') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modal_status" name="status" required>
                                        <option value="available" <?= old('status') == 'available' ? 'selected' : '' ?>>Available</option>
                                        <option value="in_use" <?= old('status') == 'in_use' ? 'selected' : '' ?>>In Use</option>
                                        <option value="standby" <?= old('status') == 'standby' ? 'selected' : '' ?>>Standby</option>
                                        <option value="under_repair" <?= old('status') == 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                                        <option value="retired" <?= old('status') == 'retired' ? 'selected' : '' ?>>Retired</option>
                                        <option value="lost" <?= old('status') == 'lost' ? 'selected' : '' ?>>Lost</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_condition_status" class="form-label">Condition <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modal_condition_status" name="condition_status" required>
                                        <option value="new" <?= old('condition_status') == 'new' ? 'selected' : '' ?>>New</option>
                                        <option value="good" <?= old('condition_status') == 'good' ? 'selected' : '' ?>>Good</option>
                                        <option value="fair" <?= old('condition_status') == 'fair' ? 'selected' : '' ?>>Fair</option>
                                        <option value="damaged" <?= old('condition_status') == 'damaged' ? 'selected' : '' ?>>Damaged</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_criticality" class="form-label">Criticality <span class="text-danger">*</span></label>
                                    <select class="form-select" id="modal_criticality" name="criticality" required>
                                        <option value="low" <?= old('criticality') == 'low' ? 'selected' : '' ?>>Low</option>
                                        <option value="medium" <?= old('criticality') == 'medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="high" <?= old('criticality') == 'high' ? 'selected' : '' ?>>High</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" id="modal_purchase_date" name="purchase_date" value="<?= old('purchase_date') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_purchase_cost" class="form-label">Purchase Cost</label>
                                    <input type="number" step="0.01" class="form-control" id="modal_purchase_cost" name="purchase_cost" value="<?= old('purchase_cost') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_order_number" class="form-label">Order Number</label>
                                    <input type="text" class="form-control" id="modal_order_number" name="order_number" value="<?= old('order_number') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_supplier" class="form-label">Supplier</label>
                                    <input type="text" class="form-control" id="modal_supplier" name="supplier" value="<?= old('supplier') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_qty" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="modal_qty" name="qty" value="<?= old('qty') ?: 1 ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_vendor" class="form-label">Vendor</label>
                                    <input type="text" class="form-control" id="modal_vendor" name="vendor" value="<?= old('vendor') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control" id="modal_warranty_expiry" name="warranty_expiry" value="<?= old('warranty_expiry') ?>">
                                </div>

                                <div class="col-md-3">
                                    <label for="modal_device_image" class="form-label">Device Image</label>
                                    <input type="file" class="form-control" id="modal_device_image" name="device_image" accept="image/*">
                                </div>

                                <div class="col-md-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="modal_requestable" name="requestable" value="1" <?= old('requestable') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="modal_requestable">
                                            Requestable
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="modal_byod" name="byod" value="1" <?= old('byod') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="modal_byod">
                                            BYOD
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                        <button type="submit" class="btn btn-success" form="addPeripheralForm"><i class="bi bi-plus-circle"></i> Add Peripheral</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Note Modal -->
        <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border: 1px solid #e9ecef; border-top: 4px solid #0d6efd;">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="modal-title" id="addNoteModalLabel"><i class="bi bi-sticky"></i> Add Note to Asset #<?= $asset['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addNoteForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                            <div class="mb-3">
                                <label for="note" class="form-label"><i class="bi bi-pencil-square"></i> Note <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="note" name="note" rows="6" required placeholder="Enter your note here..." style="border: 1px solid #e9ecef;"></textarea>
                            </div>
                            <div class="alert alert-danger d-none" id="noteError" role="alert"></div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveNoteBtn"><i class="bi bi-save"></i> Save Note</button>
                    </div>
                </div>
            </div>
        </div>

       
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveNoteBtn = document.getElementById('saveNoteBtn');
            const addNoteForm = document.getElementById('addNoteForm');
            const noteError = document.getElementById('noteError');
            const addNoteModal = document.getElementById('addNoteModal');
            const viewPeripheralModal = document.getElementById('viewPeripheralModal');
            const editPeripheralModal = document.getElementById('editPeripheralModal');
            const deletePeripheralModal = document.getElementById('deletePeripheralModal');

            if (saveNoteBtn && addNoteForm) {
                saveNoteBtn.addEventListener('click', function() {
                    const formData = new FormData(addNoteForm);
                    
                    // Disable button to prevent double submission
                    saveNoteBtn.disabled = true;
                    saveNoteBtn.textContent = 'Saving...';
                    noteError.classList.add('d-none');

                    fetch('<?= site_url('assets/note/add') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal and reload page to show new note
                            const modal = bootstrap.Modal.getInstance(addNoteModal);
                            modal.hide();
                            location.reload();
                        } else {
                            // Show error message
                            noteError.textContent = data.message || 'Failed to add note';
                            noteError.classList.remove('d-none');
                            saveNoteBtn.disabled = false;
                            saveNoteBtn.textContent = 'Save Note';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        noteError.textContent = 'An error occurred. Please try again.';
                        noteError.classList.remove('d-none');
                        saveNoteBtn.disabled = false;
                        saveNoteBtn.textContent = 'Save Note';
                    });
                });
            }

            if (viewPeripheralModal) {
                viewPeripheralModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (!button) return;
                    viewPeripheralModal.querySelector('#viewPeripheralType').textContent = button.dataset.peripheralType || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralBrand').textContent = button.dataset.brand || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralModel').textContent = button.dataset.model || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralModelNumber').textContent = button.dataset.modelNumber || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralSerial').textContent = button.dataset.serial || '-';
                    
                    // Handle device image
                    const imageElement = viewPeripheralModal.querySelector('#viewPeripheralImage');
                    if (button.dataset.deviceImage) {
                        imageElement.innerHTML = '<img src="' + button.dataset.deviceImage + '" alt="Device" style="max-width: 200px; max-height: 150px; border-radius: 4px;">';
                    } else {
                        imageElement.textContent = '-';
                    }
                    
                    viewPeripheralModal.querySelector('#viewPeripheralStatus').textContent = button.dataset.status || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralCondition').textContent = button.dataset.condition || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralDepartment').textContent = button.dataset.department || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralLocation').textContent = button.dataset.location || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralWorkstation').textContent = button.dataset.workstation || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralAssigned').textContent = button.dataset.assigned || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralPurchaseDate').textContent = button.dataset.purchaseDate || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralPurchaseCost').textContent = button.dataset.purchaseCost || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralOrderNumber').textContent = button.dataset.orderNumber || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralSupplier').textContent = button.dataset.supplier || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralVendor').textContent = button.dataset.vendor || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralWarrantyExpiry').textContent = button.dataset.warrantyExpiry || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralQty').textContent = button.dataset.qty || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralRequestable').textContent = button.dataset.requestable || '-';
                    viewPeripheralModal.querySelector('#viewPeripheralByod').textContent = button.dataset.byod || '-';
                });
            }

            if (editPeripheralModal) {
                editPeripheralModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (!button) return;
                    const peripheralId = button.dataset.peripheralId;
                    const form = document.getElementById('editPeripheralForm');
                    if (form && peripheralId) {
                        form.action = `<?= site_url('assets/peripheral/update') ?>/${peripheralId}`;
                    }
                    const setValue = (id, value) => {
                        const el = document.getElementById(id);
                        if (el) el.value = value || '';
                    };
                    const setCheckbox = (id, value) => {
                        const el = document.getElementById(id);
                        if (el) el.checked = value == '1' || value == 1;
                    };
                    setValue('edit_peripheral_type_id', button.dataset.peripheralTypeId);
                    setValue('edit_brand', button.dataset.brand);
                    setValue('edit_model', button.dataset.model);
                    setValue('edit_model_number', button.dataset.modelNumber);
                    setValue('edit_serial_number', button.dataset.serial);
                    setValue('edit_department_id', button.dataset.departmentId && button.dataset.departmentId !== '0' ? button.dataset.departmentId : '');
                    setValue('edit_location_id', button.dataset.locationId && button.dataset.locationId !== '0' ? button.dataset.locationId : '');
                    setValue('edit_workstation_id', button.dataset.workstationId && button.dataset.workstationId !== '0' ? button.dataset.workstationId : '');
                    setValue('edit_assigned_to_user_id', button.dataset.assignedId && button.dataset.assignedId !== '0' ? button.dataset.assignedId : '');
                    setValue('edit_status', button.dataset.status);
                    setValue('edit_condition_status', button.dataset.condition);
                    setValue('edit_criticality', button.dataset.criticality);
                    setValue('edit_purchase_date', button.dataset.purchaseDate);
                    setValue('edit_purchase_cost', button.dataset.purchaseCost);
                    setValue('edit_order_number', button.dataset.orderNumber);
                    setValue('edit_supplier', button.dataset.supplier);
                    setValue('edit_qty', button.dataset.qty);
                    setValue('edit_vendor', button.dataset.vendor);
                    setValue('edit_warranty_expiry', button.dataset.warrantyExpiry);
                    setCheckbox('edit_requestable', button.dataset.requestable);
                    setCheckbox('edit_byod', button.dataset.byod);
                });
            }

            if (deletePeripheralModal) {
                deletePeripheralModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (!button) return;
                    const peripheralId = button.dataset.peripheralId;
                    const label = button.dataset.peripheralLabel || 'this peripheral';
                    const labelEl = deletePeripheralModal.querySelector('#deletePeripheralLabel');
                    const form = document.getElementById('deletePeripheralForm');
                    if (labelEl) labelEl.textContent = label.trim();
                    if (form && peripheralId) {
                        form.action = `<?= site_url('assets/peripheral/delete') ?>/${peripheralId}`;
                    }
                });
            }
        });
    </script>
</body>
</html>
