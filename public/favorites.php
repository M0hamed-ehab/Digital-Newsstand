<?php
require_once '../src/Controllers/FavoritesController.php';

$controller = new FavoritesController();
$data = $controller->handleRequest();

$favorites = $data['favorites'];
$categories_result = $data['categories_result'];
$selected_category = $data['selected_category'];
$search_term = $data['search_term'];
$notifications_count = $data['notifications_count'];
$articleObj = $data['articleObj'];
$userObj = $data['userObj'];

require_once '../src/Views/favorites_view.php';
?>