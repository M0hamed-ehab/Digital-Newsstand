<?php
session_start();
include_once 'config/Database.php';
$database = new Database();
$conn = $database->connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>