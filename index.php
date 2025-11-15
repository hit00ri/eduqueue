<?php
require_once "api/index-b.php"
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Queuing</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>
<body>
<button class="dark-toggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
<div class="login-container card fade-in">
  <div class="login-logo">
    <i class="bi bi-ticket-perforated" style="font-size:28px;color:var(--accent)"></i>
    <h1>github connection test</h1>
  </div>
  <?php if ($err): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($err)?></div>
  <?php endif; ?>

  <form method="post" class="mb-3">
      <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
              <span class="input-group-text">
                  <span class="material-symbols-outlined">person</span>
              </span>
              <input name="username" class="form-control" required>
          </div>
      </div>

      <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
              <span class="input-group-text">
                  <span class="material-symbols-outlined">lock</span>
              </span>
              <input name="password" type="password" class="form-control" required>
          </div>
      </div>

      <button class="btn btn-primary w-100 mt-3">
          <span class="material-symbols-outlined" style="vertical-align:middle">login</span>
          Login
      </button>

      <a href="student_login.php" class="btn btn-outline-secondary w-100 mt-3">
          <span class="material-symbols-outlined" style="vertical-align:middle">school</span>
          Student Login
      </a>
  </form>

</div>

<script src="js/darkmode.js"></script>
</body>
</html>
