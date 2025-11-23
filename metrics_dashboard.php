<?php
require_once "api/metrics-dashboard-b.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Performance Metrics - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/metrics.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <span class="material-symbols-outlined" style="vertical-align:middle">admin_panel_settings</span>
            Admin Performance Dashboard
        </h1>
        <div>
            <a href="admin_reports.php" class="btn btn-outline-primary me-2">
                <span class="material-symbols-outlined" style="vertical-align:middle">assessment</span>
                Advanced Reports
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                Back to Queue
            </a>
        </div>
    </div>

    <!-- Real-time KPIs -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card kpi-card">
                <div class="card-body">
                    <h5>Today's Efficiency</h5>
                    <h2 class="text-primary"><?= $todayEfficiency ?>%</h2>
                    <small>Served: <?= $todayServed ?>/<?= $todayTotal ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card">
                <div class="card-body">
                    <h5>Avg Wait Time</h5>
                    <h2 class="text-info"><?= $avgWaitTime ?>m</h2>
                    <small>Today's average</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card">
                <div class="card-body">
                    <h5>Avg Service Time</h5>
                    <h2 class="text-success"><?= $avgServiceTime ?>m</h2>
                    <small>Per transaction</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card kpi-card">
                <div class="card-body">
                    <h5>Revenue Today</h5>
                    <h2 class="text-warning">₱<?= $todayRevenue ?></h2>
                    <small>Total transactions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>7-Day Performance Trends</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Queues</th>
                                <th>Served</th>
                                <th>Efficiency</th>
                                <th>Avg Wait</th>
                                <th>Avg Service</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($weeklyMetrics as $metric): ?>
                            <tr>
                                <td><?= $metric['summary_date'] ?></td>
                                <td><?= $metric['total_queues'] ?></td>
                                <td><?= $metric['served_count'] ?></td>
                                <td><?= number_format($metric['service_efficiency_rate'], 1) ?>%</td>
                                <td><?= number_format($metric['avg_wait_time'] / 60, 1) ?>m</td>
                                <td><?= number_format($metric['avg_service_time'] / 60, 1) ?>m</td>
                                <td>₱<?= number_format($metric['total_transaction_volume'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Cashier Performance</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Cashier</th>
                                <th>Today</th>
                                <th>Avg Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cashierPerformance as $cashier): ?>
                            <tr>
                                <td><?= $cashier['name'] ?></td>
                                <td><?= $cashier['served_today'] ?> served</td>
                                <td><?= number_format($cashier['avg_service_time'] / 60, 1) ?>m</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Recent System Events</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recentLogs as $log): ?>
                    <div class="small text-muted mb-1">
                        [<?= $log['created_at'] ?>] <?= $log['message'] ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/darkmode.js"></script>
</body>
</html>