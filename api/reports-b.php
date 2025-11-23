<?php
require_once "db/config.php";

// Check if MetricsService exists before including
$metricsServicePath = "services/MetricsService.php";
if (file_exists($metricsServicePath)) {
    require_once $metricsServicePath;
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Initialize metrics service if available
$metricsService = null;
if (class_exists('MetricsService')) {
    $metricsService = new MetricsService($conn);
}

// Get queue data
$data = $conn->query("
    SELECT q.*, s.name 
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    ORDER BY q.queue_id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Get transaction data
$transactions = $conn->query("
    SELECT * FROM transactions 
    ORDER BY date_paid DESC 
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Counts - FIXED VERSION (using your existing queue table)
$servedCount = $conn->query("
    SELECT COUNT(*) as count FROM queue 
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

$waitingCount = $conn->query("
    SELECT COUNT(*) as count FROM queue 
    WHERE status = 'waiting' AND DATE(time_in) = CURDATE()
")->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// Try to get today's summary if metrics tables exist
$todaySummary = null;
try {
    $todaySummary = $conn->query("
        SELECT * FROM daily_kpi_summary 
        WHERE summary_date = CURDATE()
    ")->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table doesn't exist yet, that's okay
    $todaySummary = null;
}

// If metrics service is available, generate summary
if ($metricsService && !$todaySummary) {
    try {
        $metricsService->generateDailyKPISummary(date('Y-m-d'));
    } catch (Exception $e) {
        // Ignore errors for now
    }
}
?>