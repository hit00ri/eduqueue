<?php
require_once "api/reports-b.php"
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
  <p>Served: <strong><?= $servedCount ?></strong> | Waiting: <strong><?= $waitingCount ?></strong></p>

  <div class="reports-table card">
    <h5 class="mb-3">Recent Transactions</h5>
    <table class="table table-borderless">
      <thead><tr><th>ID</th><th>Queue</th><th>Amount</th><th>Type</th><th>Date</th></tr></thead>
      <tbody>
      <?php foreach ($transactions as $t): ?>
        <tr>
          <td><?= $t['transaction_id'] ?></td>
          <td><?= $t['queue_id'] ?></td>
          <td><?= $t['amount'] ?></td>
          <td><?= $t['payment_type'] ?></td>
          <td><?= $t['date_paid'] ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <p class="mt-3"><a href="dashboard.php" class="btn btn-link">Back</a></p>
</div>

<script src="js/darkmode.js"></script>
<script src="js/autorefresh.js"></script>
</body>
</html>
