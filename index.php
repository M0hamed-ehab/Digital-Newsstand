<?php
include_once 'config/Database.php';
include_once 'classes/Article.php';

$db = Database::getInstance()->getConnection();
$articleObj = new Article($db);

$categories_result = $articleObj->getCategories();

$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;

$selected_category_name = '';
if ($selected_category !== 0) {
        $categories_result->data_seek(0);
        while ($category = $categories_result->fetch_assoc()) {
                if ($category['category_id'] == $selected_category) {
                        $selected_category_name = $category['category_name'];
                        break;
                }
        }
}

if ($search_term !== '') {
        $heading_text = "Results for: \"" . htmlspecialchars($search_term) . "\"";
} elseif ($selected_category !== 0 && $selected_category_name !== '') {
        $heading_text = "Category: " . htmlspecialchars($selected_category_name);
} else {
        $heading_text = "Latest Headlines";
}

$articles_per_page = 5;

$total_articles = $articleObj->getTotalArticles($search_term, $selected_category);
$articles_result = $articleObj->getArticles($search_term, $selected_category, $page, $articles_per_page);

$breaking_news_query = "SELECT title FROM articles ORDER BY created_at DESC";
$breaking_news_result = $db->query($breaking_news_query);

$popular_articles_query = "SELECT id, title FROM articles ORDER BY id DESC";
$popular_articles_result = $db->query($popular_articles_query);

if (session_status() === PHP_SESSION_NONE) {
        session_start();
}

$show_ads = true;
if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sub_stmt = $db->prepare("SELECT subscription_name FROM subscription WHERE user_id = ?");
        $sub_stmt->bind_param("i", $user_id);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();
        if ($sub_result && $row = $sub_result->fetch_assoc()) {
                if ($row['subscription_name'] === 'full') {
                        $show_ads = false;
                }
        }
        $sub_stmt->close();
}

function isUserLoggedIn()
{
        return isset($_SESSION['user_id']);
}

function isSignedUp()
{
        return isset($_SESSION['just_signed_up']) && $_SESSION['just_signed_up'] === true;
}




$notfications_count = 0;
if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM notfications WHERE user_id = ?");
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
        <link rel="icon" type="image/png" href="/images/home.png">
        <link rel="stylesheet" href="./index.css">
</head>

<body>

        <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">
                                <i class="fas fa-newspaper"></i> The Global Herald
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                aria-label="Toggle navigation">
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
                                        <input class="form-control me-2" type="search" name="search"
                                                placeholder="Search" aria-label="Search"
                                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                        <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                                <ul class="navbar-nav ms-auto">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                                                <li>
                                                        <?php
                                                        $randomArticleId = $articleObj->getRandomArticleId();
                                                        ?>
                                                        <a class="nav-link"
                                                                href="article.php?id=<?= $randomArticleId ?>">Discover</a>
                                                </li>
                                        </ul>
                                        <?php if (isUserLoggedIn() || isSignedUp()): ?>
                                                <li class="
                                                                nav-item">
                                                        <a class="nav-link position-relative" href="noti.php"
                                                                title="Notifications">
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
                                                        <a class="nav-link" href="#" id="userDropdown" role="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                                title="User Menu">
                                                                <i class="fas fa-user-circle fa-lg"></i>
                                                        </a>
                                                        <ul class="dropdown-menu user-dropdown-menu"
                                                                aria-labelledby="userDropdown">
                                                                <li><a class="dropdown-item" href="profile.php"><i
                                                                                        class="fas fa-user me-2"></i>
                                                                                Profile</a></li>
                                                                <li><a class="dropdown-item" href="favorites.php"><i
                                                                                        class="fas fa-heart me-2"></i>
                                                                                Favorites</a></li>
                                                                <li><a class="dropdown-item" href="bookmarks.php"><i
                                                                                        class="fas fa-bookmark me-2"></i>
                                                                                Bookmarks</a></li>
                                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                                                        <li><a class="dropdown-item" href="admin.php"><i
                                                                                                class="fas fa-user-shield me-2"></i>
                                                                                        Admin Panel</a></li>
                                                                <?php endif; ?>
                                                                <li>
                                                                        <hr class="dropdown-divider">
                                                                </li>
                                                                <li><a class="dropdown-item" href="logout.php"><i
                                                                                        class="fas fa-sign-out-alt me-2"></i>
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

        <?php
        $BNQ = "
            SELECT content FROM breaking_news
            WHERE NOW() < DATE_ADD(creation_date, INTERVAL duration MINUTE)
            ORDER BY creation_date DESC
        ";
        $BNR = $db->query($BNQ);
        if ($BNR && $BNR->num_rows > 0):
                ?>
                <div class="breaking-news-strip bg-primary text-dark py-4 px-4 d-flex align-items-center justify-content-center"
                        style="overflow:hidden; white-space: nowrap; position: sticky;  top: 0;  width: 100%; z-index: 1050; font-size: 1.5rem; font-weight: 700; height: 60px;">
                        <div id="breakingNewsContent" style="white-space: nowrap; will-change: transform; color: #fff;">
                                <?php
                                echo "<strong>Breaking News:</strong> ";
                                $breaking_contents = '';
                                while ($row = $BNR->fetch_assoc()) {
                                        $breaking_contents = $row['content'];
                                }
                                echo $breaking_contents;
                                ?>
                        </div>
                </div>
        <?php endif; ?>

        <div class="container mt-4">
                <div class="row">
                        <div class="col-md-3 sidebar">
                                <div class="breaking-news">
                                        <h4><i class="fas fa-bolt"></i> Breaking News</h4>
                                        <ul>
                                                <?php
                                                $breaking_news_query = "SELECT id, title FROM articles ORDER BY created_at DESC LIMIT 3";
                                                $breaking_news_result = $db->query($breaking_news_query);

                                                if ($breaking_news_result && $breaking_news_result->num_rows > 0): ?>
                                                        <?php while ($news = $breaking_news_result->fetch_assoc()): ?>
                                                                <li><a href="#"><?= htmlspecialchars(substr($news['title'], 0, 60)) ?>...</a>
                                                                </li>
                                                        <?php endwhile; ?>
                                                <?php else: ?>
                                                        <li>No breaking news at the moment.</li>
                                                <?php endif; ?>
                                        </ul>
                                </div>

                                <div class="popular-articles">
                                        <h4><i class="fas fa-fire"></i> Trending Stories</h4>
                                        <ul>
                                                <?php
                                                $popular_articles_query = "SELECT id, title FROM articles ORDER BY views DESC LIMIT 3";
                                                $popular_articles_result = $db->query($popular_articles_query);

                                                if ($popular_articles_result && $popular_articles_result->num_rows > 0): ?>
                                                        <?php while ($popular = $popular_articles_result->fetch_assoc()): ?>
                                                                <li><a href="article.php?id=<?= $popular['id'] ?>"><?= htmlspecialchars(substr($popular['title'], 0, 50)) ?>...</a>
                                                                </li>
                                                        <?php endwhile; ?>
                                                <?php else: ?>
                                                        <li>No trending stories yet.</li>
                                                <?php endif; ?>
                                        </ul>
                                </div>

                                <div>
                                        <h4><i class="fas fa-list-alt"></i> Categories</h4>
                                        <ul class="category-list">
                                                <li class="<?= $selected_category == 0 ? 'active' : '' ?>">
                                                        <a href="index.php">All</a>
                                                </li>
                                                <?php
                                                $categories_result->data_seek(0); // Reset again for the category list
                                                while ($category = $categories_result->fetch_assoc()): ?>
                                                        <li
                                                                class="<?= $selected_category == $category['category_id'] ? 'active' : '' ?>">
                                                                <a href="index.php?category_id=<?= $category['category_id'] ?>">
                                                                        <?= htmlspecialchars($category['category_name']) ?>
                                                                </a>
                                                        </li>
                                                <?php endwhile; ?>
                                        </ul>
                                </div>
                        </div>

                        <div class="col-md-9 main-content">
                                <h2><?= $heading_text ?></h2>
                                <?php if ($articles_result->num_rows > 0): ?>
                                        <?php while ($article = $articles_result->fetch_assoc()): ?>
                                                <div class="article-card">
                                                        <div class="article-header">
                                                                <h2><?= htmlspecialchars($article['title']) ?></h2>
                                                                <p class="article-meta">
                                                                        By <?= htmlspecialchars($article['author']) ?> |
                                                                        <i class="fas fa-folder"></i>
                                                                        <?= htmlspecialchars($article['category_name']) ?> |
                                                                        <i class="far fa-clock"></i>
                                                                        <?= date("F j, Y", strtotime($article['created_at'])) ?>
                                                                </p>
                                                        </div>
                                                        <div class="article-content">
                                                                <p><?= nl2br(htmlspecialchars($article['short_content'])) ?>...</p>
                                                                <a href="article.php?id=<?= $article['id'] ?>"
                                                                        class="read-more-btn">Continue Reading <i
                                                                                class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                </div>
                                        <?php endwhile; ?>

                                        <!-- Pagination Controls -->
                                        <?php
                                        $total_pages = ceil($total_articles / $articles_per_page);
                                        if ($total_pages > 1):
                                                ?>
                                                <nav aria-label="Page navigation">
                                                        <ul class="pagination justify-content-center">
                                                                <?php if ($page > 1): ?>
                                                                        <li class="page-item">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $page - 1 ?>"
                                                                                        aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                </a>
                                                                        </li>
                                                                <?php endif; ?>

                                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $i ?>"><?= $i ?></a>
                                                                        </li>
                                                                <?php endfor; ?>

                                                                <?php if ($page < $total_pages): ?>
                                                                        <li class="page-item">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $page + 1 ?>"
                                                                                        aria-label="Next">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                </a>
                                                                        </li>
                                                                <?php endif; ?>
                                                        </ul>
                                                </nav>
                                        <?php endif; ?>

                                <?php else: ?>
                                        <p class="lead">No articles available in this category.</p>
                                <?php endif; ?>
                        </div>
                </div>
        </div>

        <!-- Latest -->
        <?php if ($show_ads): ?>
                <section class="ads-section"
                        style="background-color: #f8f9fa; justify-self: center; padding: 1rem; margin: 2rem 2rem; border: 1px solid #ddd; border-radius: 0.5rem; width: fit-content; align-self: center;">
                        <div class="container">
                                <h3 style="text-align: center; margin-bottom: 1rem;">Sponsored Ads</h3>
                                <div style="display: flex; justify-content: center; gap: 1rem;">
                                        <a href="subscription.php" target="_blank">

                                                <div
                                                        style="width: 100%; height: 20rem;  border-radius: 0.25rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                                        <img src="images/g6.png" alt="Ad1"
                                                                style="width: 100%; height: 100%; object-fit: contain;">
                                                </div>
                                        </a>

                                </div>
                        </div>
                </section>
        <?php endif; ?>

        <section class="container-games" id="games">
                <div class="container">
                        <div class="row ify-content-center">
                                <div class="col-md-10 col-lg-8 p-b-20">
                                        <div class="how2 how2-cl4 flex-s-c m-r-10 m-r-0-sr991">
                                                <h2 class="section-title"><i class="fas fa-gamepad"></i> Explore
                                                        Exciting Games</h2>
                                        </div>

                                        <div class="row p-t-35">
                                                <div class="col-sm-6 p-r-25 p-r-15-sr991">
                                                        <!-- Item latest -->
                                                        <div class="m-b-45">
                                                                <a href="sudoku.php#h2t"
                                                                        class="wrap-pic-w hov1 trans-03">
                                                                        <img src="images/g1.jpg" alt="Sudoku"
                                                                                class="game-img">
                                                                </a>

                                                                <div class="p-t-16">
                                                                        <h5 class="p-b-5">
                                                                                <a href="sudoku.php#h2t"
                                                                                        class="f1-m-3 cl2 hov-cl10 trans-03">
                                                                                        Sodoku
                                                                                </a>
                                                                        </h5>
                                                                        <span class="cl8">


                                                                                <span class="f1-s-3">
                                                                                        A logic-based number puzzle
                                                                                        where you fill a 9×9 grid so
                                                                                        each row,
                                                                                        column, and 3×3 box contains
                                                                                        digits 1 to 9 without repetition
                                                                                </span>
                                                                        </span>

                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col-sm-6 p-r-25 p-r-15-sr991">
                                                        <!-- Item latest -->
                                                        <div class="m-b-45">
                                                                <a href="XO.php#game-container"
                                                                        class="wrap-pic-w hov1 trans-03">
                                                                        <img src="images/g2.jpg" alt="XO"
                                                                                class="game-img">
                                                                </a>

                                                                <div class="p-t-16">
                                                                        <h5 class="p-b-5">
                                                                                <a href="XO.php#game-container"
                                                                                        class="f1-m-3 cl2 hov-cl10 trans-03">
                                                                                        Tic Tac Toe
                                                                                </a>
                                                                        </h5>
                                                                        <span class="cl8">


                                                                                <span class="f1-s-3">
                                                                                        You play against the computer,
                                                                                        trying to align three X's or O's
                                                                                        while the
                                                                                        computer blocks or counters your
                                                                                        moves </span>
                                                                        </span>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col-sm-6 p-r-25 p-r-15-sr991">
                                                        <!-- Item latest -->
                                                        <div class="m-b-45">
                                                                <a href="Wordle.php#h3w"
                                                                        class="wrap-pic-w hov1 trans-03">
                                                                        <img src="images/g3.webp" alt="Wordle"
                                                                                class="game-img">
                                                                </a>

                                                                <div class="p-t-16">
                                                                        <h5 class="p-b-5">
                                                                                <a href="Wordle.php#h3w"
                                                                                        class="f1-m-3 cl2 hov-cl10 trans-03">
                                                                                        Wordle
                                                                                </a>
                                                                        </h5>

                                                                        <span class="cl8">


                                                                                <span class="f1-s-3">
                                                                                        A word puzzle where you guess a
                                                                                        five-letter word in six tries,
                                                                                        with color hints for accuracy
                                                                                </span>
                                                                        </span>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col-sm-6 p-r-25 p-r-15-sr991">
                                                        <!-- Item latest -->
                                                        <div class="m-b-45">
                                                                <a href="Mine.php#h2m" class="wrap-pic-w hov1 trans-03">
                                                                        <img src="images/g4.jpg" alt="Minesweeper"
                                                                                class="game-img">
                                                                </a>

                                                                <div class="p-t-16">
                                                                        <h5 class="p-b-5">
                                                                                <a href="Mine.php#h2m"
                                                                                        class="f1-m-3 cl2 hov-cl10 trans-03">
                                                                                        Minesweeper
                                                                                </a>
                                                                        </h5>

                                                                        <span class="cl8">


                                                                                <span class="f1-s-3">
                                                                                        A puzzle game where you uncover
                                                                                        tiles on a grid, avoiding hidden
                                                                                        mines and
                                                                                        using number clues to find safe
                                                                                        spots
                                                                                </span>
                                                                        </span>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </section>

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

                }
                );
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
                integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />


</body>

</html>