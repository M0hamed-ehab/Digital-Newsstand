<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

$categories_query = "SELECT * FROM category ORDER BY category_name ASC";
$categories_result = $db->query($categories_query);

$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

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

$breaking_news_query = "SELECT title FROM articles ORDER BY created_at DESC";
$breaking_news_result = $db->query($breaking_news_query);

$popular_articles_query = "SELECT id, title FROM articles ORDER BY id DESC";
$popular_articles_result = $db->query($popular_articles_query);
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

        .container-games {
            padding: 2rem 0;
            margin: 0 auto;
        }

        #sudoku-board {
            border-collapse: collapse;
            width: 60%;
            height: 450px;
            table-layout: fixed;
            margin: 1rem 0 4vh;
        }

        #sudoku-board td {
            border: 1px solid #999;
            width: 50px;
            height: 50px;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        #sudoku-board input {
            width: 100%;
            height: 100%;
            border: none;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            outline: none;
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
                width: 50%;
                margin-bottom: 1.5rem;
            }
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
                        <a class="nav-link <?= $selected_category == 0 ? 'active' : '' ?>" aria-current="page"
                            href="index.php">Home</a>

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
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php" title="Edit Profile">
                            <i class="fas fa-user"></i>
                        </a>
                    </li>
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
                        <?php if ($breaking_news_result && $breaking_news_result->num_rows > 0): ?>
                            <?php while ($news = $breaking_news_result->fetch_assoc()): ?>
                                <li><a href="#">
                                        <?= htmlspecialchars(substr($news['title'], 0, 60)) ?>...
                                    </a></li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>No breaking news at the moment.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="popular-articles">
                    <h4><i class="fas fa-fire"></i> Trending Stories</h4>
                    <ul>
                        <?php if ($popular_articles_result && $popular_articles_result->num_rows > 0): ?>
                            <?php while ($popular = $popular_articles_result->fetch_assoc()): ?>
                                <li><a href="article.php?id=<?= $popular['id'] ?>">
                                        <?= htmlspecialchars(substr($popular['title'], 0, 50)) ?>...
                                    </a></li>
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
            <div class="container-game">
                <h2>Sudoku Game</h2>
                <div>
                    <table id="sudoku-board"></table>
                </div>
                <button type="button" onclick="checkSolution()">Check Solution</button>
                <button type="button" onclick="fillSolution()">Get Solution</button>
                <button type="button" onclick="resetBoard()">Reset</button>
                <p id="result">here</p>
            </div>
        </div>
    </div>
    </div>















    <script src="sudoku.js"></script>

</body>

</html>