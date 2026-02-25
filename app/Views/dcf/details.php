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
        .text-insights-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin: 15px 0; }
        .text-insight-card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; }
        .text-insight-card h6 { margin-bottom: 10px; color: #495057; }
        .text-insight-canvas { position: relative; height: 280px; }
        .question-card-clickable { cursor: pointer; }
        .question-card-clickable:focus-visible { outline: 2px solid #0d6efd; outline-offset: 2px; }
        
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
        .wysiwyg-modal-limited { max-height: 360px; overflow: auto; }
        .wysiwyg-modal-limited table { display: block; overflow-x: auto; }
        .wysiwyg-modal-limited img,
        .wysiwyg-modal-limited iframe,
        .wysiwyg-modal-limited video,
        .wysiwyg-modal-limited embed,
        .wysiwyg-modal-limited object { max-width: 100%; height: auto; }
        .wysiwyg-modal-limited pre,
        .wysiwyg-modal-limited code { white-space: pre-wrap; word-break: break-word; }
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
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <h5 class="mb-0"><i class="bi bi-table"></i> Response List</h5>
                            <div class="input-group input-group-sm" style="max-width: 320px;">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="respondentSearchInput" placeholder="Search respondent..." aria-label="Search respondent">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="respondentTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Submitted At</th>
                                        <th class="text-center" width="130">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="respondentTableBody">
                                    <?php foreach ($responses as $idx => $response): ?>
                                        <?php $responseDetailsModalId = 'response_details_' . (int) $response['id']; ?>
                                        <tr class="respondent-row">
                                            <td class="respondent-row-number"><?= $idx + 1 ?></td>
                                            <td><?= esc($response['respondent_name']) ?></td>
                                            <td><?= esc($response['respondent_mobile'] ?: 'N/A') ?></td>
                                            <td><?= esc($response['respondent_email'] ?: 'N/A') ?></td>
                                            <td><?= esc($response['submitted_at']) ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $responseDetailsModalId ?>">
                                                    <i class="bi bi-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                            <small class="text-muted" id="respondentPaginationInfo"></small>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Respondent pagination controls">
                                <button type="button" class="btn btn-outline-secondary" id="respondentPrevPage">Previous</button>
                                <button type="button" class="btn btn-outline-secondary" id="respondentNextPage">Next</button>
                            </div>
                        </div>
                        <div class="mt-2" id="respondentPageNumbers"></div>

                        <?php foreach ($responses as $response): ?>
                            <?php
                                $responseDetailsModalId = 'response_details_' . (int) $response['id'];
                                $respondentAnswers = $responseAnswersMap[(int) $response['id']] ?? [];
                            ?>
                            <div class="modal fade" id="<?= $responseDetailsModalId ?>" tabindex="-1" aria-labelledby="<?= $responseDetailsModalId ?>_label" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $responseDetailsModalId ?>_label">Respondent Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6"><strong>Name:</strong> <?= esc($response['respondent_name'] ?: 'N/A') ?></div>
                                                <div class="col-md-6"><strong>Email:</strong> <?= esc($response['respondent_email'] ?: 'N/A') ?></div>
                                                <div class="col-md-6"><strong>Mobile:</strong> <?= esc($response['respondent_mobile'] ?: 'N/A') ?></div>
                                                <div class="col-md-6"><strong>Submitted At:</strong> <?= esc($response['submitted_at'] ?: 'N/A') ?></div>
                                            </div>

                                            <?php if (!empty($respondentAnswers)): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th width="45">#</th>
                                                                <th>Question</th>
                                                                <th width="140">Type</th>
                                                                <th>Answer</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($respondentAnswers as $answerIndex => $answerRow): ?>
                                                                <tr>
                                                                    <td><?= $answerIndex + 1 ?></td>
                                                                    <td><?= esc($answerRow['question_text'] ?? 'Question') ?></td>
                                                                    <td><?= esc($answerRow['answer_type'] ?? 'N/A') ?></td>
                                                                    <td><?= esc($answerRow['answer_text'] ?? 'N/A') ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted mb-0">No answers found for this respondent.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="bi bi-bar-chart"></i> Question Analytics</h5>
                    <?php foreach ($analytics as $questionId => $data): ?>
                        <?php $detailsModalId = 'question_details_' . $questionId; ?>
                        <div class="chart-container question-card-clickable" role="button" tabindex="0" aria-label="Open full details for <?= esc($data['question']['question_text']) ?>" data-details-modal="#<?= $detailsModalId ?>" onclick="openQuestionDetailsFromCard(this, event)" onkeydown="handleQuestionCardKeydown(this, event)">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div>
                                    <h6 class="mb-1">
                                        <button type="button" class="btn btn-link p-0 text-start text-decoration-none fw-semibold" data-bs-toggle="modal" data-bs-target="#<?= $detailsModalId ?>">
                                            <?= esc($data['question']['question_text']) ?>
                                        </button>
                                    </h6>
                                    <small class="text-muted">Type: <?= esc($data['question']['answer_type']) ?></small>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $detailsModalId ?>">
                                    <i class="bi bi-arrows-angle-expand"></i> Full Details
                                </button>
                            </div>
                            
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
                                    
                                    <!-- Chart Only -->
                                    <div class="chart-wrapper" style="max-width: 480px;">
                                        <div class="chart-canvas-wrapper">
                                            <canvas id="<?= $chartId ?>"></canvas>
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
                                        <?php
                                            $textInsights = $data['textInsights'] ?? null;
                                            $wordCounts = $textInsights['word_counts'] ?? [];
                                            $phraseCounts = $textInsights['phrase_counts'] ?? [];
                                            $sentenceCounts = $textInsights['sentence_counts'] ?? [];
                                            $wordChartId = 'word_chart_' . $questionId;
                                            $phraseChartId = 'phrase_chart_' . $questionId;
                                            $sentenceChartId = 'sentence_chart_' . $questionId;
                                        ?>

                                        <?php if (!empty($wordCounts) || !empty($sentenceCounts) || !empty($phraseCounts)): ?>
                                            <div class="text-insights-grid">
                                                <?php if (!empty($wordCounts)): ?>
                                                    <?php $wordChartDataJson = json_encode(['labels' => array_keys($wordCounts), 'data' => array_values($wordCounts)], JSON_UNESCAPED_UNICODE); ?>
                                                    <div class="text-insight-card">
                                                        <h6><i class="bi bi-type"></i> Most Common Words</h6>
                                                        <div class="text-insight-canvas">
                                                            <canvas id="<?= $wordChartId ?>"></canvas>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const wordsData = <?= $wordChartDataJson ?>;
                                                            const wordsCtx = document.getElementById('<?= $wordChartId ?>');
                                                            if (!wordsCtx) return;

                                                            new Chart(wordsCtx, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: wordsData.labels,
                                                                    datasets: [{
                                                                        label: 'Count',
                                                                        data: wordsData.data,
                                                                        backgroundColor: '#667eea',
                                                                        borderRadius: 6
                                                                    }]
                                                                },
                                                                options: {
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    plugins: { legend: { display: false } },
                                                                    scales: {
                                                                        y: { beginAtZero: true, ticks: { precision: 0 } }
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php endif; ?>

                                                <?php
                                                    $phraseModes = [];
                                                    foreach ([2, 3, 4, 5] as $n) {
                                                        $counts = $phraseCounts[(string) $n] ?? $phraseCounts[$n] ?? [];
                                                        if (empty($counts) || !is_array($counts)) {
                                                            continue;
                                                        }

                                                        $phraseModes[(string) $n] = [
                                                            'label' => $n . '-word phrases',
                                                            'labels' => array_keys($counts),
                                                            'data' => array_values($counts),
                                                        ];
                                                    }
                                                    $phraseModesJson = json_encode($phraseModes, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                                                    if ($phraseModesJson === false) {
                                                        $phraseModesJson = '{}';
                                                    }
                                                ?>
                                                <?php if (!empty($phraseModes)): ?>
                                                    <div class="text-insight-card">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0"><i class="bi bi-chat-quote"></i> Common Phrases</h6>
                                                            <div class="btn-group btn-group-sm" role="group" id="phrase_toggle_<?= $questionId ?>">
                                                                <?php foreach (array_keys($phraseModes) as $index => $n): ?>
                                                                    <button type="button" class="btn btn-outline-primary <?= $index === 0 ? 'active' : '' ?>" data-mode="<?= esc($n) ?>"><?= esc($n) ?>-word</button>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <div class="text-insight-canvas">
                                                            <canvas id="<?= $phraseChartId ?>"></canvas>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const phraseModes = <?= $phraseModesJson ?>;
                                                            const phraseCanvas = document.getElementById('<?= $phraseChartId ?>');
                                                            const phraseToggle = document.getElementById('phrase_toggle_<?= $questionId ?>');
                                                            const availableModes = Object.keys(phraseModes);
                                                            if (!phraseCanvas || !phraseToggle || availableModes.length === 0) return;

                                                            const normalizeModeData = (mode) => {
                                                                const source = phraseModes[mode] || {};
                                                                let labels = Array.isArray(source.labels) ? source.labels.slice() : [];
                                                                let data = [];

                                                                if (Array.isArray(source.data)) {
                                                                    data = source.data.map((value) => Number(value) || 0);
                                                                } else if (source.data && typeof source.data === 'object') {
                                                                    if (labels.length === 0) {
                                                                        labels = Object.keys(source.data);
                                                                    }
                                                                    data = labels.map((label) => Number(source.data[label]) || 0);
                                                                }

                                                                if (labels.length !== data.length) {
                                                                    const minLength = Math.min(labels.length, data.length);
                                                                    labels = labels.slice(0, minLength);
                                                                    data = data.slice(0, minLength);
                                                                }

                                                                return {
                                                                    label: source.label || 'Phrases',
                                                                    labels,
                                                                    data,
                                                                };
                                                            };

                                                            const initialMode = availableModes[0];
                                                            const initialData = normalizeModeData(initialMode);
                                                            const phraseLabelState = {
                                                                labels: initialData.labels.slice(),
                                                            };
                                                            const phraseChart = new Chart(phraseCanvas, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: initialData.labels,
                                                                    datasets: [{
                                                                        label: initialData.label,
                                                                        data: initialData.data,
                                                                        backgroundColor: '#f59f00',
                                                                        borderRadius: 6
                                                                    }]
                                                                },
                                                                options: {
                                                                    indexAxis: 'y',
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    plugins: {
                                                                        legend: { display: false },
                                                                        tooltip: {
                                                                            callbacks: {
                                                                                title: function (items) {
                                                                                    const index = items[0]?.dataIndex ?? 0;
                                                                                    return phraseLabelState.labels[index] || '';
                                                                                }
                                                                            }
                                                                        }
                                                                    },
                                                                    scales: {
                                                                        x: { beginAtZero: true, ticks: { precision: 0 } },
                                                                        y: {
                                                                            ticks: {
                                                                                callback: function (value, index) {
                                                                                    const label = phraseLabelState.labels[index] || '';
                                                                                    return label.length > 50 ? label.slice(0, 50) + '…' : label;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                            phraseChart.currentMode = initialMode;

                                                            phraseToggle.querySelectorAll('button[data-mode]').forEach((btn) => {
                                                                btn.addEventListener('click', function () {
                                                                    const mode = this.getAttribute('data-mode');
                                                                    if (!phraseModes[mode] || phraseChart.currentMode === mode) return;

                                                                    const modeData = normalizeModeData(mode);

                                                                    phraseChart.currentMode = mode;
                                                                    phraseChart.data.labels = modeData.labels;
                                                                    phraseChart.data.datasets[0].label = modeData.label;
                                                                    phraseChart.data.datasets[0].data = modeData.data;
                                                                    phraseLabelState.labels = modeData.labels.slice();
                                                                    phraseChart.update();

                                                                    phraseToggle.querySelectorAll('button[data-mode]').forEach((b) => b.classList.remove('active'));
                                                                    this.classList.add('active');
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                <?php endif; ?>



                                                <?php if (!empty($sentenceCounts)): ?>
                                                    <?php $sentenceChartDataJson = json_encode(['labels' => array_keys($sentenceCounts), 'data' => array_values($sentenceCounts)], JSON_UNESCAPED_UNICODE); ?>
                                                    <div class="text-insight-card">
                                                        <h6><i class="bi bi-chat-left-text"></i> Most Common Sentences</h6>
                                                        <div class="text-insight-canvas">
                                                            <canvas id="<?= $sentenceChartId ?>"></canvas>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const sentencesData = <?= $sentenceChartDataJson ?>;
                                                            const sentencesCtx = document.getElementById('<?= $sentenceChartId ?>');
                                                            if (!sentencesCtx) return;

                                                            const truncate = (value, limit = 60) => value.length > limit ? value.slice(0, limit) + '…' : value;

                                                            new Chart(sentencesCtx, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: sentencesData.labels,
                                                                    datasets: [{
                                                                        label: 'Count',
                                                                        data: sentencesData.data,
                                                                        backgroundColor: '#38a3a5',
                                                                        borderRadius: 6
                                                                    }]
                                                                },
                                                                options: {
                                                                    indexAxis: 'y',
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    plugins: {
                                                                        legend: { display: false },
                                                                        tooltip: {
                                                                            callbacks: {
                                                                                title: (items) => sentencesData.labels[items[0].dataIndex] || ''
                                                                            }
                                                                        }
                                                                    },
                                                                    scales: {
                                                                        x: { beginAtZero: true, ticks: { precision: 0 } },
                                                                        y: {
                                                                            ticks: {
                                                                                callback: function(value, index) {
                                                                                    return truncate(sentencesData.labels[index] || '');
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted mt-2">No responses yet.</p>
                            <?php endif; ?>
                        </div>

                        <div class="modal fade" id="<?= $detailsModalId ?>" tabindex="-1" aria-labelledby="<?= $detailsModalId ?>_label" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="<?= $detailsModalId ?>_label"><?= esc($data['question']['question_text']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted mb-3">Type: <?= esc($data['question']['answer_type']) ?></p>

                                        <?php
                                            $modalTextInsights = $data['textInsights'] ?? null;
                                            $modalWordCounts = $modalTextInsights['word_counts'] ?? [];
                                            $modalPhraseCounts = $modalTextInsights['phrase_counts'] ?? [];
                                            $modalSentenceCounts = $modalTextInsights['sentence_counts'] ?? [];
                                            $modalViewToggleId = 'modal_view_toggle_' . $questionId;
                                            $modalAnalyticsSectionId = 'modal_analytics_section_' . $questionId;
                                            $modalAnswersSectionId = 'modal_answers_section_' . $questionId;
                                            $modalChoiceChartId = 'modal_chart_' . $questionId;
                                            $modalWordChartId = 'modal_word_chart_' . $questionId;
                                            $modalPhraseChartId = 'modal_phrase_chart_' . $questionId;
                                            $modalPhraseToggleId = 'modal_phrase_toggle_' . $questionId;
                                            $modalSentenceChartId = 'modal_sentence_chart_' . $questionId;
                                        ?>

                                        <?php if (is_array($data['summary']) && count($data['summary']) > 0): ?>
                                            <div class="d-flex justify-content-end mb-3">
                                                <div class="btn-group btn-group-sm" role="group" id="<?= $modalViewToggleId ?>">
                                                    <button type="button" class="btn btn-outline-primary" data-view="charts">Chart-only</button>
                                                    <button type="button" class="btn btn-outline-primary" data-view="answers">Answers-only</button>
                                                    <button type="button" class="btn btn-outline-primary active" data-view="both">Both</button>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div id="<?= $modalAnalyticsSectionId ?>">

                                        <?php if (is_array($data['summary']) && count($data['summary']) > 0): ?>
                                            <?php if (array_keys($data['summary'])[0] !== 0): ?>
                                                <?php
                                                    $modalAnswers = array_keys($data['summary']);
                                                    $modalCounts = array_values($data['summary']);
                                                    $modalPercentages = [];
                                                    $modalTotalForChart = array_sum($modalCounts);
                                                    foreach ($modalCounts as $countValue) {
                                                        $modalPercentages[] = $modalTotalForChart > 0 ? round(($countValue / $modalTotalForChart) * 100, 1) : 0;
                                                    }
                                                    $modalChartDataJson = json_encode([
                                                        'labels' => $modalAnswers,
                                                        'data' => $modalCounts,
                                                        'percentages' => $modalPercentages,
                                                    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                                                    if ($modalChartDataJson === false) {
                                                        $modalChartDataJson = '{"labels":[],"data":[],"percentages":[]}';
                                                    }
                                                ?>
                                                <div class="text-insight-card mb-3">
                                                    <h6><i class="bi bi-pie-chart"></i> Analytics Chart</h6>
                                                    <div class="text-insight-canvas" style="height:320px;">
                                                        <canvas id="<?= $modalChoiceChartId ?>"></canvas>
                                                    </div>
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const modalElement = document.getElementById('<?= $detailsModalId ?>');
                                                        const chartCanvas = document.getElementById('<?= $modalChoiceChartId ?>');
                                                        if (!modalElement || !chartCanvas) return;

                                                        const modalData = <?= $modalChartDataJson ?>;
                                                        let modalChartInstance = null;

                                                        const colors = [
                                                            '#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe',
                                                            '#43e97b', '#38f9d7', '#fa709a', '#fee140', '#30cfd0',
                                                            '#a8edea', '#fed6e3', '#ff7675', '#74b9ff', '#81ecec',
                                                            '#ff9ff3', '#feca57', '#48dbfb', '#ff6b6b', '#1dd1a1'
                                                        ];

                                                        const renderChoiceModalChart = function () {
                                                            if (modalChartInstance || !modalData || !Array.isArray(modalData.labels)) {
                                                                return;
                                                            }

                                                            const backgroundColor = colors.slice(0, modalData.labels.length);
                                                            modalChartInstance = new Chart(chartCanvas, {
                                                                type: 'doughnut',
                                                                data: {
                                                                    labels: modalData.labels,
                                                                    datasets: [{
                                                                        label: 'Responses',
                                                                        data: modalData.data || [],
                                                                        backgroundColor: backgroundColor,
                                                                        borderColor: '#ffffff',
                                                                        borderWidth: 3,
                                                                        hoverOffset: 12
                                                                    }]
                                                                },
                                                                options: {
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    plugins: {
                                                                        legend: { display: true, position: 'bottom' }
                                                                    }
                                                                }
                                                            });
                                                        };

                                                        modalElement.addEventListener('shown.bs.modal', renderChoiceModalChart);
                                                        modalElement.addEventListener('hidden.bs.modal', function () {
                                                            if (modalChartInstance) {
                                                                modalChartInstance.destroy();
                                                                modalChartInstance = null;
                                                            }
                                                        });
                                                    });
                                                </script>
                                            <?php else: ?>
                                                <?php if (!empty($modalWordCounts) || !empty($modalSentenceCounts) || !empty($modalPhraseCounts)): ?>
                                                    <?php
                                                        $modalWordChartDataJson = json_encode([
                                                            'labels' => array_keys($modalWordCounts),
                                                            'data' => array_values($modalWordCounts)
                                                        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                                                        if ($modalWordChartDataJson === false) {
                                                            $modalWordChartDataJson = '{"labels":[],"data":[]}';
                                                        }

                                                        $modalSentenceChartDataJson = json_encode([
                                                            'labels' => array_keys($modalSentenceCounts),
                                                            'data' => array_values($modalSentenceCounts)
                                                        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                                                        if ($modalSentenceChartDataJson === false) {
                                                            $modalSentenceChartDataJson = '{"labels":[],"data":[]}';
                                                        }

                                                        $modalPhraseModes = [];
                                                        foreach ([2, 3, 4, 5] as $n) {
                                                            $counts = $modalPhraseCounts[(string) $n] ?? $modalPhraseCounts[$n] ?? [];
                                                            if (empty($counts) || !is_array($counts)) {
                                                                continue;
                                                            }
                                                            $modalPhraseModes[(string) $n] = [
                                                                'label' => $n . '-word phrases',
                                                                'labels' => array_keys($counts),
                                                                'data' => array_values($counts),
                                                            ];
                                                        }
                                                        $modalPhraseModesJson = json_encode($modalPhraseModes, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                                                        if ($modalPhraseModesJson === false) {
                                                            $modalPhraseModesJson = '{}';
                                                        }
                                                    ?>
                                                    <div class="text-insights-grid mb-3">
                                                        <?php if (!empty($modalWordCounts)): ?>
                                                            <div class="text-insight-card">
                                                                <h6><i class="bi bi-type"></i> Most Common Words</h6>
                                                                <div class="text-insight-canvas">
                                                                    <canvas id="<?= $modalWordChartId ?>"></canvas>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($modalPhraseModes)): ?>
                                                            <div class="text-insight-card">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="mb-0"><i class="bi bi-chat-quote"></i> Common Phrases</h6>
                                                                    <div class="btn-group btn-group-sm" role="group" id="<?= $modalPhraseToggleId ?>">
                                                                        <?php foreach (array_keys($modalPhraseModes) as $index => $n): ?>
                                                                            <button type="button" class="btn btn-outline-primary <?= $index === 0 ? 'active' : '' ?>" data-mode="<?= esc($n) ?>"><?= esc($n) ?>-word</button>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="text-insight-canvas">
                                                                    <canvas id="<?= $modalPhraseChartId ?>"></canvas>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($modalSentenceCounts)): ?>
                                                            <div class="text-insight-card">
                                                                <h6><i class="bi bi-chat-left-text"></i> Most Common Sentences</h6>
                                                                <div class="text-insight-canvas">
                                                                    <canvas id="<?= $modalSentenceChartId ?>"></canvas>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const modalElement = document.getElementById('<?= $detailsModalId ?>');
                                                            if (!modalElement) return;

                                                            const wordsData = <?= $modalWordChartDataJson ?>;
                                                            const sentencesData = <?= $modalSentenceChartDataJson ?>;
                                                            const phraseModes = <?= $modalPhraseModesJson ?>;

                                                            let wordChartInstance = null;
                                                            let phraseChartInstance = null;
                                                            let sentenceChartInstance = null;

                                                            const createWordChart = function () {
                                                                const canvas = document.getElementById('<?= $modalWordChartId ?>');
                                                                if (!canvas || wordChartInstance || !Array.isArray(wordsData.labels) || wordsData.labels.length === 0) return;

                                                                wordChartInstance = new Chart(canvas, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: wordsData.labels,
                                                                        datasets: [{
                                                                            label: 'Count',
                                                                            data: wordsData.data || [],
                                                                            backgroundColor: '#667eea',
                                                                            borderRadius: 6
                                                                        }]
                                                                    },
                                                                    options: {
                                                                        responsive: true,
                                                                        maintainAspectRatio: false,
                                                                        plugins: { legend: { display: false } },
                                                                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                                                                    }
                                                                });
                                                            };

                                                            const createPhraseChart = function () {
                                                                const canvas = document.getElementById('<?= $modalPhraseChartId ?>');
                                                                const toggle = document.getElementById('<?= $modalPhraseToggleId ?>');
                                                                const modes = Object.keys(phraseModes || {});
                                                                if (!canvas || !toggle || phraseChartInstance || modes.length === 0) return;

                                                                const normalizeModeData = (mode) => {
                                                                    const source = phraseModes[mode] || {};
                                                                    return {
                                                                        label: source.label || 'Phrases',
                                                                        labels: Array.isArray(source.labels) ? source.labels : [],
                                                                        data: Array.isArray(source.data) ? source.data : [],
                                                                    };
                                                                };

                                                                const initialMode = modes[0];
                                                                const initialData = normalizeModeData(initialMode);
                                                                const phraseLabels = { labels: initialData.labels.slice() };

                                                                phraseChartInstance = new Chart(canvas, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: initialData.labels,
                                                                        datasets: [{
                                                                            label: initialData.label,
                                                                            data: initialData.data,
                                                                            backgroundColor: '#f59f00',
                                                                            borderRadius: 6
                                                                        }]
                                                                    },
                                                                    options: {
                                                                        indexAxis: 'y',
                                                                        responsive: true,
                                                                        maintainAspectRatio: false,
                                                                        plugins: {
                                                                            legend: { display: false },
                                                                            tooltip: {
                                                                                callbacks: {
                                                                                    title: function (items) {
                                                                                        const index = items[0]?.dataIndex ?? 0;
                                                                                        return phraseLabels.labels[index] || '';
                                                                                    }
                                                                                }
                                                                            }
                                                                        },
                                                                        scales: {
                                                                            x: { beginAtZero: true, ticks: { precision: 0 } },
                                                                            y: {
                                                                                ticks: {
                                                                                    callback: function (value, index) {
                                                                                        const label = phraseLabels.labels[index] || '';
                                                                                        return label.length > 50 ? label.slice(0, 50) + '…' : label;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                });

                                                                toggle.querySelectorAll('button[data-mode]').forEach((btn) => {
                                                                    btn.addEventListener('click', function () {
                                                                        const mode = this.getAttribute('data-mode');
                                                                        if (!phraseModes[mode] || !phraseChartInstance || phraseChartInstance.currentMode === mode) return;

                                                                        const modeData = normalizeModeData(mode);
                                                                        phraseChartInstance.currentMode = mode;
                                                                        phraseChartInstance.data.labels = modeData.labels;
                                                                        phraseChartInstance.data.datasets[0].label = modeData.label;
                                                                        phraseChartInstance.data.datasets[0].data = modeData.data;
                                                                        phraseLabels.labels = modeData.labels.slice();
                                                                        phraseChartInstance.update();

                                                                        toggle.querySelectorAll('button[data-mode]').forEach((b) => b.classList.remove('active'));
                                                                        this.classList.add('active');
                                                                    });
                                                                });

                                                                phraseChartInstance.currentMode = initialMode;
                                                            };

                                                            const createSentenceChart = function () {
                                                                const canvas = document.getElementById('<?= $modalSentenceChartId ?>');
                                                                if (!canvas || sentenceChartInstance || !Array.isArray(sentencesData.labels) || sentencesData.labels.length === 0) return;

                                                                sentenceChartInstance = new Chart(canvas, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: sentencesData.labels,
                                                                        datasets: [{
                                                                            label: 'Count',
                                                                            data: sentencesData.data || [],
                                                                            backgroundColor: '#38a3a5',
                                                                            borderRadius: 6
                                                                        }]
                                                                    },
                                                                    options: {
                                                                        indexAxis: 'y',
                                                                        responsive: true,
                                                                        maintainAspectRatio: false,
                                                                        plugins: {
                                                                            legend: { display: false },
                                                                            tooltip: {
                                                                                callbacks: {
                                                                                    title: function (items) {
                                                                                        const index = items[0]?.dataIndex ?? 0;
                                                                                        return sentencesData.labels[index] || '';
                                                                                    }
                                                                                }
                                                                            }
                                                                        },
                                                                        scales: {
                                                                            x: { beginAtZero: true, ticks: { precision: 0 } },
                                                                            y: {
                                                                                ticks: {
                                                                                    callback: function (value, index) {
                                                                                        const label = sentencesData.labels[index] || '';
                                                                                        return label.length > 60 ? label.slice(0, 60) + '…' : label;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            };

                                                            modalElement.addEventListener('shown.bs.modal', function () {
                                                                createWordChart();
                                                                createPhraseChart();
                                                                createSentenceChart();
                                                            });

                                                            modalElement.addEventListener('hidden.bs.modal', function () {
                                                                if (wordChartInstance) {
                                                                    wordChartInstance.destroy();
                                                                    wordChartInstance = null;
                                                                }
                                                                if (phraseChartInstance) {
                                                                    phraseChartInstance.destroy();
                                                                    phraseChartInstance = null;
                                                                }
                                                                if (sentenceChartInstance) {
                                                                    sentenceChartInstance.destroy();
                                                                    sentenceChartInstance = null;
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        </div>

                                        <div id="<?= $modalAnswersSectionId ?>">

                                        <?php if (is_array($data['summary']) && count($data['summary']) > 0): ?>
                                            <div class="mb-3">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input
                                                        type="text"
                                                        class="form-control modal-answer-search"
                                                        placeholder="Search answers..."
                                                        aria-label="Search answers"
                                                        oninput="filterModalAnswers('<?= $detailsModalId ?>', this.value)"
                                                    >
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (is_array($data['summary']) && count($data['summary']) > 0): ?>
                                            <?php if (array_keys($data['summary'])[0] !== 0): ?>
                                                <?php $modalTotal = array_sum(array_values($data['summary'])); ?>
                                                <div class="table-responsive modal-search-table-wrap">
                                                    <table class="table table-sm table-hover modal-search-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Answer</th>
                                                                <th width="80" class="text-center">Count</th>
                                                                <th width="100" class="text-center">Percentage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($data['summary'] as $answer => $count): ?>
                                                                <?php $pct = $modalTotal > 0 ? round(($count / $modalTotal) * 100, 1) : 0; ?>
                                                                <tr class="modal-search-item">
                                                                    <td><?= esc($answer) ?></td>
                                                                    <td class="text-center"><strong><?= $count ?></strong></td>
                                                                    <td class="text-center"><span class="badge bg-primary"><?= $pct ?>%</span></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <?php if ($data['question']['answer_type'] === 'wysiwyg'): ?>
                                                    <?php foreach ($data['summary'] as $answer): ?>
                                                        <div class="card mb-3 modal-search-item">
                                                            <div class="card-body wysiwyg-content wysiwyg-modal-limited">
                                                                <?php echo $answer; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <ul class="list-group">
                                                        <?php foreach ($data['summary'] as $answer): ?>
                                                            <li class="list-group-item modal-search-item"><?= esc($answer) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <p class="text-muted mb-0 mt-2 modal-search-empty d-none">No matching answers found.</p>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">No responses yet.</p>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const modalElement = document.getElementById('<?= $detailsModalId ?>');
                                const toggleContainer = document.getElementById('<?= $modalViewToggleId ?>');
                                if (!modalElement || !toggleContainer) return;

                                const applyView = function (view) {
                                    setModalDetailsView('<?= $detailsModalId ?>', '<?= $modalAnalyticsSectionId ?>', '<?= $modalAnswersSectionId ?>', view);

                                    toggleContainer.querySelectorAll('button[data-view]').forEach((btn) => {
                                        btn.classList.toggle('active', btn.getAttribute('data-view') === view);
                                    });
                                };

                                toggleContainer.querySelectorAll('button[data-view]').forEach((btn) => {
                                    btn.addEventListener('click', function () {
                                        const view = this.getAttribute('data-view') || 'both';
                                        applyView(view);
                                    });
                                });

                                modalElement.addEventListener('shown.bs.modal', function () {
                                    const activeBtn = toggleContainer.querySelector('button[data-view].active');
                                    applyView(activeBtn ? activeBtn.getAttribute('data-view') : 'both');
                                });

                                modalElement.addEventListener('hidden.bs.modal', function () {
                                    applyView('both');
                                });
                            });
                        </script>
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

        function filterModalAnswers(modalId, value) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const query = (value || '').toLowerCase().trim();
            const items = modal.querySelectorAll('.modal-search-item');
            let visibleCount = 0;

            items.forEach((item) => {
                const text = (item.textContent || '').toLowerCase();
                const isMatch = query === '' || text.includes(query);
                item.classList.toggle('d-none', !isMatch);
                if (isMatch) {
                    visibleCount++;
                }
            });

            const noMatchLabel = modal.querySelector('.modal-search-empty');
            if (noMatchLabel) {
                noMatchLabel.classList.toggle('d-none', visibleCount > 0 || items.length === 0);
            }
        }

        function initRespondentTable() {
            const tableBody = document.getElementById('respondentTableBody');
            const searchInput = document.getElementById('respondentSearchInput');
            const prevButton = document.getElementById('respondentPrevPage');
            const nextButton = document.getElementById('respondentNextPage');
            const pageNumbers = document.getElementById('respondentPageNumbers');
            const infoLabel = document.getElementById('respondentPaginationInfo');

            if (!tableBody || !searchInput || !prevButton || !nextButton || !pageNumbers || !infoLabel) {
                return;
            }

            const allRows = Array.from(tableBody.querySelectorAll('tr.respondent-row'));
            if (allRows.length === 0) {
                infoLabel.textContent = 'No respondents found.';
                return;
            }

            const pageSize = 5;
            let currentPage = 1;
            let filteredRows = allRows.slice();

            const updateRowNumbers = () => {
                filteredRows.forEach((row, index) => {
                    const numberCell = row.querySelector('.respondent-row-number');
                    if (numberCell) {
                        numberCell.textContent = String(index + 1);
                    }
                });
            };

            const renderPageButtons = (totalPages) => {
                pageNumbers.innerHTML = '';
                if (totalPages <= 1) {
                    return;
                }

                const fragment = document.createDocumentFragment();
                for (let page = 1; page <= totalPages; page++) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm ' + (page === currentPage ? 'btn-primary' : 'btn-outline-secondary');
                    btn.textContent = String(page);
                    btn.addEventListener('click', () => {
                        currentPage = page;
                        render();
                    });
                    fragment.appendChild(btn);
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'btn-group btn-group-sm';
                wrapper.setAttribute('role', 'group');
                wrapper.appendChild(fragment);
                pageNumbers.appendChild(wrapper);
            };

            const render = () => {
                const totalItems = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
                currentPage = Math.min(Math.max(currentPage, 1), totalPages);

                const startIndex = (currentPage - 1) * pageSize;
                const endIndex = startIndex + pageSize;

                allRows.forEach((row) => {
                    row.classList.add('d-none');
                });

                filteredRows.slice(startIndex, endIndex).forEach((row) => {
                    row.classList.remove('d-none');
                });

                prevButton.disabled = currentPage <= 1;
                nextButton.disabled = currentPage >= totalPages;

                if (totalItems === 0) {
                    infoLabel.textContent = 'No matching respondents found.';
                } else {
                    const shownStart = startIndex + 1;
                    const shownEnd = Math.min(endIndex, totalItems);
                    infoLabel.textContent = `Showing ${shownStart}-${shownEnd} of ${totalItems} respondents`;
                }

                renderPageButtons(totalPages);
            };

            const applySearch = () => {
                const query = searchInput.value.toLowerCase().trim();
                filteredRows = allRows.filter((row) => {
                    const rowText = (row.textContent || '').toLowerCase();
                    return query === '' || rowText.includes(query);
                });

                updateRowNumbers();
                currentPage = 1;
                render();
            };

            searchInput.addEventListener('input', applySearch);
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    render();
                }
            });
            nextButton.addEventListener('click', () => {
                const totalPages = Math.max(1, Math.ceil(filteredRows.length / pageSize));
                if (currentPage < totalPages) {
                    currentPage++;
                    render();
                }
            });

            updateRowNumbers();
            render();
        }

        function setModalDetailsView(modalId, analyticsSectionId, answersSectionId, view) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const analyticsSection = document.getElementById(analyticsSectionId);
            const answersSection = document.getElementById(answersSectionId);
            if (!analyticsSection || !answersSection) return;

            const selected = (view || 'both').toLowerCase();
            if (selected === 'charts') {
                analyticsSection.classList.remove('d-none');
                answersSection.classList.add('d-none');
                return;
            }

            if (selected === 'answers') {
                analyticsSection.classList.add('d-none');
                answersSection.classList.remove('d-none');
                return;
            }

            analyticsSection.classList.remove('d-none');
            answersSection.classList.remove('d-none');
        }

        function isInteractiveCardTarget(target) {
            if (!target || typeof target.closest !== 'function') {
                return false;
            }

            return !!target.closest('a, button, input, select, textarea, label, .btn-group, [data-bs-toggle], [data-no-card-modal]');
        }

        function openQuestionDetailsFromCard(card, event) {
            if (!card) {
                return;
            }

            if (event && isInteractiveCardTarget(event.target)) {
                return;
            }

            const modalSelector = card.getAttribute('data-details-modal');
            if (!modalSelector) {
                return;
            }

            const modalElement = document.querySelector(modalSelector);
            if (!modalElement || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                return;
            }

            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }

        function handleQuestionCardKeydown(card, event) {
            if (!event) {
                return;
            }

            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openQuestionDetailsFromCard(card, event);
            }
        }

        document.addEventListener('hidden.bs.modal', function (event) {
            const modal = event.target;
            if (!modal || !modal.classList || !modal.classList.contains('modal')) return;

            const input = modal.querySelector('.modal-answer-search');
            if (input) {
                input.value = '';
                filterModalAnswers(modal.id, '');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            initRespondentTable();
        });
    </script>
</body>
</html>
