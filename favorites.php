<?php
session_start();
include_once 'config/Database.php';

$db = (new Database())->connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name
    FROM favorites f
    JOIN articles a ON f.article_id = a.id
    LEFT JOIN category c ON a.category_id = c.category_id
    WHERE f.user_id = ?
    ORDER BY a.created_at DESC
";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$favorites_result = $stmt->get_result();

// Check if favorites exist
if ($favorites_result->num_rows > 0) {
    $favorites = $favorites_result->fetch_all(MYSQLI_ASSOC);
} else {
    $message = "You have no favorites yet.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Favorites - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h1>Your Favorite Articles</h1>
    
    <?php if (isset($message)): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favorites as $favorite): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($favorite['title']) ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($favorite['content']), 0, 100) ?>...</p>
                            <p class="text-muted">
                                Category: <?= htmlspecialchars($favorite['category_name']) ?> | 
                                By <?= htmlspecialchars($favorite['author']) ?> | 
                                <small><?= date("F j, Y", strtotime($favorite['created_at'])) ?></small>
                            </p>
                            <a href="article.php?id=<?= $favorite['id'] ?>" class="btn btn-primary">Read More</a>
                            <a href="#" class="btn btn-danger" onclick="removeFromFavorites(<?= $favorite['id'] ?>); return false;">Remove from Favorites</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-3">
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Headlines</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function removeFromFavorites(articleId) {
        const userId = '<?php echo $_SESSION['user_id']; ?>';

        fetch('remove_from_favorites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}&article_id=${articleId}`
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'removed') {
                alert('Article removed from favorites.');
                location.reload();
            } else {
                alert('Failed to remove the article. Please try again.');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while removing the article.');
        });
    }
</script>

</body>
</html>
