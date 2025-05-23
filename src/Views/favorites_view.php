<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Favorites - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="stylesheet" href="style/favs.css">
    <?php if ($dark_mode): ?>
        <link rel="stylesheet" href="style/favs-dark.css">
    <?php endif; ?>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">

        <div class="container mt-5">
            <h1>Your Favorites</h1>

            <?php if (isset($message)): ?>
                <div class="alert alert-info">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php else: ?>
                <div class="row">

                    <?php if (empty($favorites)): ?>
                        <div class="alert alert-danger" role="alert">
                            You have no Favorites yet. Start adding some to your Favorite
                        </div>
                    <?php else: ?>
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
                                        <a href="#" class="btn btn-danger"
                                            onclick="removeFromFavorites(<?= $favorite['id'] ?>); return false;">
                                            Remove from Favorites
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Headlines</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function removeFromFavorites(articleId) {
            fetch('favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=remove&article_id=${articleId}`
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'removed') {
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