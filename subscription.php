<?php
session_start();
include_once 'config/Database.php';







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











if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['plan'])) {
        $new_plan = $_POST['plan'];

        $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $role = $row['role'];

        if ($role === 'admin') {
            if ($new_plan === 'free') {
                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'none' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium') {
                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'semi' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium_plus') {
                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'full' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            }
            $message = "Your subscription has been changed to " . ucfirst(str_replace('_', ' ', $new_plan)) . ".";
        } else {
            if ($new_plan === 'free') {
                $update_role = $conn->prepare("UPDATE users SET role = 'member' WHERE user_id = ?");
                $update_role->bind_param("i", $user_id);
                $update_role->execute();
                $_SESSION['role'] = 'member';

                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'none' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium') {
                $update_role = $conn->prepare("UPDATE users SET role = 'subscriber' WHERE user_id = ?");
                $update_role->bind_param("i", $user_id);
                $update_role->execute();
                $_SESSION['role'] = 'subscriber';

                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'semi' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium_plus') {
                $update_role = $conn->prepare("UPDATE users SET role = 'subscriber' WHERE user_id = ?");
                $update_role->bind_param("i", $user_id);
                $update_role->execute();
                $_SESSION['role'] = 'subscriber';

                $update_sub = $conn->prepare("UPDATE subscription SET subscription_name = 'full' WHERE user_id = ?");
                $update_sub->bind_param("i", $user_id);
                $update_sub->execute();
            }
            $message = "Your subscription has been changed to " . ucfirst(str_replace('_', ' ', $new_plan)) . ".";
        }
        header("Location: subscription.php?message=" . urlencode($message));
        exit();
    } elseif (isset($_POST['auto_renew'])) {
        $auto_renew = $_POST['auto_renew'] === '1' ? 1 : 0;
        $stmt = $conn->prepare("UPDATE subscription SET auto_renew = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $auto_renew, $user_id);
        $stmt->execute();
        $message = "Auto-renewal has been " . ($auto_renew ? "enabled" : "disabled") . ".";
        header("Location: subscription.php?message=" . urlencode($message));
        exit();
    }
}

$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$role = $row['role'];

$highlight_plan = 'free';

if ($role === 'member') {
    $highlight_plan = 'free';
} elseif ($role === 'subscriber' || $role === 'admin') {
    $stmt_sub = $conn->prepare("SELECT subscription_name, auto_renew FROM subscription WHERE user_id = ?");
    $stmt_sub->bind_param("i", $user_id);
    $stmt_sub->execute();
    $result_sub = $stmt_sub->get_result();
    if ($result_sub->num_rows > 0) {
        $sub_row = $result_sub->fetch_assoc();
        $subscription_name = $sub_row['subscription_name'];
        $auto_renew = $sub_row['auto_renew'];
        if ($subscription_name === 'none') {
            $highlight_plan = 'free';
        } elseif ($subscription_name === 'semi') {
            $highlight_plan = 'premium';
        } elseif ($subscription_name === 'full') {
            $highlight_plan = 'premium_plus';
        }
    } else {
        $highlight_plan = 'free';
        $auto_renew = 0;
    }
} else {
    $highlight_plan = 'free';
    $auto_renew = 0;
}

$today = date('Y-m-d');
$articles = [];
$stmt_articles = $conn->prepare("SELECT title, content FROM articles WHERE DATE(created_at) = ?");
$stmt_articles->bind_param("s", $today);
$stmt_articles->execute();
$result_articles = $stmt_articles->get_result();
while ($row_article = $result_articles->fetch_assoc()) {
    $articles[] = $row_article;
}

function planClass($plan, $highlight_plan)
{
    return $plan === $highlight_plan ? 'plan highlight' : 'plan';
}

function buttonLabel($plan, $highlight_plan)
{
    return $plan === $highlight_plan ? 'Your Plan' : 'Choose Plan';
}

$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
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
    <title>Subscription Plans - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/subs.png">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url('images/g5.jpg');
            background-size: auto;
            color: #343a40;
            line-height: 1.7;
        }

        #result {
            position: absolute;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        a:hover {
            color: #0056b3;
        }

        #sudoku-board {

            margin: 20px auto;
            width: 80%;
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
            margin: 0 auto;
            padding: 1.5rem;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
        }

        .row {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
            flex-wrap: nowrap;
            align-items: center;
            flex-direction: row;
        }

        .sidebar {
            flex: 0 0 25%;
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

        .read-more-btn,
        button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 0.7rem 1.4rem;
            border-radius: 0.3rem;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background-color 0.2s ease-in-out;
        }

        .read-more-btn:hover,
        button:hover {
            background-color: #0056b3;
        }

        .breaking-news {
            background-color: #fdf0d5;
            border: 1px solid #fbeed5;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
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

        #container-games {
            padding: 2rem 0;
            margin: 0 auto;
        }

        .row>* {
            max-width: 70%;
        }

        h1 {
            margin: 2%;
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


            .sidebar {
                width: 50%;
                margin-bottom: 1.5rem;
            }
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


        .plans {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .plan {
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            width: 200px;
            text-align: center;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .plan.highlight {
            border-color: #007bff;
            box-shadow: 0 0 10px #007bff;
            background-color: #e6f0ff;
        }

        .plan h2 {
            margin-top: 0;
        }

        .plan p {
            font-size: 14px;
            color: #555;
        }

        .plan button {
            margin-top: 15px;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .plan button:hover:not(:disabled) {
            background-color: #0056b3;
        }

        .plan button:disabled {
            background-color: #6c757d;
            cursor: default;
        }

        /* Message box styles */
        #messageBox {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #007bff;
            color: white;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            font-size: 18px;
            display: none;
            z-index: 1000;
        }

        #messageBox button {
            margin: 10px;
            padding: 8px 16px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #messageBox .closeBtn {
            background-color: #28a745;
            color: white;
        }

        /* Auto-renew toggle styles */
        .auto-renew-container {
            margin: 30px auto;
            width: 300px;
            text-align: center;
        }

        .auto-renew-label {
            font-size: 16px;
            margin-right: 10px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #007bff;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        #Daily {
            margin: 40px auto;
            width: 80%;
            max-width: 800px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            min-height: 150px;
        }

        #Daily h2 {
            margin-top: 0;
        }








        .plans {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px;
        }

        .plan-card {
            background: white;
            border: 2px solid #ddd;
            border-radius: 10px;
            width: 250px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        #premium {
            border-color: #B8860B;

        }

        .plan-card h2 {
            font-size: 22px;
            margin: 10px 0;
        }

        .plan-card .price {
            font-size: 26px;
            font-weight: bold;
            color: rgb(32, 113, 218);
            margin: 10px 0;
        }

        .plan-card button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }


        .plan-card .features {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .plan-card .features li {
            font-size: 16px;
            line-height: 1.6;
            padding-left: 20px;
            position: relative;
            margin-bottom: 10px;
        }

        .plan-card .features li::before {
            content: "✔";
            font-size: 18px;
            color: rgb(30, 67, 235);
            position: absolute;
            left: 0;
            top: 0;
        }

        .plan-card .features li:hover {
            color: #333;
            cursor: pointer;
            background-color: #f0f0f0;
            border-radius: 4px;
        }
    </style>
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