<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = $_GET['id'];

    $article_query = "
        SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name
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
    <title><?= isset($article['title']) ? htmlspecialchars($article['title']) : 'Article Detail' ?> - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.7;
        }
        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }
        a:hover {
            color: #0056b3;
        }
        .container {
            max-width: 960px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
        }
        .article-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .article-header-left {
            flex-grow: 1;
        }
        .article-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }
        .article-meta {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .article-meta a {
            color: #007bff;
            text-decoration: none;
        }
        .article-meta a:hover {
            text-decoration: underline;
        }
        .article-actions a {
            margin-left: 1rem;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }
        .article-actions a:hover {
            color: #007bff;
        }
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #495057;
            margin-bottom: 2rem;
        }
        .share-icons {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        .share-icons h5 {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 1rem;
        }
        .share-icons a {
            display: inline-block;
            margin: 0 0.75rem;
            font-size: 1.5rem;
            color: #007bff;
            transition: color 0.2s ease-in-out;
        }
        .share-icons a:hover {
            color: #0056b3;
        }
        .back-link {
            margin-top: 1.5rem;
            display: inline-block;
        }
        .favorited {
            color: #e52b6f !important;
        }
        .bookmarked {
            color: #007BFF !important;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php else: ?>
        <div class="article-header">
            <div class="article-header-left">
                <h1><?= htmlspecialchars($article['title']) ?></h1>
                <p class="article-meta">
                    By <a href="#"><?= htmlspecialchars($article['author']) ?></a> |
                    Category:<?= htmlspecialchars($article['category_name']) ?></a> |
                    <i class="far fa-clock"></i> <?= date("F j, Y", strtotime($article['created_at'])) ?>
                </p>
            </div>
            <div class="article-actions">
                <a href="#" title="Add to Favorites" class="far fa-heart" onclick="toggleFavorite(<?= $article['id'] ?>, this); return false;"></a>
                <a href="#" title="Add to Bookmark" class="far fa-bookmark" onclick="toggleBookmark(<?= $article['id'] ?>, this); return false;"></a>
            </div>
        </div>
        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>

        <div class="share-icons">
            <h5>Share this article:</h5>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($article_url) ?>" target="_blank" rel="noopener noreferrer" class="fab fa-facebook"></a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($article_url) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener noreferrer" class="fab fa-twitter"></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($article_url) ?>&title=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener noreferrer" class="fab fa-linkedin"></a>
            <a href="whatsapp://send?text=<?= urlencode($article_url) ?>" target="_blank" class="fab fa-whatsapp"></a>
            <a href="mailto:?subject=<?= urlencode('Check out this article: ' . $article['title']) ?>&body=<?= urlencode('I thought you might find this interesting: ' . $article_url) ?>" class="fas fa-envelope"></a>
        </div>
    <?php endif; ?>

    <div class="back-link">
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Headlines</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleFavorite(articleId, iconElement) {
        iconElement.classList.toggle('far');
        iconElement.classList.toggle('fas');
        iconElement.classList.toggle('favorited');
    }

    function toggleBookmark(articleId, iconElement) {
        iconElement.classList.toggle('far');
        iconElement.classList.toggle('fas');
        iconElement.classList.toggle('bookmarked');
    }
</script>
</body>
</html>