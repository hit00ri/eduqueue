<?php
require_once "db/config.php";

if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit;
}

$student = $_SESSION['student'];
$message = "";

// Take queue number
if (isset($_POST['take_queue'])) {
    $last = $db->query("SELECT queue_number FROM queue ORDER BY queue_id DESC LIMIT 1")->fetchColumn();
    $next = $last ? $last + 1 : 1;

    $stmt = $db->prepare("INSERT INTO queue (student_id, queue_number, status) VALUES (?, ?, 'waiting')");
    $stmt->execute([$student['student_id'], $next]);

    $message = "Your queue number is: <strong>$next</strong>";
}

$nowServing = $db->query("
    SELECT q.queue_number, s.name 
    FROM queue q
    JOIN students s ON q.student_id=s.student_id
    WHERE q.status='serving'
    ORDER BY queue_id ASC LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>
