<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

$query = "SELECT id FROM articles ORDER BY RAND() LIMIT 1";
$result = $db->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $random_article_id = $row['id'];
    header("Location: article.php?id=" . $random_article_id);
    exit();
} else {
    // No articles found, redirect to homepage
    header("Location: index.php");
    exit();
}
?>