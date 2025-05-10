<?php
require_once '../src/Controllers/NotificationController.php';

$controller = new NotificationController();
$data = $controller->handleRequest();

$categories_result = $data['categories_result'];
$selected_category = $data['selected_category'];
$search_term = $data['search_term'];
$notifications_count = $data['notifications_count'];
$notfications = $data['notfications'];
$articleObj = $data['articleObj'];
$userObj = $data['userObj'];
$dark_mode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : false;

require_once '../src/Views/notification_view.php';
?>