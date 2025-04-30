<?php
include_once 'config/Database.php';
include_once 'classes/Article.php';
include_once 'classes/Category.php';

$db = Database::getInstance()->getConnection();
$article = new Article($db);
$category = new Category($db);

if (isset($_POST['create_category'])) {
    $message = $category->create($_POST['category_name'])
        ? "Category added successfully."
        : "Failed to add category.";
}

if (isset($_POST['delete_category'])) {
    $message = $category->delete($_POST['category_id'])
        ? "Category deleted."
        : "Failed to delete category.";
}

if (isset($_POST['create'])) {
    $message = $article->createFromForm($_POST, $_FILES)
        ? "Article published."
        : "Failed to publish article.";
}

if (isset($_POST['update'])) {
    $message = $article->updateFromForm($_POST)
        ? "Article updated."
        : "Failed to update article.";
}

if (isset($_POST['delete'])) {
    $message = $article->deleteById($_POST['id'])
        ? "Article deleted."
        : "Failed to delete article.";
}

if (isset($_POST['send'])) {
    $message = $article->sendSummary($_POST['id']);
}

$categories = $category->readAll();
$articles = $article->readAll();
$categoryList = $category->readAll(); // For dropdown


// Handle breaking news creation
if (isset($_POST['create_breaking_news'])) {
    $content = trim($_POST['breaking_content']);
    $duration = intval($_POST['breaking_duration']);
    if ($duration < 1 || $duration > 60) {
        $message = "Duration must be between 1 and 60 minutes.";
    } elseif (empty($content)) {
        $message = "Content cannot be empty.";
    } else {
        $stmt = $db->prepare("INSERT INTO breaking_news (content, duration) VALUES (?, ?)");
        $stmt->bind_param("si", $content, $duration);
        if ($stmt->execute()) {
            $message = "Breaking news created successfully.";
        } else {
            $message = "Failed to create breaking news.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - The Global Herald</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="/images/admin.png">
    <link rel="stylesheet" href="./style/admin.css">
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
            <form method="POST" enctype="multipart/form-data">
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

            <form method="POST" enctype="multipart/form-data">
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
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" name="image" placeholder="e.g., .jpg">
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
                        <form method="POST">
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><input type="text" class="form-control" name="title" value="<?= htmlspecialchars($row['title']) ?>"></td>
                                <td><input type="text" class="form-control" name="content" value="<?= htmlspecialchars($row['content']) ?>"></td>
                                <td><input type="text" class="form-control" name="author" value="<?= htmlspecialchars($row['author']) ?>"></td>
                                <td>
                                    <select class="form-select" name="category_id">
                                        <?php
                                        $cats = $category->readAll();
                                        while ($c = $cats->fetch_assoc()):
                                        ?>
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
                            </tr>
                        </form>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
        <!-- BREAKING NEWS MANAGEMENT -->
        <div class="section">
            <h2>🚨 Create Breaking News</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="breaking_content" class="form-label">Content</label>
                    <textarea class="form-control" id="breaking_content" name="breaking_content" rows="3"
                        placeholder="Enter breaking news content" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="breaking_duration" class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control" id="breaking_duration" name="breaking_duration" min="1"
                        max="60" value="1" required>
                    <div class="form-text">Duration must be between 1 and 60 minutes.</div>
                </div>
                <button type="submit" name="create_breaking_news" class="btn btn-custom w-100">Create Breaking
                    News</button>
            </form>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>