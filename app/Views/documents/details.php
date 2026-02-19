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
        .document-nav .nav-link { color: #495057; }
        .document-nav .nav-link.active { font-weight: 600; }
        .file-hover-preview {
            position: fixed;
            z-index: 1080;
            width: 260px;
            max-height: 220px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 8px;
            display: none;
            pointer-events: none;
        }
        .file-hover-preview .preview-title {
            font-size: 12px;
            color: #495057;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-hover-preview .preview-body {
            width: 100%;
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 6px;
            overflow: hidden;
        }
        .file-hover-preview .preview-body img,
        .file-hover-preview .preview-body iframe,
        .file-hover-preview .preview-body video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border: 0;
        }
        .file-modal-preview {
            width: 100%;
            min-height: 420px;
            max-height: 65vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }
        .file-modal-preview img,
        .file-modal-preview iframe,
        .file-modal-preview video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border: 0;
        }
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
                <ul class="nav nav-tabs document-nav mb-3" id="documentDetailsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#information-pane" type="button" role="tab" aria-controls="information-pane" aria-selected="true">
                            <i class="bi bi-file-earmark-text"></i> Document Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes-pane" type="button" role="tab" aria-controls="notes-pane" aria-selected="false">
                            <i class="bi bi-journal-text"></i> Notes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files-pane" type="button" role="tab" aria-controls="files-pane" aria-selected="false">
                            <i class="bi bi-paperclip"></i> File Uploads
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="alerts-tab" data-bs-toggle="tab" data-bs-target="#alerts-pane" type="button" role="tab" aria-controls="alerts-pane" aria-selected="false">
                            <i class="bi bi-calendar-event"></i> Alerts
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="documentDetailsTabContent">
                    <div class="tab-pane fade show active" id="information-pane" role="tabpanel" aria-labelledby="information-tab" tabindex="0">
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
                            <div class="mb-0 mt-3">
                                <strong>Details:</strong><br>
                                <?= !empty($document['details']) ? $document['details'] : 'N/A' ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="notes-pane" role="tabpanel" aria-labelledby="notes-tab" tabindex="0">
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
                    </div>

                    <div class="tab-pane fade" id="files-pane" role="tabpanel" aria-labelledby="files-tab" tabindex="0">
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
                                                <?php
                                                    $fileUrl = base_url($file['file_path']);
                                                    $mimeType = strtolower($file['mime_type'] ?? '');
                                                    $fileName = $file['original_name'] ?? '';
                                                    $previewType = 'other';
                                                    if (strpos($mimeType, 'image/') === 0) {
                                                        $previewType = 'image';
                                                    } elseif ($mimeType === 'application/pdf' || preg_match('/\.pdf$/i', $fileName)) {
                                                        $previewType = 'pdf';
                                                    } elseif (strpos($mimeType, 'video/') === 0) {
                                                        $previewType = 'video';
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="<?= $fileUrl ?>"
                                                            class="file-preview-link"
                                                            data-preview-url="<?= esc($fileUrl, 'attr') ?>"
                                                            data-preview-type="<?= esc($previewType, 'attr') ?>"
                                                            data-preview-name="<?= esc($fileName, 'attr') ?>"
                                                            data-preview-mime="<?= esc($file['mime_type'] ?? '', 'attr') ?>"
                                                        ><?= esc($file['original_name']) ?></a>
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
                    </div>

                    <div class="tab-pane fade" id="alerts-pane" role="tabpanel" aria-labelledby="alerts-tab" tabindex="0">
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

    <div id="fileHoverPreview" class="file-hover-preview" aria-hidden="true">
        <div class="preview-title"></div>
        <div class="preview-body"></div>
    </div>

    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="fileModalPreviewBody" class="file-modal-preview"></div>
                </div>
                <div class="modal-footer">
                    <a id="fileModalDownloadBtn" href="#" class="btn btn-primary" download>
                        <i class="bi bi-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
    <script>
        (function () {
            const previewBox = document.getElementById('fileHoverPreview');
            if (!previewBox) return;
            const modalElement = document.getElementById('filePreviewModal');
            const modalPreviewBody = document.getElementById('fileModalPreviewBody');
            const modalTitle = document.getElementById('filePreviewModalLabel');
            const modalDownloadBtn = document.getElementById('fileModalDownloadBtn');
            const filePreviewModal = modalElement ? new bootstrap.Modal(modalElement) : null;

            const titleEl = previewBox.querySelector('.preview-title');
            const bodyEl = previewBox.querySelector('.preview-body');
            let activeLink = null;

            function setPosition(event) {
                const offsetX = 16;
                const offsetY = 16;
                const previewWidth = 260;
                const previewHeight = 220;
                let left = event.clientX + offsetX;
                let top = event.clientY + offsetY;

                if (left + previewWidth > window.innerWidth) {
                    left = event.clientX - previewWidth - offsetX;
                }
                if (top + previewHeight > window.innerHeight) {
                    top = window.innerHeight - previewHeight - 8;
                }

                previewBox.style.left = left + 'px';
                previewBox.style.top = Math.max(8, top) + 'px';
            }

            function buildPreview(link) {
                const previewUrl = link.dataset.previewUrl || '';
                const previewType = link.dataset.previewType || 'other';
                const previewName = link.dataset.previewName || 'File preview';
                const previewMime = link.dataset.previewMime || '';

                titleEl.textContent = previewName;
                bodyEl.innerHTML = '';

                if (previewType === 'image') {
                    const img = document.createElement('img');
                    img.src = previewUrl;
                    img.alt = previewName;
                    bodyEl.appendChild(img);
                    return;
                }

                if (previewType === 'pdf') {
                    const frame = document.createElement('iframe');
                    frame.src = previewUrl;
                    frame.title = previewName;
                    bodyEl.appendChild(frame);
                    return;
                }

                if (previewType === 'video') {
                    const video = document.createElement('video');
                    video.muted = true;
                    video.controls = false;
                    video.preload = 'metadata';
                    const source = document.createElement('source');
                    source.src = previewUrl;
                    source.type = previewMime;
                    video.appendChild(source);
                    bodyEl.appendChild(video);
                    return;
                }

                const fallback = document.createElement('div');
                fallback.className = 'text-muted small text-center px-2';
                fallback.innerHTML = '<i class="bi bi-file-earmark"></i><br>No preview available';
                bodyEl.appendChild(fallback);
            }

            function buildModalPreview(link) {
                if (!modalPreviewBody || !modalTitle || !modalDownloadBtn) return;

                const previewUrl = link.dataset.previewUrl || '';
                const previewType = link.dataset.previewType || 'other';
                const previewName = link.dataset.previewName || 'File Preview';
                const previewMime = link.dataset.previewMime || '';

                modalTitle.textContent = previewName;
                modalPreviewBody.innerHTML = '';
                modalDownloadBtn.href = previewUrl;
                modalDownloadBtn.setAttribute('download', previewName);

                if (previewType === 'image') {
                    const img = document.createElement('img');
                    img.src = previewUrl;
                    img.alt = previewName;
                    modalPreviewBody.appendChild(img);
                    return;
                }

                if (previewType === 'pdf') {
                    const frame = document.createElement('iframe');
                    frame.src = previewUrl;
                    frame.title = previewName;
                    modalPreviewBody.appendChild(frame);
                    return;
                }

                if (previewType === 'video') {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.preload = 'metadata';
                    const source = document.createElement('source');
                    source.src = previewUrl;
                    source.type = previewMime;
                    video.appendChild(source);
                    modalPreviewBody.appendChild(video);
                    return;
                }

                const fallback = document.createElement('div');
                fallback.className = 'text-muted text-center px-2';
                fallback.innerHTML = '<i class="bi bi-file-earmark" style="font-size: 2rem;"></i><br>Preview not available for this file type.';
                modalPreviewBody.appendChild(fallback);
            }

            function showPreview(event) {
                const link = event.currentTarget;
                activeLink = link;
                buildPreview(link);
                previewBox.style.display = 'block';
                previewBox.setAttribute('aria-hidden', 'false');
                setPosition(event);
            }

            function hidePreview() {
                activeLink = null;
                previewBox.style.display = 'none';
                previewBox.setAttribute('aria-hidden', 'true');
                bodyEl.innerHTML = '';
            }

            document.querySelectorAll('.file-preview-link').forEach(function (link) {
                link.addEventListener('mouseenter', showPreview);
                link.addEventListener('mousemove', setPosition);
                link.addEventListener('mouseleave', hidePreview);
                link.addEventListener('blur', hidePreview);
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    hidePreview();
                    buildModalPreview(link);
                    if (filePreviewModal) {
                        filePreviewModal.show();
                    }
                });
            });

            window.addEventListener('scroll', function () {
                if (activeLink) {
                    hidePreview();
                }
            }, true);
        })();
    </script>
</body>
</html>
