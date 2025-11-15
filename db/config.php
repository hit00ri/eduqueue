<?php
// config.php - update DB credentials as needed
session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'schema2';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('DB connection error: ' . $e->getMessage());
}
?>