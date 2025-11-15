<?php
require_once "api/student-dashboard-b.php"
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/student.css">
</head>
<body>

<button class="dark-toggle"><i class="bi bi-moon-stars"></i></button>

<div class="student-box card fade-in">
    <h1><i class="bi bi-person-circle"></i> Welcome, <?= $student['name'] ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <h4>Now Serving</h4>

    <?php if ($nowServing): ?>
        <p>#<?= $nowServing['queue_number'] ?> — <?= $nowServing['name'] ?></p>
    <?php else: ?>
        <p>No one is being served.</p>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <button name="take_queue" class="btn btn-primary">
            <i class="bi bi-ticket-perforated"></i> Take a Queue Number
        </button>
    </form>

    <p class="mt-3"><a href="api/student-logout-b.php">Logout</a></p>
</div>

<script src="js/darkmode.js"></script>
<script src="js/autorefresh.js"></script>
</body>
</html>
