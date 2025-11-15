<?php
require_once 'db/config.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) { 
    header('Location: ../index.php'); 
    exit; 
}

$user = $_SESSION['user'];

/* =========================
    CALL NEXT STUDENT
========================= */
if (isset($_POST['call_next'])) {

    // Find oldest waiting
    $next = $db->query("
        SELECT queue_id 
        FROM queue 
        WHERE status = 'waiting' 
        ORDER BY queue_id ASC 
        LIMIT 1
    ")->fetchColumn();

    if ($next) {
        $stmt = $db->prepare("UPDATE queue SET status='serving' WHERE queue_id=?");
        $stmt->execute([$next]);
    }

    header('Location: dashboard.php');
    exit;
}


/* =========================
    MARK AS SERVED
========================= */
if (isset($_POST['served'])) {

    $id = intval($_POST['queue_id']);

    // Ensure this queue item is actually serving
    $check = $db->prepare("SELECT * FROM queue WHERE queue_id=? AND status='serving'");
    $check->execute([$id]);

    if ($check->fetch()) {
        // Mark finished with timestamp
        $stmt = $db->prepare("
            UPDATE queue 
            SET status='served', time_out = NOW() 
            WHERE queue_id=?
        ");
        $stmt->execute([$id]);
    }

    // OPTIONAL: immediately call next in line
    $db->query("
        UPDATE queue 
        SET status='serving' 
        WHERE queue_id = (
            SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1
        )
        LIMIT 1
    ");

    header('Location: dashboard.php');
    exit;
}


/* =========================
    VOID QUEUE NUMBER
========================= */
if (isset($_POST['voided'])) {

    $id = intval($_POST['queue_id']);

    // Only allow voiding if currently serving or waiting
    $stmt = $db->prepare("
        UPDATE queue 
        SET status='voided' 
        WHERE queue_id=? AND (status='serving' OR status='waiting')
    ");
    $stmt->execute([$id]);

    // OPTIONAL: Call next automatically after void
    $db->query("
        UPDATE queue 
        SET status='serving' 
        WHERE queue_id = (
            SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1
        )
        LIMIT 1
    ");

    header('Location: dashboard.php');
    exit;
}


/* =========================
    FETCH DATA FOR DASHBOARD
========================= */

// Who is being served?
$serving = $db->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status='serving' 
    ORDER BY q.queue_id ASC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

// Who is waiting?
$waiting = $db->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status='waiting' 
    ORDER BY q.queue_id ASC
")->fetchAll(PDO::FETCH_ASSOC);

?>
