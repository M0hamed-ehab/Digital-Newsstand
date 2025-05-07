<?php
require_once '../src/Controllers/ArticleController.php';

$controller = new ArticleController();
$data = $controller->handleRequest();

require_once '../src/Views/article_view.php';
?>
