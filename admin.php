<?php
session_start();
include_once 'config/Database.php';
include_once 'classes/Article.php';
include_once 'classes/Category.php';

$db = (new Database())->connect();
$article = new Article($db);
$category = new Category($db);
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['create_category'])) {
    $category->name = $_POST['category_name'];
    $message = $category->create() ? "Category added successfully." : "Failed to add category.";
}
if (isset($_POST['delete_category'])) {
    $category->category_id = $_POST['category_id'];
    $message = $category->delete() ? "Category deleted." : "Failed to delete category.";
}

if (isset($_POST['create'])) {
    $article->title = $_POST['title'];
    $article->content = $_POST['content'];
    $article->author = $_POST['author'];
    $article->category_id = $_POST['category_id'];
    $message = $article->create() ? "Article published." : "Failed to publish article.";
}
if (isset($_POST['update'])) {
    $article->id = $_POST['id'];
    $article->title = $_POST['title'];
    $article->content = $_POST['content'];
    $article->author = $_POST['author'];
    $article->category_id = $_POST['category_id'];
    $message = $article->update() ? "Article updated." : "Failed to update article.";
}
if (isset($_POST['delete'])) {
    $article->id = $_POST['id'];
    $message = $article->delete() ? "Article deleted." : "Failed to delete article.";
}

if (isset($_POST['send'])) {
    $articleId = $_POST['id'];
    $stmt = $db->prepare("SELECT id FROM articles WHERE id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $subscribers = $db->query("SELECT email FROM users WHERE role = 'subscriber'");
        if ($subscribers && $subscribers->num_rows > 0) {
            $count = $subscribers->num_rows;
            $message = "Summary sent to $count subscribers.";
        } else {
            $message = "No subscribers found to send the summary.";
        }
    }
}

$categories = $category->readAll();
$articles = $article->readAll();
$categoryList = $category->readAll(); // For dropdown
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | News Website</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Open Sans', sans-serif;
            color: #333;
        }

        header {
            background-color: #1e3a8a;
            padding: 20px 0;
            text-align: center;
            color: #fff;
            font-family: 'Merriweather', serif;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        header p {
            font-size: 1.2rem;
        }

        .container {
            padding: 40px 15px;
        }

        .section {
            margin-top: 40px;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-family: 'Merriweather', serif;
            font-weight: 700;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            transition: 0.3s;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 8px rgba(30, 58, 138, 0.5);
        }

        .btn-custom {
            background: #1e3a8a;
            color: #fff;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #1e40af;
        }

        .input-group-text {
            background: #f1f1f1;
            border-radius: 8px 0 0 8px;
        }

        .form-check p {
            text-align: left;
            font-size: 14px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
            }
        }

        .bbbtn {
            display: flex;
            gap: 10px;
            flex-direction: row;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <header>
        <h1>Admin Panel | <a href="index.php" style="text-decoration: none; color: #fff;">News Website</a></h1>
        <p>Manage Your News Website</p>

    </header>

    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- CATEGORY MANAGEMENT -->
        <div class="section">
            <h2>📁 Manage Categories</h2>

            <form method="POST">
                <div class="mb-3">
                    <label for="category_name" class="form-label">New Category</label>
                    <input type="text" class="form-control" name="category_name" placeholder="Enter category name"
                        required>
                </div>
                <button type="submit" name="create_category" class="btn btn-custom w-100">Add Category</button>
            </form>

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $cat['category_id'] ?></td>
                                <td><?= htmlspecialchars($cat['category_name']) ?></td>
                                <td>
                                    <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                                    <button type="submit" name="delete_category" class="btn btn-danger">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ARTICLE MANAGEMENT -->
        <div class="section">
            <h2>📰 Manage Articles</h2>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Enter article title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea class="form-control" name="content" rows="5"
                        placeholder="Write the article content here..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" class="form-control" name="author" placeholder="e.g., John Doe" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = $categoryList->fetch_assoc()): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="create" class="btn btn-custom w-100">Publish Article</button>
            </form>

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $articles->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $row['id'] ?></td>
                                <td><input type="text" class="form-control" name="title"
                                        value="<?= htmlspecialchars($row['title']) ?>"></td>
                                <td><input type="text" class="form-control" name="content"
                                        value="<?= htmlspecialchars($row['content']) ?>"></td> <!-- Updated -->
                                <td><input type="text" class="form-control" name="author"
                                        value="<?= htmlspecialchars($row['author']) ?>"></td>
                                <td>
                                    <select class="form-select" name="category_id">
                                        <?php
                                        $cats = $category->readAll();
                                        while ($c = $cats->fetch_assoc()): ?>
                                            <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $row['category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['category_name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </td>
                                <td class="bbbtn">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                    <button type="submit" name="send" class="btn btn-primary">Send</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>