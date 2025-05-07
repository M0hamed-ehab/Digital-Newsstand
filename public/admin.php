<?php
require_once '../src/Controllers/AdminController.php';

$controller = new AdminController();

$message = $controller->message;
$categories = $controller->categories;
$articles = $controller->articles;
$categoryList = $controller->categoryList;

require_once '../src/Views/admin_view.php';
?>