<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCF Details - <?= esc($dcf['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        .chart-and-table-wrapper { display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap; }
        .chart-wrapper { flex: 0 0 450px; position: relative; height: 400px; }
        .chart-canvas-wrapper { position: relative; height: 100%; }
        .stats-table-wrapper { flex: 1; min-width: 300px; }
        .stats-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px; }
        .stat-item { background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; }
        .stat-item-value { font-size: 1.8rem; font-weight: 700; color: #667eea; }
        #qrcode { display: inline-block; padding: 10px; background: white; border-radius: 8px; }
        
        /* WYSIWYG Content Display */
        .wysiwyg-content { line-height: 1.6; font-size: 14px; }
        .wysiwyg-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .wysiwyg-content p { margin-bottom: 12px; }
        .wysiwyg-content ul, .wysiwyg-content ol { margin: 10px 0; padding-left: 25px; }
        .wysiwyg-content table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .wysiwyg-content table td, .wysiwyg-content table th { border: 1px solid #dee2e6; padding: 8px; }
        .wysiwyg-content table th { background: #f8f9fa; font-weight: 600; }
        .wysiwyg-content blockquote { border-left: 4px solid #667eea; padding-left: 15px; margin: 15px 0; color: #6c757d; font-style: italic; }
        .wysiwyg-content a { color: #0d6efd; text-decoration: none; }
        .wysiwyg-content a:hover { text-decoration: underline; }
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
                                    <!-- Multiple Choice / Checkbox / Dropdown - Show as Charts -->
                                    <?php
                                        $answers = array_keys($data['summary']);
                                        $counts = array_values($data['summary']);
                                        $total = array_sum($counts);
                                        $percentages = array_map(fn($c) => round(($c / $total) * 100, 1), $counts);
                                        $chartId = 'chart_' . $questionId;
                                        $chartDataJson = json_encode([
                                            'labels' => $answers,
                                            'data' => $counts,
                                            'percentages' => $percentages,
                                            'type' => $data['question']['answer_type']
                                        ]);
                                    ?>
                                    
                                    <!-- Chart and Table Side by Side -->
                                    <div class="chart-and-table-wrapper">
                                        <!-- Pie Chart -->
                                        <div class="chart-wrapper">
                                            <div class="chart-canvas-wrapper">
                                                <canvas id="<?= $chartId ?>"></canvas>
                                            </div>
                                        </div>
                                        
                                        <!-- Statistics Table -->
                                        <div class="stats-table-wrapper">
                                            <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Answer</th>
                                                <th width="80" class="text-center">Count</th>
                                                <th width="100" class="text-center">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['summary'] as $answer => $count): ?>
                                                <?php $pct = round(($count / $total) * 100, 1); ?>
                                                <tr>
                                                    <td><?= esc($answer) ?></td>
                                                    <td class="text-center"><strong><?= $count ?></strong></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary"><?= $pct ?>%</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const data = <?= $chartDataJson ?>;
                                            const ctx = document.getElementById('<?= $chartId ?>').getContext('2d');
                                            
                                            // Generate colors
                                            const colors = [
                                                '#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe',
                                                '#43e97b', '#38f9d7', '#fa709a', '#fee140', '#30cfd0',
                                                '#a8edea', '#fed6e3', '#ff7675', '#74b9ff', '#81ecec',
                                                '#ff9ff3', '#feca57', '#48dbfb', '#ff6b6b', '#1dd1a1'
                                            ];
                                            const backgroundColor = colors.slice(0, data.labels.length);
                                            const hoverColors = backgroundColor.map(color => {
                                                // Brighten on hover
                                                return color + 'dd';
                                            });
                                            
                                            new Chart(ctx, {
                                                type: 'doughnut',
                                                data: {
                                                    labels: data.labels,
                                                    datasets: [{
                                                        label: 'Responses',
                                                        data: data.data,
                                                        backgroundColor: backgroundColor,
                                                        borderColor: '#ffffff',
                                                        borderWidth: 3,
                                                        hoverBackgroundColor: hoverColors,
                                                        hoverBorderColor: '#ffffff',
                                                        hoverBorderWidth: 4,
                                                        hoverOffset: 15
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: true,
                                                    aspectRatio: 1,
                                                    plugins: {
                                                        legend: {
                                                            display: true,
                                                            position: 'bottom',
                                                            align: 'center',
                                                            labels: {
                                                                padding: 12,
                                                                font: {
                                                                    size: 11,
                                                                    family: 'Arial, sans-serif'
                                                                },
                                                                boxWidth: 12,
                                                                boxHeight: 12,
                                                                usePointStyle: true,
                                                                pointStyle: 'circle'
                                                            }
                                                        },
                                                        tooltip: {
                                                            backgroundColor: 'rgba(0,0,0,0.85)',
                                                            padding: 12,
                                                            cornerRadius: 8,
                                                            titleFont: { size: 14, weight: 'bold' },
                                                            bodyFont: { size: 13 },
                                                            bodySpacing: 6,
                                                            callbacks: {
                                                                label: function(context) {
                                                                    const label = context.label || '';
                                                                    const value = context.parsed || 0;
                                                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                                    const percentage = ((value / total) * 100).toFixed(1);
                                                                    return label + ': ' + value + ' (' + percentage + '%)';
                                                                }
                                                            }
                                                        }
                                                    },
                                                    animation: {
                                                        animateRotate: true,
                                                        animateScale: true
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                <?php else: ?>
                                    <!-- Short Answer / Paragraph / WYSIWYG -->
                                    <div class="mt-3">
                                        <?php if ($data['question']['answer_type'] === 'wysiwyg'): ?>
                                            <!-- WYSIWYG - Display HTML content with images -->
                                            <?php foreach ($data['summary'] as $answer): ?>
                                                <div class="card mb-3">
                                                    <div class="card-body wysiwyg-content">
                                                        <?php echo $answer; // Output raw HTML without escaping ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- Short Answer / Paragraph - Display as text -->
                                            <ul class="list-group">
                                                <?php foreach ($data['summary'] as $answer): ?>
                                                    <li class="list-group-item"><?= esc($answer) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
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
