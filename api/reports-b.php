<?php
require_once "db/config.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
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

// Counts
$servedCount = $conn->query("
    SELECT COUNT(*) as count FROM queue 
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetch(PDO::FETCH_ASSOC)['count'];

$waitingCount = $conn->query("
    SELECT COUNT(*) as count FROM queue 
    WHERE status = 'waiting' AND DATE(time_in) = CURDATE()
")->fetch(PDO::FETCH_ASSOC)['count'];
?>