<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peripheral Details</title>
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
        .status-available { background-color: #d1e7dd; color: #0f5132; }
        .status-in_use { background-color: #cfe2ff; color: #084298; }
        .status-standby { background-color: #fff3cd; color: #856404; }
        .status-under_repair { background-color: #ffeaa7; color: #cc5500; }
        .status-retired { background-color: #e2e3e5; color: #383d41; }
        .status-lost { background-color: #f8d7da; color: #842029; }
        
        .peripheral-side-panel { background: white; border-radius: 8px; padding: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-top: 75px; }
        .peripheral-image { background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 20px; min-height: 250px; display: flex; align-items: center; justify-content: center; }
        .peripheral-image img { max-width: 100%; max-height: 240px; object-fit: contain; }
        .peripheral-image .placeholder { color: #adb5bd; font-size: 14px; }
        
        .action-btn { width: 100%; margin-bottom: 12px; }
        .action-btn i { margin-right: 8px; }
        
        .nav-tabs { border-bottom: 2px solid #e9ecef; }
        .nav-tabs .nav-link { color: #495057; border: none; border-bottom: 3px solid transparent; padding: 12px 16px; font-weight: 500; }
        .nav-tabs .nav-link:hover { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }
        
        .note-item { background: #f8f9fa; border-left: 4px solid #0d6efd; border-radius: 6px; padding: 16px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Peripheral Details']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('peripherals') ?>" title="Go to Peripherals">Peripherals</a>
            <span class="separator">›</span>
            <span class="current"><?= !empty($peripheral['model']) ? esc($peripheral['model']) : 'Peripheral Details' ?></span>
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
                <ul class="nav nav-tabs" id="peripheralDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-info-btn" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab" aria-controls="tab-info" aria-selected="true"><i class="bi bi-info-circle"></i> Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-notes-btn" data-bs-toggle="tab" data-bs-target="#tab-notes" type="button" role="tab" aria-controls="tab-notes" aria-selected="false"><i class="bi bi-sticky"></i> Notes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab" aria-controls="tab-history" aria-selected="false"><i class="bi bi-clock-history"></i> History</button>
                    </li>
                </ul>
                
                <div class="tab-content mt-4" id="peripheralDetailsTabsContent">
                    <div class="tab-pane fade show active" id="tab-info" role="tabpanel" aria-labelledby="tab-info-btn">
                        <!-- Basic Information Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-tag"></i> Basic Information</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-cpu"></i> Peripheral Type</span>
                                        <span class="detail-value"><?= $peripheral_type_name ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-building"></i> Brand</span>
                                        <span class="detail-value"><?= $peripheral['brand'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-laptop"></i> Model</span>
                                        <span class="detail-value"><?= $peripheral['model'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-key"></i> Model Number</span>
                                        <span class="detail-value"><?= $peripheral['model_number'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-hash"></i> Serial Number</span>
                                        <span class="detail-value"><?= $peripheral['serial_number'] ?? '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-flag-fill"></i> Status</span>
                                        <span class="status-badge status-<?= $peripheral['status'] ?>">
                                            <i class="bi bi-record-fill"></i> <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-gear"></i> Condition</span>
                                        <span class="status-badge" style="background-color: #e9ecef; color: #495057;">
                                            <i class="bi bi-tools"></i> <?= ucfirst($peripheral['condition_status']) ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-exclamation-triangle"></i> Criticality</span>
                                        <span class="status-badge" style="background-color: #9c27b0; color: white;">
                                            <i class="bi bi-lightning-fill"></i> <?= ucfirst($peripheral['criticality']) ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-box"></i> Quantity</span>
                                        <span class="detail-value"><?= $peripheral['qty'] ?? '1' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Information Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-currency-dollar"></i> Purchase Information</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-calendar-event"></i> Purchase Date</span>
                                        <span class="detail-value"><?= !empty($peripheral['purchase_date']) ? date('M d, Y', strtotime($peripheral['purchase_date'])) : '<span class="text-muted">Not set</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-currency-dollar"></i> Purchase Cost</span>
                                        <span class="detail-value"><?= !empty($peripheral['purchase_cost']) ? '₱' . number_format($peripheral['purchase_cost'], 2) : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-receipt"></i> Order Number</span>
                                        <span class="detail-value"><?= !empty($peripheral['order_number']) ? $peripheral['order_number'] : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-shop"></i> Supplier</span>
                                        <span class="detail-value"><?= !empty($peripheral['supplier']) ? $peripheral['supplier'] : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-truck"></i> Vendor</span>
                                        <span class="detail-value"><?= !empty($peripheral['vendor']) ? $peripheral['vendor'] : '<span class="text-muted">-</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-shield-check"></i> Warranty Expiry</span>
                                        <span class="detail-value"><?= !empty($peripheral['warranty_expiry']) ? date('M d, Y', strtotime($peripheral['warranty_expiry'])) : '<span class="text-muted">Not set</span>' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location & Assignment Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-geo-alt"></i> Location & Assignment</h3>
                            
                            <div class="info-grid">
                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-building"></i> Department</span>
                                        <span class="detail-value"><?= $department_name ?? '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-pin-map"></i> Location</span>
                                        <span class="detail-value"><?= $location_name ?? '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-house-door"></i> Workstation</span>
                                        <span class="detail-value"><?= $workstation_code ?? '<span class="text-muted">Not assigned</span>' ?></span>
                                    </div>
                                </div>

                                <div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-person-badge"></i> Assigned To</span>
                                        <span class="detail-value"><?= $assigned_user_name ?? '<span class="text-muted">Unassigned</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-hand-thumbs-up"></i> Requestable</span>
                                        <span class="detail-value"><?= !empty($peripheral['requestable']) ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label"><i class="bi bi-phone"></i> BYOD</span>
                                        <span class="detail-value"><?= !empty($peripheral['byod']) ? '<span class="badge bg-info"><i class="bi bi-check-circle"></i> Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps Card -->
                        <div class="detail-card">
                            <h3><i class="bi bi-clock"></i> Timestamps</h3>
                            
                            <div class="info-grid">
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-plus-circle"></i> Created At</span>
                                    <span class="detail-value"><?= date('M d, Y \a\t h:i A', strtotime($peripheral['created_at'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-arrow-repeat"></i> Updated At</span>
                                    <span class="detail-value"><?= date('M d, Y \a\t h:i A', strtotime($peripheral['updated_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Tab Content -->
                    <div class="tab-pane fade" id="tab-notes" role="tabpanel" aria-labelledby="tab-notes-btn">
                        <div class="detail-card">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0"><i class="bi bi-sticky"></i> Peripheral Notes</h3>
                                <button class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Add Note
                                </button>
                            </div>

                            <div class="alert alert-info"><i class="bi bi-info-circle"></i> Notes functionality will be available soon.</div>
                        </div>
                    </div>

                    <!-- History Tab Content -->
                    <div class="tab-pane fade" id="tab-history" role="tabpanel" aria-labelledby="tab-history-btn">
                        <div class="detail-card">
                            <h3><i class="bi bi-clock-history"></i> Change History</h3>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> History tracking will be available soon.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Panel -->
            <div class="col-lg-3">
                <div class="peripheral-side-panel">
                    <!-- Peripheral Image -->
                    <div class="peripheral-image">
                        <?php if (!empty($peripheral['device_image'])): ?>
                            <img src="<?= base_url('uploads/devices/' . $peripheral['device_image']) ?>" alt="device" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 6px;">
                        <?php else: ?>
                            <div class="text-muted"><i class="bi bi-image" style="font-size: 48px; margin-bottom: 10px;"></i><br>No image available</div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions Section -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #0d6efd;">
                        <h6 style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #6c757d; margin-bottom: 15px;"><i class="bi bi-lightning"></i> Quick Actions</h6>
                        <a href="<?= site_url('peripherals/edit/' . $peripheral['id']) ?>" class="btn btn-warning action-btn" title="Edit this peripheral"><i class="bi bi-pencil-square"></i> Edit Peripheral</a>
                        <button class="btn btn-primary action-btn" data-bs-toggle="tab" data-bs-target="#tab-notes" type="button" title="Add a note"><i class="bi bi-sticky"></i> Add Note</button>
                        <a href="<?= site_url('peripherals/delete/' . $peripheral['id']) ?>" class="btn btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this peripheral?')" title="Delete this peripheral"><i class="bi bi-trash"></i> Delete Peripheral</a>
                    </div>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
