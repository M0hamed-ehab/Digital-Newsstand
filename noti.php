<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

$categories_query = "SELECT * FROM category ORDER BY category_name ASC";
$categories_result = $db->query($categories_query);

$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search_term !== '') {
    $search_term_like = '%' . $search_term . '%';
    $articles_query = "
        SELECT a.id, a.title, a.author, SUBSTR(a.content, 1, 300) AS short_content, a.created_at,
        c.category_name
        FROM articles a
        LEFT JOIN category c ON a.category_id = c.category_id
        WHERE a.content LIKE '$search_term_like'
        ORDER BY a.created_at DESC
    ";
    $articles_result = $db->query($articles_query);
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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
function isUserLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isSignedUp()
{
    return isset($_SESSION['just_signed_up']) && $_SESSION['just_signed_up'] === true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['delete_notfication_id']) || isset($_POST['delete_all']))) {
    $user_id = $_SESSION['user_id'] ?? 0;

    if ($user_id > 0) {
        if (isset($_POST['delete_notfication_id'])) {
            $notfication_id = intval($_POST['delete_notfication_id']);
            $delete_stmt = $db->prepare("DELETE FROM notfications WHERE notfication_id = ? AND user_id = ?");
            $delete_stmt->bind_param("ii", $notfication_id, $user_id);
            $delete_stmt->execute();
            $delete_stmt->close();
        } elseif (isset($_POST['delete_all'])) {
            $delete_all_stmt = $db->prepare("DELETE FROM notfications WHERE user_id = ?");
            $delete_all_stmt->bind_param("i", $user_id);
            $delete_all_stmt->execute();
            $delete_all_stmt->close();
        }
    }

    header("Location: noti.php");
    exit();
}

$notfications = [];
if (isUserLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $notif_stmt = $db->prepare("SELECT notfication_id, article_id, notfication_description FROM notfications WHERE user_id = ? ORDER BY notfication_id DESC");
    $notif_stmt->bind_param("i", $user_id);
    $notif_stmt->execute();
    $notif_result = $notif_stmt->get_result();
    while ($row = $notif_result->fetch_assoc()) {
        $notfications[] = $row;
    }
    $notif_stmt->close();
}
$dbx = (new Database())->connect();
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
    <title>The Global Herald - Your Source for Trusted News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url('images/g5.jpg');
            background-size: auto;
            color: #343a40;
            line-height: 1.7;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .navbar {
            background-color: #343a40;
            padding: 0.75rem 1rem;
        }

        .navbar-nav .nav-link {
            color: #fff;
            padding: 0.7rem 1.2rem;
            transition: background-color 0.3s ease-in-out;
            border-radius: 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 5% 5% 10% 5%;
            padding: 1.5rem;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
        }

        .row {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
            justify-content: center;
        }

        .sidebar {
            flex: 0 0 28%;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .sidebar h4 {
            color: #212529;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-list li {
            margin-bottom: 0.6rem;
            padding: 0.7rem 1rem;
            border-radius: 0.3rem;
            transition: background-color 0.2s ease-in-out;
        }

        .category-list li a {
            color: #495057;
            display: block;
        }

        .category-list li:hover {
            background-color: #e9ecef;
        }

        .category-list li.active {
            background-color: #007bff;
        }

        .category-list li.active a {
            color: #fff;
        }

        .main-content {
            flex: 1;
            padding: 1rem;
        }

        .article-card {
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.2s ease-in-out;
        }

        .article-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .article-header {
            background-color: #f8f9fa;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
        }

        .article-header h2 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: #212529;
            font-weight: 500;
            line-height: 1.3;
        }

        .article-meta {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .article-content {
            padding: 1.25rem;
        }

        .read-more-btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 0.7rem 1.4rem;
            border-radius: 0.3rem;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background-color 0.2s ease-in-out;
        }

        .read-more-btn:hover {
            background-color: #0056b3;
        }

        .breaking-news {
            background-color: #fdf0d5;
            border: 1px solid #fbeed5;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            max-height: 15rem;
        }

        .breaking-news h4 {
            color: #d68f00;
            border-bottom: 2px solid #ffe699;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .breaking-news ul {
            list-style: none;
            padding: 0;
        }

        .breaking-news li {
            padding: 0.6rem 0;
            border-bottom: 1px dashed #ffe699;
        }

        .breaking-news li:last-child {
            border-bottom: none;
        }

        .popular-articles {
            background-color: #e7f4fd;
            border: 1px solid #d1e9ff;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            max-height: 15rem;
        }

        .popular-articles h4 {
            color: #1c83c4;
            border-bottom: 2px solid #bfe2ff;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .popular-articles ul {
            list-style: none;
            padding: 0;
        }

        .popular-articles li {
            padding: 0.6rem 0;
            border-bottom: 1px dashed #bfe2ff;
        }

        .popular-articles li:last-child {
            border-bottom: none;
        }

        .container-games {
            padding: 2rem 0;
            margin: 0 auto;
        }

        .game-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .game-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .game-item img:hover {
            transform: scale(1.05);
        }

        .game-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-top: 0.5rem;
        }

        .game-description {
            font-size: 0.9rem;
            color: #555;
        }

        .col-sm-6 {
            flex: 0 0 auto;
            width: 48%;
        }

        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #343a40;
        }

        footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
            background-color: #343a40;
            color: #fff;
            border-top: 0.25rem solid #007bff;
            font-size: 0.9rem;
        }

        footer a {
            color: #fff;
            text-decoration: underline;
        }

        footer a:hover {
            color: #f8f9fa;
        }

        @media (max-width: 992px) {
            .row {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                margin-bottom: 1.5rem;
            }
        }

        .user-icons {
            display: flex;
            align-items: center;
        }

        .user-icons .nav-link {
            padding: 0.7rem 0.8rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            min-width: 10rem;
            z-index: 1000;
            display: none;
        }

        .dropdown-menu[data-bs-popper] {
            left: -360%;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        .user-dropdown-menu a.dropdown-item {
            display: block;
            width: 100%;
            padding: 0.25rem 1.5rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            text-decoration: none;
        }

        .user-dropdown-menu a.dropdown-item:hover,
        .user-dropdown-menu a.dropdown-item:focus {
            background-color: #e9ecef;
            color: #1e2125;
        }
    </style>
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
                                href="?category_id=<?= $category['category_id'] ?>">
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
                <ul class="navbar-nav ms-auto">
                    <?php if (isUserLoggedIn() || isSignedUp()): ?>
                        <li class="nav-item">
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
                                Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-4">
        <h2>Your Notfications</h2>

        <?php if (count($notfications) === 0): ?>
            <p>No notfications found.</p>
        <?php else: ?>
            <div id="notfications-list" class="list-group">
                <?php foreach ($notfications as $notfication): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center"
                        data-notfication-id="<?= $notfication['notfication_id'] ?>">
                        <div class="notfication-description"><?= htmlspecialchars($notfication['notfication_description']) ?>
                        </div>
                        <div>
                            <a href="article.php?id=<?= $notfication['article_id'] ?>"
                                class="btn btn-primary btn-sm me-2">Read</a>
                            <button class="btn btn-danger btn-sm delete-btn">X</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="delete-confirmation" class="alert alert-warning mt-3 d-none">
                <p>Are you sure you want to delete this notfication?</p>
                <button id="confirm-delete" class="btn btn-danger me-2">Yes</button>
                <button id="cancel-delete" class="btn btn-secondary">No</button>
            </div>
            <form id="delete-form" method="POST" style="display:none;">
                <input type="hidden" name="delete_notfication_id" id="delete-notfication-id" value="">
            </form>
        <?php endif; ?>
    </div>

    <?php if (isUserLoggedIn() && count($notfications) > 0): ?>
        <div class="container mt-3">
            <form id="delete-all-form" method="POST" style="display:inline;">
                <input type="hidden" name="delete_all" value="1">
                <button type="button" id="delete-all-btn" class="btn btn-danger">Delete All</button>
            </form>
            <div id="delete-all-confirmation" class="alert alert-warning mt-3 d-none">
                <p>Are you sure you want to delete all your notifications?</p>
                <button id="confirm-delete-all" class="btn btn-danger me-2">Yes</button>
                <button id="cancel-delete-all" class="btn btn-secondary">No</button>
            </div>
        </div>
    <?php endif; ?>


















    <footer>
        <p>&copy; <?= date("Y") ?> The Global Herald. <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const sidebarCategoryLinks = document.querySelectorAll('.category-list li a');
            const currentCategoryId = urlParams.get('category_id');

            sidebarCategoryLinks.forEach(link => {
                if ((currentCategoryId === null && link.getAttribute('href') === 'index.php') || link.getAttribute('href') === '?category_id=' + currentCategoryId) {
                    link.parentElement.classList.add('active');
                }
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />




    <script src="noti.js"></script>
</body>

</html>