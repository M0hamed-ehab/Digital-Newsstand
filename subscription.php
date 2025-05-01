<?php
session_start();
include_once 'config/Database.php';
include_once 'classes/Article.php';
include_once 'classes/User.php';
include_once 'classes/Subscription.php';

$db = Database::getInstance()->getConnection();

$articleObj = new Article($db);
$userObj = new User($db);

$categories_result = $articleObj->getCategories();

$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$subscription = new Subscription($db, $user_id);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['plan'])) {
        $new_plan = $_POST['plan'];
        $message = $subscription->handlePlanChange($new_plan);
        header("Location: subscription.php?message=" . urlencode($message));
        exit();
    } elseif (isset($_POST['auto_renew'])) {
        $auto_renew = $_POST['auto_renew'];
        $message = $subscription->handleAutoRenewChange($auto_renew);
        header("Location: subscription.php?message=" . urlencode($message));
        exit();
    }
}

$highlight_plan = $subscription->getHighlightPlan();
$auto_renew = $subscription->getAutoRenew();
$articles = $subscription->getTodayArticles();
$notfications_count = $subscription->getNotificationsCount();

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

function planClass($plan, $highlight_plan)
{
    global $subscription;
    return $subscription->planClass($plan);
}

function buttonLabel($plan, $highlight_plan)
{
    global $subscription;
    return $subscription->buttonLabel($plan);
}

$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscription Plans - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/subs.png">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/subs.css">
    <script src="subs.js"></script>
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
        <h1>Subscription Plans</h1>










        <form id="subscriptionForm" method="POST" action="subscription.php">
            <input type="hidden" name="plan" id="planInput" value="">
            <section class="plans">
                <div class="<?php echo planClass('free', $highlight_plan); ?> plan-card" id="free">
                    <h2>Free</h2>
                    <p class="price">0 EGP</p>
                    <p>Free!</p>

                    <ul class="features">
                        <li>Access Articles</li>
                        <li>Translation</li>
                        <li>Games</li>
                        <li>Ads</li>
                    </ul>
                    <button type="button" <?php echo $highlight_plan === 'free' ? 'disabled' : ''; ?>
                        onclick="confirmChange('free')" class="select-btn">
                        <?php echo buttonLabel('free', $highlight_plan); ?>
                    </button>
                </div>
                <div class="<?php echo planClass('premium', $highlight_plan); ?> plan-card" id="premium">
                    <h2>Premium</h2>
                    <label style="font-size: small; color: gray;">Popular</label>
                    <p class="price">10 EGP</p>
                    <p>Billed monthly. Ideal for light readers.</p>
                    <ul class="features">
                        <li>Recieve Email Updates</li>
                        <li>Download Articles</li>
                        <li>Ads</li>
                    </ul>
                    <button type="button" <?php echo $highlight_plan === 'premium' ? 'disabled' : ''; ?>
                        onclick="confirmChange('premium')" class="select-btn">
                        <?php echo buttonLabel('premium', $highlight_plan); ?>
                    </button>
                </div>
                <div class="<?php echo planClass('premium_plus', $highlight_plan); ?> plan-card" id="premium_plus">
                    <h2>Premium+</h2>
                    <p class="price">50 EGP</p>
                    <p>Billed monthly. Perfect for avid readers and families.</p>
                    <ul class="features">
                        <li>Daily Briefing</li>
                        <li>Priority customer support</li>
                        <li>No Ads</li>

                        <li></li>
                    </ul>
                    <button type="button" <?php echo $highlight_plan === 'premium_plus' ? 'disabled' : ''; ?>
                        onclick="confirmChange('premium_plus')" class="select-btn">
                        <?php echo buttonLabel('premium_plus', $highlight_plan); ?>
                    </button>
                </div>
            </section>
        </form>

        <?php if ($highlight_plan === 'premium_plus' || $highlight_plan === 'premium'): ?>
            <div class="auto-renew-container">
                <h2>Auto-Renewal</h2>
                <form id="autoRenewForm" method="POST" action="subscription.php">
                    <input type="hidden" name="auto_renew" id="autoRenewInput"
                        value="<?php echo $auto_renew ? '1' : '0'; ?>">
                    <label class="auto-renew-label" for="autoRenewToggle">Enable Auto-Renewal:</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="autoRenewToggle" <?php echo $auto_renew ? 'checked' : ''; ?>
                            onchange="toggleAutoRenew(this)">
                        <span class="slider"></span>
                    </label>
                </form>
            </div>
            <?php if ($highlight_plan === 'premium_plus'): ?>

                <div id="Daily">
                    <h2>Daily</h2>
                    <?php if (count($articles) > 0): ?>
                        <?php foreach ($articles as $article): ?>
                            <article>
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No news articles for today.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div id="messageBox"></div>

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




</body>

</html>