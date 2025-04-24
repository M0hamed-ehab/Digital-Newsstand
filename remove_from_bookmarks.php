<?php
session_start();
include_once 'config/Database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['article_id'])) {
    echo 'error';
    exit();
}

$user_id = $_SESSION['user_id'];
$article_id = $_POST['article_id'];

$db = (new Database())->connect();

$query = "DELETE FROM bookmarks WHERE user_id = ? AND article_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("ii", $user_id, $article_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo 'removed';
} else {
    echo 'error';
}
?>
