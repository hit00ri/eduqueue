<?php
require_once "db/config.php";
require_once "services/MetricsService.php";

if (!isset($_SESSION['user'])) { 
    header("Location: index.php"); 
    exit; 
}

$metricsService = new MetricsService($conn);

// Helper: check if a column exists in a table
function columnExists($conn, $table, $column) {
    $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    return $stmt->fetch() !== false;
}

$hasHandledBy = columnExists($conn, 'queue', 'handled_by');

// ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['call_next'])) {
        $next = $conn->query("SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1")
                     ->fetchColumn();

        if ($next) {
            // Get student_id for this queue
            $student_data = $conn->query("SELECT student_id FROM queue WHERE queue_id = {$next}")->fetch(PDO::FETCH_ASSOC);
            $student_id = $student_data ? $student_data['student_id'] : null;
            
            if ($hasHandledBy) {
                $stmt = $conn->prepare("UPDATE queue SET status='serving', handled_by = ? WHERE queue_id=?");
                $stmt->execute([$_SESSION['user']['user_id'], $next]);
            } else {
                $stmt = $conn->prepare("UPDATE queue SET status='serving' WHERE queue_id=?");
                $stmt->execute([$next]);
            }
            
            // Log the event
            if ($student_id) {
                $metricsService->logEvent('INFO', 'QUEUE', "Called next queue #{$next}", $_SESSION['user']['user_id'], $student_id, $next);
            } else {
                $metricsService->logEvent('INFO', 'QUEUE', "Called next queue #{$next}", $_SESSION['user']['user_id'], null, $next);
            }
        }
    }

    if (isset($_POST['served'])) {
        $id = intval($_POST['queue_id']);
        
        // Get student_id before updating
        $student_data = $conn->query("SELECT student_id FROM queue WHERE queue_id = {$id}")->fetch(PDO::FETCH_ASSOC);
        $student_id = $student_data ? $student_data['student_id'] : null;
        
        if ($hasHandledBy) {
            $stmt = $conn->prepare("UPDATE queue SET status='served', time_out = NOW(), handled_by = ? WHERE queue_id=?");
            $stmt->execute([$_SESSION['user']['user_id'], $id]);
        } else {
            $stmt = $conn->prepare("UPDATE queue SET status='served', time_out = NOW() WHERE queue_id=?");
            $stmt->execute([$id]);
        }
        
        // Record metrics for served queue
        if ($student_id) {
            $metricsService->recordQueueCompletion($id, $student_id, 'served');
            $metricsService->logEvent('INFO', 'QUEUE', "Marked queue #{$id} as served", $_SESSION['user']['user_id'], $student_id, $id);
        }
    }

    if (isset($_POST['voided'])) {
        $id = intval($_POST['queue_id']);
        
        // Get student_id before updating
        $student_data = $conn->query("SELECT student_id FROM queue WHERE queue_id = {$id}")->fetch(PDO::FETCH_ASSOC);
        $student_id = $student_data ? $student_data['student_id'] : null;
        
        if ($hasHandledBy) {
            $stmt = $conn->prepare("UPDATE queue SET status='voided', handled_by = ? WHERE queue_id=?");
            $stmt->execute([$_SESSION['user']['user_id'], $id]);
        } else {
            $stmt = $conn->prepare("UPDATE queue SET status='voided' WHERE queue_id=?");
            $stmt->execute([$id]);
        }
        
        // Record metrics for voided queue
        if ($student_id) {
            $metricsService->recordQueueCompletion($id, $student_id, 'voided');
            $metricsService->logEvent('WARNING', 'QUEUE', "Voided queue #{$id}", $_SESSION['user']['user_id'], $student_id, $id);
        }
    }

    header('Location: dashboard.php');
    exit;
}

// QUERY DATA
$serving = $conn->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status = 'serving' 
    ORDER BY q.queue_id ASC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

$waiting = $conn->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status = 'waiting' 
    ORDER BY q.queue_id ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Get cashier performance data (for display in dashboard)
$todayPerformance = [];
$todayEfficiency = 0;

if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['admin', 'cashier'])) {
    $cashier_id = $_SESSION['user']['user_id'];
    
    // Today's performance for this cashier (only if handled_by column exists)
    if ($hasHandledBy) {
        $todayPerformance = $conn->query(
        "SELECT 
            COUNT(*) as today_queues,
            SUM(CASE WHEN status = 'served' THEN 1 ELSE 0 END) as today_served,
            SUM(CASE WHEN status = 'voided' THEN 1 ELSE 0 END) as today_voided
        FROM queue 
        WHERE handled_by = {$cashier_id} 
        AND DATE(time_in) = CURDATE()"
    )->fetch(PDO::FETCH_ASSOC);
    } else {
        // Fallback: compute overall today's performance (cannot filter by cashier)
        $todayPerformance = $conn->query(
        "SELECT 
            COUNT(*) as today_queues,
            SUM(CASE WHEN status = 'served' THEN 1 ELSE 0 END) as today_served,
            SUM(CASE WHEN status = 'voided' THEN 1 ELSE 0 END) as today_voided
        FROM queue 
        WHERE DATE(time_in) = CURDATE()"
    )->fetch(PDO::FETCH_ASSOC);
    }
    
    if ($todayPerformance && $todayPerformance['today_queues'] > 0) {
        $todayEfficiency = round(($todayPerformance['today_served'] / $todayPerformance['today_queues']) * 100, 1);
    }
}
?>