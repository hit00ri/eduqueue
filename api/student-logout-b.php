<?php
require_once "../db/config.php";
unset($_SESSION['student']);
header("Location: ../student_login.php");
exit;
?>
