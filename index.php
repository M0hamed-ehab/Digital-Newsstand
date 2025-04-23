<?php
include_once 'config/Database.php';

$db = (new Database())->connect();

$categories_query = "SELECT * FROM category";
$categories_result = $db->query($categories_query);

$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : 0;

$articles_query = "
    SELECT a.id, a.title, a.author, a.content, c.category_name
    FROM articles a
    LEFT JOIN category c ON a.category_id = c.category_id
    WHERE c.category_id = ? OR ? = 0
";
$stmt = $db->prepare($articles_query);
$stmt->bind_param("ii", $selected_category, $selected_category);
$stmt->execute();
$articles_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 60px 20px;
            text-align: center;
            border-bottom: 4px solid #0056b3;
        }

        header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        header p {
            font-size: 1.2rem;
        }

        .container {
            max-width: 1200px;
        }

        .row {
            margin-top: 40px;
        }

        .category-list {
            padding-left: 0;
            list-style: none;
            margin-top: 20px;
        }

        .category-list li {
            background-color: #ffffff;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 15px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .category-list li:hover {
            background-color: #007bff;
            color: white;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        }

        .category-list li.active {
            background-color: #0056b3;
            color: white;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #f1f1f1;
            padding: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .card-body {
            padding: 25px;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 500;
            color: #333;
        }

        .card-text {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .read-more {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            transition: background-color 0.3s;
        }

        .read-more:hover {
            background-color: #0056b3;
        }

        .col-md-3 {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .col-md-9 {
            padding-left: 20px;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column-reverse;
            }

            .col-md-3 {
                margin-bottom: 20px;
            }

            .col-md-9 {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header Section -->
    <header>
        <h1>News Website</h1>
        <p>Your go-to platform for the latest articles and stories</p>
    </header>

    <div class="row">
        <!-- Categories Section -->
        <div class="col-md-3">
            <h4>Categories</h4>
            <ul class="category-list">
                <li class="list-group-item" data-category-id="0">All Categories</li>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <li class="list-group-item" data-category-id="<?= $category['category_id'] ?>">
                        <?= htmlspecialchars($category['category_name']) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Articles Section -->
        <div class="col-md-9">
            <h4>Articles</h4>
            <?php if ($articles_result->num_rows > 0): ?>
                <?php while ($article = $articles_result->fetch_assoc()): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                            <small>By <?= htmlspecialchars($article['author']) ?> | Category: <?= htmlspecialchars($article['category_name']) ?></small>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))) ?>...</p>
                            <a href="article.php?id=<?= $article['id'] ?>" class="btn read-more">Read More</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No articles available in this category.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.category-list li').forEach(function (categoryItem) {
        categoryItem.addEventListener('click', function () {
            const categoryId = this.getAttribute('data-category-id');
            window.location.href = `?category_id=${categoryId}`;
            document.querySelectorAll('.category-list li').forEach(function(item) {
                item.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>

</body>
</html>
