<?php
require_once "db/config.php";

if (!isset($_SESSION['student'])) {
    header("Location: student_login.php");
    exit;
}

$student = $_SESSION['student'];
$message = "";

// TAKE QUEUE NUMBER
if (isset($_POST['take_queue'])) {
    // Get the last queue number for today
    $last = $conn->query("
        SELECT queue_number FROM queue 
        WHERE DATE(time_in) = CURDATE() 
        ORDER BY queue_id DESC LIMIT 1
    ")->fetchColumn();

    $next = $last ? $last + 1 : 1;

    $stmt = $conn->prepare("
        INSERT INTO queue (student_id, queue_number, status) 
        VALUES (?, ?, 'waiting')
    ");
    
    $stmt->execute([$student['student_id'], $next]);

    $message = "Your queue number is: <strong>$next</strong>";
}

// NOW SERVING
$nowServing = $conn->query("
    SELECT q.queue_number, s.name 
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    WHERE q.status = 'serving'
    ORDER BY queue_id ASC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>