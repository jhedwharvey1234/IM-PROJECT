<?php

$this->extend('layout');

$this->section('content');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Alerts Calendar</title>
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
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Document Alerts']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('documents') ?>" title="Go to Documents">Document Management</a>
            <span class="separator">›</span>
            <span class="current">Alerts Calendar</span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

    <div class="row">
        <!-- Calendar Section -->
        <div class="col-lg-8">
            <div class="detail-card">
                <h5 class="mb-0">Calendar View</h5>
                <div class="mt-3">
                    <div class="d-flex gap-2 align-items-center mb-3">
                        <a href="?year=<?= $currentMonth == 1 ? $currentYear - 1 : $currentYear ?>&month=<?= $currentMonth == 1 ? 12 : $currentMonth - 1 ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                        <span class="fw-bold"><?= date('F Y', mktime(0, 0, 0, $currentMonth, 1, $currentYear)) ?></span>
                        <a href="?year=<?= $currentMonth == 12 ? $currentYear + 1 : $currentYear ?>&month=<?= $currentMonth == 12 ? 1 : $currentMonth + 1 ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="?year=<?= date('Y') ?>&month=<?= date('m') ?>" class="btn btn-sm btn-outline-primary">
                            Today
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Sun</th>
                                    <th class="text-center">Mon</th>
                                    <th class="text-center">Tue</th>
                                    <th class="text-center">Wed</th>
                                    <th class="text-center">Thu</th>
                                    <th class="text-center">Fri</th>
                                    <th class="text-center">Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $firstDay = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                                $dayOfWeek = date('w', $firstDay);
                                $daysInMonth = date('t', $firstDay);

                                $alertsByDate = [];
                                foreach ($alerts as $alert) {
                                    $date = date('Y-m-d', strtotime($alert['alert_date']));
                                    if (!isset($alertsByDate[$date])) {
                                        $alertsByDate[$date] = [];
                                    }
                                    $alertsByDate[$date][] = $alert;
                                }

                                $day = 1;
                                for ($week = 0; $week < 6; $week++) {
                                    echo '<tr style="height: 120px;">';
                                    for ($dayOfWeekCounter = 0; $dayOfWeekCounter < 7; $dayOfWeekCounter++) {
                                        if (($week === 0 && $dayOfWeekCounter < $dayOfWeek) || $day > $daysInMonth) {
                                            echo '<td class="bg-light"></td>';
                                        } else {
                                            $displayDate = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                                            $hasAlerts = isset($alertsByDate[$displayDate]) && count($alertsByDate[$displayDate]) > 0;
                                            $isToday = $displayDate === date('Y-m-d');
                                            $cellClass = 'position-relative ';
                                            if ($isToday) $cellClass .= 'table-primary';
                                            elseif ($hasAlerts) $cellClass .= 'table-info';
                                            echo '<td class="' . $cellClass . '" style="vertical-align: top; overflow-y: auto;">';
                                            echo '<div class="fw-bold mb-1">' . $day . '</div>';
                                            if ($hasAlerts) {
                                                foreach ($alertsByDate[$displayDate] as $alert) {
                                                    $badge = 'badge bg-warning text-dark';
                                                    if ($alert['is_notified']) $badge = 'badge bg-success';
                                                    echo '<div class="' . $badge . '" style="font-size: 0.75rem; margin-bottom: 2px;" title="' . htmlspecialchars($alert['description']) . '">';
                                                    echo htmlspecialchars(substr($alert['document_title'], 0, 15));
                                                    if (strlen($alert['document_title']) > 15) echo '...';
                                                    echo '</div>';
                                                }
                                            }
                                            echo '</td>';
                                            $day++;
                                        }
                                    }
                                    echo '</tr>';
                                    if ($day > $daysInMonth) break;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Alerts Section -->
        <div class="col-lg-4">
            <div class="detail-card">
                <h5 class="mb-0">Upcoming Alerts (30 Days) <span class="badge bg-primary float-end"><?= count($upcomingAlerts) ?></span></h5>
                <div class="mt-3" style="max-height: 500px; overflow-y: auto;">
                    <?php if (count($upcomingAlerts) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($upcomingAlerts as $alert): ?>
                                <a href="<?= site_url('documents/details/' . $alert['document_id']) ?>" class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($alert['document_title']) ?></h6>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-calendar"></i>
                                                <?= date('M d, Y', strtotime($alert['alert_date'])) ?>
                                                <?php if ($alert['alert_time']): ?>
                                                    at <?= date('h:i A', strtotime($alert['alert_time'])) ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php if ($alert['description']): ?>
                                                <small class="text-muted d-block mt-1" title="<?= htmlspecialchars($alert['description']) ?>">
                                                    <?= htmlspecialchars(substr($alert['description'], 0, 50)) ?>
                                                    <?= strlen($alert['description']) > 50 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-warning text-dark ms-2">
                                            <?php if ($alert['is_notified']): ?>
                                                <i class="bi bi-check-circle"></i> Done
                                            <?php else: ?>
                                                <i class="bi bi-exclamation-circle"></i> Pending
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            <p class="mt-2">No upcoming alerts</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

        <?= view('partials/footer') ?>
    </div>

    <style>
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table tbody td {
            word-break: break-word;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
