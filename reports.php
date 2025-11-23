<?php
require_once "api/reports-b.php";

// Cashier sees basic daily reports only
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'cashier'])) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reports - Queuing</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/reports.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<button class="dark-toggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>

<div class="main-content">
  <h1><i class="bi bi-bar-chart nav-icon"></i> Reports</h1>
  
  <!-- Today's Summary Card -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Today's Summary</h5>
      <div class="row text-center">
        <div class="col-md-3">
          <div class="border rounded p-3">
            <h6 class="text-muted">Served Today</h6>
            <h3 class="text-success"><?= $servedCount ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3">
            <h6 class="text-muted">Currently Waiting</h6>
            <h3 class="text-warning"><?= $waitingCount ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3">
            <h6 class="text-muted">Total Transactions</h6>
            <h3 class="text-info"><?= count($transactions) ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3">
            <h6 class="text-muted">Date</h6>
            <h3 class="text-primary"><?= date('M j, Y') ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Performance Metrics Section (Only if available) -->
  <?php if ($todaySummary): ?>
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Performance Metrics</h5>
    </div>
    <div class="card-body">
      <div class="row text-center">
        <div class="col-md-2">
          <h6>Efficiency Rate</h6>
          <h4 class="text-success"><?= $todaySummary['service_efficiency_rate'] ?? 'N/A' ?>%</h4>
        </div>
        <div class="col-md-2">
          <h6>Avg Wait Time</h6>
          <h4><?= $todaySummary['avg_wait_time'] ? number_format($todaySummary['avg_wait_time'] / 60, 1) . 'm' : 'N/A' ?></h4>
        </div>
        <div class="col-md-2">
          <h6>Total Revenue</h6>
          <h4 class="text-success">₱<?= number_format($todaySummary['total_transaction_volume'] ?? 0, 2) ?></h4>
        </div>
        <div class="col-md-2">
          <h6>Voided</h6>
          <h4 class="text-danger"><?= $todaySummary['voided_count'] ?? 0 ?></h4>
        </div>
        <div class="col-md-2">
          <h6>Total Queues</h6>
          <h4><?= $todaySummary['total_queues'] ?? 0 ?></h4>
        </div>
        <div class="col-md-2">
          <h6>Avg Service Time</h6>
          <h4><?= $todaySummary['avg_service_time'] ? number_format($todaySummary['avg_service_time'] / 60, 1) . 'm' : 'N/A' ?></h4>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Recent Transactions Table -->
  <div class="reports-table card">
    <div class="card-header">
      <h5 class="mb-0">Recent Transactions</h5>
    </div>
    <div class="card-body">
      <?php if (count($transactions) > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Queue</th>
              <th>Amount</th>
              <th>Type</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($transactions as $t): ?>
            <tr>
              <td><strong>#<?= $t['transaction_id'] ?></strong></td>
              <td>Queue #<?= $t['queue_id'] ?></td>
              <td class="fw-bold text-success">₱<?= number_format($t['amount'], 2) ?></td>
              <td>
                <span class="badge bg-info"><?= strtoupper($t['payment_type']) ?></span>
              </td>
              <td><?= date('M j, g:i A', strtotime($t['date_paid'])) ?></td>
              <td>
                <span class="badge bg-<?= $t['status'] === 'completed' ? 'success' : 'warning' ?>">
                  <?= ucfirst($t['status']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
      <div class="text-center py-4">
        <i class="bi bi-receipt" style="font-size: 3rem; color: #6c757d;"></i>
        <p class="text-muted mt-2">No transactions found for today.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Additional Stats -->
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h6>Quick Stats</h6>
          <ul class="list-unstyled">
            <li class="mb-2">
              <small class="text-muted">Total served today:</small>
              <strong class="float-end"><?= $servedCount ?></strong>
            </li>
            <li class="mb-2">
              <small class="text-muted">Currently in queue:</small>
              <strong class="float-end text-warning"><?= $waitingCount ?></strong>
            </li>
            <li class="mb-2">
              <small class="text-muted">Report generated:</small>
              <strong class="float-end"><?= date('g:i A') ?></strong>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <p class="mt-3"><a href="dashboard.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a></p>
</div>

<script src="js/darkmode.js"></script>
<script src="js/autorefresh.js"></script>
</body>
</html>