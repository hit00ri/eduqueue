<?php
require_once "db/config.php";

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $last = $db->query("SELECT queue_number FROM queue ORDER BY queue_id DESC LIMIT 1")->fetchColumn();
    $next = $last ? intval($last) + 1 : 1;
    $stmt = $db->prepare("INSERT INTO queue (student_id, queue_number, status, time_in) VALUES (?, ?, 'waiting', NOW())");
    $stmt->execute([$student_id, $next]);
    $message = 'Your queue number: ' . $next;
}

$students = $db->query("SELECT * FROM students ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$nowServing = $db->query("SELECT q.queue_number, s.name FROM queue q JOIN students s ON q.student_id=s.student_id WHERE q.status='serving' ORDER BY q.queue_id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>