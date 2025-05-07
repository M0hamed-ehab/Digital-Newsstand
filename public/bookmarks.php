<?php
require_once '../src/Controllers/BookmarksController.php';

$controller = new BookmarksController();
$data = $controller->handleRequest();

$bookmarks = $data['bookmarks'];
$categories_result = $data['categories_result'];
$selected_category = $data['selected_category'];
$search_term = $data['search_term'];
$notifications_count = $data['notifications_count'];
$articleObj = $data['articleObj'];
$userObj = $data['userObj'];

require_once '../src/Views/bookmarks_view.php';
?>