<?php
require_once '../src/Controllers/ArticleController.php';

$controller = new ArticleController();
$data = $controller->handleRequest();
$dark_mode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : false;

require_once '../src/Views/article_view.php';
?>