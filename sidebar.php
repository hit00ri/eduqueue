<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<div class="sidebar fade-in">
    <h4 class="mb-4 sidebar-title">
        <span class="material-symbols-outlined nav-icon">dashboard</span>
        Queuing System
    </h4>

    <!-- Cashier & Admin: Queue Management -->
    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'cashier'])): ?>
    <a class="sidebar-link" href="dashboard.php">
        <span class="material-symbols-outlined nav-icon">record_voice_over</span>
        Queue Management
    </a>
    <?php endif; ?>

    <!-- Cashier & Admin: Basic Reports -->
    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'cashier'])): ?>
    <a class="sidebar-link" href="reports.php">
        <span class="material-symbols-outlined nav-icon">analytics</span>
        Daily Reports
    </a>
    <?php endif; ?>

    <!-- Admin Only: Advanced Metrics -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
    <a class="sidebar-link" href="metrics_dashboard.php">
        <span class="material-symbols-outlined nav-icon">monitoring</span>
        Performance Metrics
    </a>
    
    <a class="sidebar-link" href="system_logs.php">
        <span class="material-symbols-outlined nav-icon">list_alt</span>
        System Logs
    </a>
    
    <a class="sidebar-link" href="admin_reports.php">
        <span class="material-symbols-outlined nav-icon">assessment</span>
        Advanced Reports
    </a>
    <?php endif; ?>
</div>