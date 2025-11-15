<!-- <?php
require_once "api/student-b.php"
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student - Queuing</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/student.css">
</head>
<body>
<button class="dark-toggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>

<div class="student-box card fade-in">
  <h1><i class="bi bi-people nav-icon"></i> Student Queue</h1>
  <?php if ($message): ?>
    <div class="alert alert-success"><?=htmlspecialchars($message)?></div>
  <?php endif; ?>

  <h4>Now Serving</h4>
  <?php if ($nowServing): ?>
    <p><strong>#<?= $nowServing['queue_number'] ?></strong> — <?= htmlspecialchars($nowServing['name']) ?></p>
  <?php else: ?>
    <p>None</p>
  <?php endif; ?>

  <form method="post" class="mt-3">
    <div class="mb-3">
      <label class="form-label">Select Student</label>
      <select name="student_id" class="form-select" required>
        <?php foreach ($students as $s): ?>
          <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary"><i class="bi bi-ticket-perforated"></i> Take a Queue</button>
  </form>

  <p class="mt-3"><a href="index.php" class="btn btn-link">Back to login</a></p>
</div>

<script src="js/darkmode.js"></script>
</body>
</html> -->
