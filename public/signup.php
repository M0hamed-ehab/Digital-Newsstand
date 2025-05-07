<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['loggedIn' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['loggedIn' => false]);
    }
    exit;
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once '../src/Controllers/SignupController.php';

$controller = new SignupController();
$controller->handleRequest();
?>