<?php
require_once "api/student-login-b.php"
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>
<body>

<button class="dark-toggle"><i class="bi bi-moon-stars"></i></button>

<div class="login-container card fade-in">
    <h1><i class="bi bi-person-circle"></i> Student Login</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Student ID</label>
            <div class="input-group">
                <span class="input-group-text">
                    <span class="material-symbols-outlined">badge</span>
                </span>
                <input name="student_id" type="number" class="form-control" required>
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

        <button class="btn btn-primary w-100">
            <span class="material-symbols-outlined" style="vertical-align:middle">login</span>
            Login
        </button>
    </form>


    <p class="mt-3"><a href="index.php">← Back to Login</a></p>
</div>

<script src="js/darkmode.js"></script>
</body>
</html>
