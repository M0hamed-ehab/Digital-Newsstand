<?php
require_once '../src/Controllers/AdminController.php';

$controller = new AdminController();

$message = $controller->message;
$categories = $controller->categories;
$articles = $controller->articles;
$categoryList = $controller->categoryList;
$dark_mode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : false;
require_once '../src/Views/admin_view.php';
?>