<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Details</title>
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
        .detail-card { background-color: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .detail-card h4 { color: #212529; font-weight: 600; margin-bottom: 16px; font-size: 18px; border-bottom: 2px solid #0d6efd; padding-bottom: 8px; display: flex; align-items: center; }
        .detail-card h4 i { margin-right: 10px; color: #0d6efd; }
        .record-item { background: #f8f9fa; padding: 12px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #0d6efd; }
        .record-item-title { font-weight: 600; color: #212529; }
        .record-item-meta { font-size: 12px; color: #6c757d; margin-top: 4px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Document Details']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('documents') ?>" title="Go to Documents">Document Management</a>
            <span class="separator">›</span>
            <span class="current"><?= esc($document['title']) ?></span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="detail-card">
                    <h4><i class="bi bi-file-earmark-text"></i> Document Information</h4>
                    <div class="mb-3">
                        <strong>Title:</strong><br>
                        <?= esc($document['title']) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Subject:</strong><br>
                        <?= esc($document['subject'] ?? 'N/A') ?>
                    </div>
                    <div class="mb-0">
                        <strong>Description:</strong><br>
                        <?= !empty($document['description']) ? nl2br(esc($document['description'])) : 'N/A' ?>
                    </div>
                </div>

                <div class="detail-card">
                    <h4><i class="bi bi-journal-text"></i> Notes</h4>

                    <form action="<?= site_url('documents/note/store/' . $document['id']) ?>" method="post" class="mb-3">
                        <?= csrf_field() ?>
                        <div class="mb-2">
                            <textarea name="note" class="form-control" rows="3" placeholder="Add a note..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Note
                        </button>
                    </form>

                    <?php if (!empty($notes)): ?>
                        <?php foreach ($notes as $note): ?>
                            <div class="record-item">
                                <div class="record-item-title"><?= nl2br(esc($note['note'])) ?></div>
                                <div class="record-item-meta">
                                    By <?= esc($note['username'] ?? 'Unknown') ?>
                                    • <?= !empty($note['created_at']) ? esc(date('M d, Y h:i A', strtotime($note['created_at']))) : 'N/A' ?>
                                    <a href="<?= site_url('documents/note/delete/' . $document['id'] . '/' . $note['id']) ?>" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="return confirm('Delete this note?')">Delete</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No notes yet.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h4><i class="bi bi-paperclip"></i> File Uploads</h4>

                    <form action="<?= site_url('documents/file/upload/' . $document['id']) ?>" method="post" enctype="multipart/form-data" class="mb-3">
                        <?= csrf_field() ?>
                        <div class="mb-2">
                            <input type="file" name="files[]" class="form-control" multiple required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-upload"></i> Upload File(s)
                        </button>
                    </form>

                    <?php if (!empty($files)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Uploaded</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($files as $file): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url($file['file_path']) ?>" target="_blank"><?= esc($file['original_name']) ?></a>
                                            </td>
                                            <td><?= esc($file['mime_type'] ?? 'N/A') ?></td>
                                            <td><?= !empty($file['file_size']) ? esc(number_format($file['file_size'] / 1024, 2)) . ' KB' : 'N/A' ?></td>
                                            <td><?= !empty($file['created_at']) ? esc(date('M d, Y h:i A', strtotime($file['created_at']))) : 'N/A' ?></td>
                                            <td>
                                                <a href="<?= site_url('documents/file/delete/' . $document['id'] . '/' . $file['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this file?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No files uploaded yet.</p>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 style="margin: 0; padding: 0; border: none;">
                            <i class="bi bi-calendar-event"></i> Alerts
                        </h4>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addAlertModal">
                            <i class="bi bi-plus-circle"></i> Add Alert
                        </button>
                    </div>

                    <?php if (!empty($alerts)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Description</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alerts as $alert): ?>
                                        <tr>
                                            <td><?= esc(date('M d, Y', strtotime($alert['alert_date']))) ?></td>
                                            <td><?= !empty($alert['alert_time']) ? esc(date('h:i A', strtotime($alert['alert_time']))) : 'All day' ?></td>
                                            <td><?= !empty($alert['description']) ? htmlspecialchars(substr($alert['description'], 0, 50)) : 'N/A' ?><?= strlen($alert['description'] ?? '') > 50 ? '...' : '' ?></td>
                                            <td>
                                                <a href="<?= site_url('documents/alert/delete/' . $document['id'] . '/' . $alert['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this alert?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No alerts set yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="detail-card">
                    <h4><i class="bi bi-info-circle"></i> Metadata</h4>
                    <div class="mb-2"><strong>ID:</strong> #<?= esc($document['id']) ?></div>
                    <div class="mb-2"><strong>Created By:</strong> <?= esc($document['created_by_name'] ?? 'N/A') ?></div>
                    <div class="mb-2"><strong>Created At:</strong> <?= !empty($document['created_at']) ? esc(date('M d, Y h:i A', strtotime($document['created_at']))) : 'N/A' ?></div>
                    <div class="mb-3"><strong>Updated At:</strong> <?= !empty($document['updated_at']) ? esc(date('M d, Y h:i A', strtotime($document['updated_at']))) : 'N/A' ?></div>

                    <a href="<?= site_url('documents/edit/' . $document['id']) ?>" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil-square"></i> Edit Document
                    </a>
                    <a href="<?= site_url('documents') ?>" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <!-- Add Alert Modal -->
    <div class="modal fade" id="addAlertModal" tabindex="-1" aria-labelledby="addAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlertModalLabel">
                        <i class="bi bi-calendar-plus"></i> Add Alert
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= site_url('documents/alert/store/' . $document['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alert_date" class="form-label">Alert Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="alert_date" name="alert_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="alert_time" class="form-label">Alert Time (Optional)</label>
                            <input type="time" class="form-control" id="alert_time" name="alert_time">
                            <small class="text-muted">Leave blank for all-day alert</small>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter alert details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Create Alert
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
