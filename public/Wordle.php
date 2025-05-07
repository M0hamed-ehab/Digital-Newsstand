<?php

include_once '../config/Database.php';
include_once '../src/Models/Article.php';
include_once '../src/Models/User.php';
include_once '../src/Models/Admin.php';

$db = Database::getInstance()->getConnection();


$articleObj = new Article($db);
$userObj = new User($db);

$adminObj = new Admin($db);

$categories_result = $adminObj->getCategories();
$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$notfications_count = $userObj->getNotificationsCount();
$show_ads = $userObj->shouldShowAds();


function isUserLoggedIn()
{
    global $userObj;
    return $userObj->isLoggedIn();
}

function isSignedUp()
{
    global $userObj;
    return $userObj->isSignedUp();
}


?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/games.png">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/games.css">


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
                <ul class="navbar-nav ms-auto">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li>
                            <?php
                            $randomArticleId = $articleObj->getRandomArticleId();
                            ?>
                            <a class="nav-link" href="article.php?id=<?= $randomArticleId ?>">Discover</a>
                        </li>
                    </ul>
                    <?php if ($userObj->isLoggedIn() || $userObj->isSignedUp()): ?>
                        <li class="
                                                                nav-item">
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
                                <li><a
                                        href="article.php?id=<?= $popular['id'] ?>"><?= htmlspecialchars(substr($popular['title'], 0, 50)) ?>...</a>
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
                            <li class="<?= $selected_category == $category['category_id'] ? 'active' : '' ?>">
                                <a href="index.php?category_id=<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>

            <div class="game-container" id="wordle-game" style="margin-top: 2rem; max-width: 400px;">

                <h3 id="h3w">Wordle Game</h3>
                <p>Guess the 5-letter word in 6 tries.</p>

                <div id="wordle-grid"
                    style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; margin-bottom: 1rem;">
                    <!-- 6 rows of 5 cells each -->
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                    <div class="wordle-cell"></div>
                </div>

                <input type="text" id="wordle-input" maxlength="5"
                    style="text-transform: uppercase; width: 100%; padding: 0.5rem; font-size: 1.2rem; letter-spacing: 0.3rem;"
                    autofocus autocomplete="off" />
                <button id="wordle-submit" class="btn btn-primary mt-2" style="width: 100%;">Submit Guess</button>
                <p id="wordle-message" style="margin-top: 1rem; font-weight: bold;"></p>

                <style>

                </style>

            </div>

            <?php if ($show_ads): ?>
                <section class="ads-section"
                    style="background-color: #f8f9fa;  padding: 1rem;  border: 1px solid #ddd; border-radius: 0.5rem; width: fit-content; ">
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






        </div>
    </div>
    </div>



















    <footer>
        <p>&copy; <?= date("Y") ?> The Global Herald. <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </footer>
    <script src="worlde.js"></script>

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

</body>

</html>