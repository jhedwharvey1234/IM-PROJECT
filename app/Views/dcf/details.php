<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCF Details - <?= esc($dcf['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .breadcrumb-nav { padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .info-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-box h3 { font-size: 2.5rem; margin: 0; }
        .stat-box p { margin: 10px 0 0 0; }
        .share-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 2px dashed #dee2e6; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        #qrcode { display: inline-block; padding: 10px; background: white; border-radius: 8px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'DCF Details']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('dcf') ?>">DCF Management</a>
            <span class="separator">›</span>
            <span class="current">Details</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="bi bi-file-earmark-text"></i> <?= esc($dcf['title']) ?></h3>
            <a href="<?= site_url('dcf/edit/' . $dcf['id']) ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Edit DCF
            </a>
        </div>

        <ul class="nav nav-tabs mb-3" id="dcfDetailsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="bi bi-info-circle"></i> Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab">
                    <i class="bi bi-bar-chart"></i> Results (<?= $responseCount ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dcfDetailsTabContent">
            <!-- Information Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="info-card">
                            <h5 class="mb-3"><i class="bi bi-card-text"></i> DCF Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="200">Title:</th>
                                    <td><?= esc($dcf['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td><?= esc($dcf['description'] ?: 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td><?= esc($department['department_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Due Date:</th>
                                    <td><span class="badge bg-warning"><?= esc($dcf['due_date']) ?></span></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td><?= esc($dcf['created_at']) ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="info-card">
                            <h5 class="mb-3"><i class="bi bi-question-circle"></i> Questions (<?= count($questions) ?>)</h5>
                            <?php if (!empty($parts)): ?>
                                <?php foreach ($parts as $partIndex => $part): ?>
                                    <div class="mb-3 p-3 border rounded bg-light">
                                        <h6 class="mb-1"><?= ($partIndex + 1) ?>. <?= esc($part['title'] ?? 'Part') ?></h6>
                                        <?php if (!empty($part['description'])): ?>
                                            <p class="text-muted mb-3"><?= esc($part['description']) ?></p>
                                        <?php endif; ?>

                                        <?php if (!empty($part['questions'])): ?>
                                            <?php foreach ($part['questions'] as $index => $question): ?>
                                                <div class="mb-2 p-2 border rounded bg-white">
                                                    <strong><?= ($index + 1) ?>. <?= esc($question['question_text']) ?></strong>
                                                    <?php if (!empty($question['is_required'])): ?>
                                                        <span class="badge bg-danger">Required</span>
                                                    <?php endif; ?>
                                                    <br>
                                                    <small class="text-muted">Type: <?= esc($question['answer_type']) ?></small>
                                                    <?php if (isset($question['options']) && count($question['options']) > 0): ?>
                                                        <ul class="mb-0 mt-2">
                                                            <?php foreach ($question['options'] as $option): ?>
                                                                <li><?= esc($option['option_text']) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">No questions in this part.</p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No questions added yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-box mb-3">
                            <h3><?= $responseCount ?></h3>
                            <p><i class="bi bi-people"></i> Total Responses</p>
                        </div>

                        <div class="info-card">
                            <h5 class="mb-3"><i class="bi bi-share"></i> Share DCF</h5>
                            <div class="share-box text-center mb-3">
                                <div id="qrcode" class="mb-3"></div>
                                <p class="mb-2"><strong>Scan QR Code</strong></p>
                            </div>
                            
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="shareUrl" value="<?= $shareUrl ?>" readonly>
                                <button class="btn btn-primary" onclick="copyToClipboard()">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="<?= $shareUrl ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> Open Form
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Tab -->
            <div class="tab-pane fade" id="results" role="tabpanel">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="stat-box" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <h3><?= $responseCount ?></h3>
                            <p>Total Respondents</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box" style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);">
                            <h3><?= count($questions) ?></h3>
                            <p>Questions</p>
                        </div>
                    </div>
                </div>

                <?php if ($responseCount > 0): ?>
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-table"></i> Response List</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($responses as $idx => $response): ?>
                                        <tr>
                                            <td><?= $idx + 1 ?></td>
                                            <td><?= esc($response['respondent_name']) ?></td>
                                            <td><?= esc($response['respondent_mobile'] ?: 'N/A') ?></td>
                                            <td><?= esc($response['respondent_email'] ?: 'N/A') ?></td>
                                            <td><?= esc($response['submitted_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="bi bi-bar-chart"></i> Question Analytics</h5>
                    <?php foreach ($analytics as $questionId => $data): ?>
                        <div class="chart-container">
                            <h6><?= esc($data['question']['question_text']) ?></h6>
                            <small class="text-muted">Type: <?= esc($data['question']['answer_type']) ?></small>
                            
                            <?php if (is_array($data['summary']) && count($data['summary']) > 0): ?>
                                <?php if (array_keys($data['summary'])[0] !== 0): ?>
                                    <!-- Multiple Choice / Checkbox / Dropdown -->
                                    <table class="table table-sm mt-3">
                                        <thead>
                                            <tr>
                                                <th>Answer</th>
                                                <th width="100">Count</th>
                                                <th width="200">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['summary'] as $answer => $count): ?>
                                                <?php $percentage = round(($count / $responseCount) * 100, 1); ?>
                                                <tr>
                                                    <td><?= esc($answer) ?></td>
                                                    <td><?= $count ?></td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%">
                                                                <?= $percentage ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <!-- Short Answer / Paragraph -->
                                    <div class="mt-3">
                                        <ul class="list-group">
                                            <?php foreach ($data['summary'] as $answer): ?>
                                                <li class="list-group-item"><?= esc($answer) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted mt-2">No responses yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No responses received yet. Share the DCF link to start collecting responses!
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate QR Code
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $shareUrl ?>",
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        function copyToClipboard() {
            const input = document.getElementById('shareUrl');
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        }
    </script>
</body>
</html>
