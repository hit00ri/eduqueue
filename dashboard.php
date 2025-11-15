<?php
require_once "api/dashboard-b.php"
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Queuing</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>
<body>
<?php include 'sidebar.php'; ?>

<button class="dark-toggle" title="Toggle dark mode">
  <i class="bi bi-moon-stars"></i>
</button>

<div class="main-content">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>
      <span class="material-symbols-outlined" style="vertical-align:middle">dashboard</span>
      Dashboard
    </h1>

    <div>
      <a href="reports.php" class="btn btn-outline-secondary me-2">
        <span class="material-symbols-outlined" style="vertical-align:middle">bar_chart</span>
        Reports
      </a>

      <a href="api/logout.php" class="btn btn-outline-danger">
        <span class="material-symbols-outlined" style="vertical-align:middle">logout</span>
        Logout
      </a>
    </div>
  </div>

  <!-- CALL NEXT BUTTON -->
  <form method="post" class="mb-3">
    <button name="call_next" class="btn btn-primary">
      <span class="material-symbols-outlined" style="vertical-align:middle">notifications_active</span>
      Call Next
    </button>
  </form>

  <!-- NOW SERVING -->
  <h3>
    <span class="material-symbols-outlined" style="vertical-align:middle">record_voice_over</span>
    Now Serving
  </h3>

  <?php if ($serving): ?>
    <div class="card p-3 mb-3 now-serving">
      <h4>
        Queue #<?= $serving['queue_number'] ?> — <?= htmlspecialchars($serving['name']) ?>
      </h4>

      <div class="mt-2">
        <form method="post" class="d-inline">
          <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
          <button name="served" class="btn btn-success">
            <span class="material-symbols-outlined" style="vertical-align:middle">task_alt</span>
            Mark Served
          </button>
        </form>

        <form method="post" class="d-inline">
          <input type="hidden" name="queue_id" value="<?= $serving['queue_id'] ?>">
          <button name="voided" class="btn btn-warning">
            <span class="material-symbols-outlined" style="vertical-align:middle">cancel</span>
            Void
          </button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <p>No one being served.</p>
  <?php endif; ?>

  <!-- WAITING LIST -->
  <h3>
    <span class="material-symbols-outlined" style="vertical-align:middle">hourglass_top</span>
    Waiting
  </h3>

  <ul class="list-unstyled">
    <?php foreach ($waiting as $w): ?>
      <li class="waiting-list mb-2 card p-2 d-flex justify-content-between align-items-center">
        <div>
          <strong>#<?= $w['queue_number'] ?></strong> —
          <?= htmlspecialchars($w['name']) ?>
        </div>
        <div class="text-muted small"><?= $w['time_in'] ?? '' ?></div>
      </li>
    <?php endforeach; ?>
  </ul>

</div>

<script src="js/darkmode.js"></script>
<script src="js/autorefresh.js"></script>

</body>
</html>
