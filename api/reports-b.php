<?php
require_once "db/config.php";
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$servedCount = $db->query("SELECT COUNT(*) FROM queue WHERE status='served'")->fetchColumn();
$waitingCount = $db->query("SELECT COUNT(*) FROM queue WHERE status='waiting'")->fetchColumn();
$transactions = $db->query("SELECT * FROM transactions ORDER BY date_paid DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
?>