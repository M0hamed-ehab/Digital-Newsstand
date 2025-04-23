<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = $_GET['id'];
    
    $article_query = "
        SELECT a.id, a.title, a.author, a.content, c.category_name
        FROM articles a
        LEFT JOIN category c ON a.category_id = c.category_id
        WHERE a.id = ?
    ";
    
    $stmt = $db->prepare($article_query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $article_result = $stmt->get_result();

    if ($article_result->num_rows > 0) {
        $article = $article_result->fetch_assoc();
    } else {
        $error_message = "Article not found.";
    }
} else {
    $error_message = "Invalid article ID.";
}

$article_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
        }
        .card {
            margin-top: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .share-icons a {
            margin-right: 10px;
            margin-left: 10px;
            font-size: 1.5rem;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <!-- Header Section -->
    <header class="text-center mb-4">
        <h1>Article Detail</h1>
    </header>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php else: ?>
        <!-- Full Article -->
        <div class="card">
            <div class="card-header">
                <h3><?= htmlspecialchars($article['title']) ?></h3>
                <small>By <?= htmlspecialchars($article['author']) ?> | Category: <?= htmlspecialchars($article['category_name']) ?></small>
            </div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
            </div>
        </div>

        <!-- Share Icons -->
        <div class="share-icons mt-4 text-center">
            <h5>Share this article:</h5>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($article_url) ?>" target="_blank" class="fab fa-facebook"></a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($article_url) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" class="fab fa-twitter"></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($article_url) ?>&title=<?= urlencode($article['title']) ?>" target="_blank" class="fab fa-linkedin"></a>
            <a href="whatsapp://send?text=<?= urlencode($article_url) ?>" target="_blank" class="fab fa-whatsapp"></a>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">Back to Articles</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
