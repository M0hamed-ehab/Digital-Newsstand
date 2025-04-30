<?php
session_start();
include_once 'config/Database.php';
include_once 'classes/user_favs.php';

$userFavorites = new user_favs();
$favorites = $userFavorites->getUserFavorites();





$db = Database::getInstance()->getConnection();

$categories_query = "SELECT * FROM category ORDER BY category_name ASC";
$categories_result = $db->query($categories_query);

$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search_term !== '') {
    $search_term_like = '%' . $search_term . '%';

    $count_query = "SELECT COUNT(*) as total FROM articles a LEFT JOIN category c ON a.category_id = c.category_id WHERE a.content LIKE ? OR a.author LIKE ? OR c.category_name LIKE ?";
    $count_stmt = $db->prepare($count_query);
    $count_stmt->bind_param("sss", $search_term_like, $search_term_like, $search_term_like);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_articles = 0;
    if ($count_result && $row = $count_result->fetch_assoc()) {
        $total_articles = $row['total'];
    }
    $count_stmt->close();

    $articles_query = "
    SELECT a.id, a.title, a.author, SUBSTR(a.content, 1, 300) AS short_content, a.created_at,
    c.category_name
    FROM articles a
    LEFT JOIN category c ON a.category_id = c.category_id
    WHERE a.content LIKE ? OR a.author LIKE ? OR c.category_name LIKE ?
    ORDER BY a.created_at DESC
    LIMIT ? OFFSET ?
";
    $stmt = $db->prepare($articles_query);
    $stmt->bind_param("sssii", $search_term_like, $search_term_like, $search_term_like, $articles_per_page, $offset);
    $stmt->execute();
    $articles_result = $stmt->get_result();
} else {
    $articles_query = "
        SELECT a.id, a.title, a.author, SUBSTR(a.content, 1, 300) AS short_content, a.created_at,
        c.category_name
        FROM articles a
        LEFT JOIN category c ON a.category_id = c.category_id
        WHERE c.category_id = ? OR ? = 0
        ORDER BY a.created_at DESC
    ";
    $stmt = $db->prepare($articles_query);
    $stmt->bind_param("ii", $selected_category, $selected_category);
    $stmt->execute();
    $articles_result = $stmt->get_result();
}

$breaking_news_query = "SELECT title FROM articles ORDER BY created_at DESC";
$breaking_news_result = $db->query($breaking_news_query);

$popular_articles_query = "SELECT id, title FROM articles ORDER BY id DESC";
$popular_articles_result = $db->query($popular_articles_query);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isSignedUp()
{
    return isset($_SESSION['just_signed_up']) && $_SESSION['just_signed_up'] === true;
}




$dbx = Database::getInstance()->getConnection();
$notfications_count = 0;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $dbx->prepare("SELECT COUNT(*) as count FROM notfications WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $notfications_count = $row['count'];
    }
    $stmt->close();
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
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="stylesheet" href="./favs.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-newspaper"></i> The Global Herald
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    </li>
                    <?php
                    $categories_result->data_seek(0); // Reset the result set pointer
                    while ($category = $categories_result->fetch_assoc()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $selected_category == $category['category_id'] ? 'active' : '' ?>"
                                href="index.php?category_id=<?= $category['category_id'] ?>">
                                <?= htmlspecialchars($category['category_name']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    <li>
                        <a class="nav-link" href="index.php#games">Games</a>
                    </li>

                </ul>
                <form class="d-flex" method="GET" action="index.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search"
                        aria-label="Search"
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn btn-outline-light" type="submit">Search</button>

                </form>
                <ul class=" navbar-nav ms-auto">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li>
                            <a class="nav-link" href="random_article.php" ">Discover</a>
                        </li>
                    </ul>
                                <?php if (isUserLoggedIn() || isSignedUp()): ?>
                                            <li class=" nav-item">
                                    <a class="nav-link position-relative" href="noti.php" title="Notifications">
                                        <i class="fas fa-bell fa-lg"></i>
                                        <?php if ($notfications_count > 0): ?>
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                <?= $notfications_count ?>
                                                <span class="visually-hidden">unread
                                                    notifications</span>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                            </li>
                            <li class="nav-item user-dropdown">
                                <a class="nav-link" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" title="User Menu">
                                    <i class="fas fa-user-circle fa-lg"></i>
                                </a>
                                <ul class="dropdown-menu user-dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>
                                            Profile</a></li>
                                    <li><a class="dropdown-item" href="favorites.php"><i class="fas fa-heart me-2"></i>
                                            Favorites</a></li>
                                    <li><a class="dropdown-item" href="bookmarks.php"><i class="fas fa-bookmark me-2"></i>
                                            Bookmarks</a></li>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="admin.php"><i class="fas fa-user-shield me-2"></i>
                                                Admin Panel</a></li>
                                    <?php endif; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                            Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.html"><i class="fas fa-sign-in-alt"></i>
                                    Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="signup.html"><i class="fas fa-user-plus"></i>
                                    Signup</a>
                            </li>
                        <?php endif; ?>
                    </ul>
            </div>
        </div>
    </nav>
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
                                        <a href="#" class="btn btn-danger" onclick="removeFromFavorites(<?= $favorite['id'] ?>); return false;">
                                            Remove from Bookmarks
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