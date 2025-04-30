<?php
session_start();
include_once 'config/Database.php';

if (isset($_POST['user_id'], $_POST['article_id'], $_POST['action'])) {
    $user_id = $_POST['user_id'];
    $article_id = $_POST['article_id'];
    $action = $_POST['action'];

    $db = Database::getInstance()->getConnection();

    if ($action === 'add') {
        $query = "INSERT INTO bookmarks (user_id, article_id) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $user_id, $article_id);
        if ($stmt->execute()) {
            echo 'added';
        } else {
            echo 'error';
        }
    } elseif ($action === 'remove') {
        $query = "DELETE FROM bookmarks WHERE user_id = ? AND article_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $user_id, $article_id);
        if ($stmt->execute()) {
            echo 'removed';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
