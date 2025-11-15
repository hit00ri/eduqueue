<?php
require_once "../db/config.php";

session_destroy();
header("Location: ../index.php");
exit;
?>