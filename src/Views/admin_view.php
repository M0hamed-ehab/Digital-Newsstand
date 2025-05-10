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
    <?php if ($dark_mode): ?>
        <link rel="stylesheet" href="style/admin-dark.css">
    <?php endif; ?>
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

        <!-- USER STATS-->
        <div class="section mb-4">
            <h2>👥 User Statistics</h2>
            <button id="showUserStatsBtn" class="btn btn-info mb-3">Show User Stats</button>
            <div id="userStatsSummary" style="display:none;">
                <p>Total Users: <span
                        id="totalUsers"><?= isset($userStats['total_users']) ? $userStats['total_users'] : 0 ?></span>
                </p>
                <p>Subscribers: <span
                        id="subscribers"><?= isset($userStats['subscribers']) ? $userStats['subscribers'] : 0 ?></span>
                </p>
                <p>Admins: <span id="admins"><?= isset($userStats['admins']) ? $userStats['admins'] : 0 ?></span></p>
                <button id="showMoreUsersBtn" class="btn btn-secondary">Show More</button>
            </div>
            <div id="userDetails" style="display:none; max-height: 300px; overflow-y: auto; margin-top: 10px;">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Role</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($userStats['users']) && is_array($userStats['users'])): ?>
                            <?php foreach ($userStats['users'] as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No user data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

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
                        <?php foreach ($categoryList as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
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
                    <?php foreach ($articles as $row): ?>
                        <form method="POST">
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><input type="text" class="form-control" name="title"
                                        value="<?= htmlspecialchars($row['title']) ?>"></td>
                                <td><input type="text" class="form-control" name="content"
                                        value="<?= htmlspecialchars($row['content']) ?>"></td>
                                <td><input type="text" class="form-control" name="author"
                                        value="<?= htmlspecialchars($row['author']) ?>"></td>
                                <td>
                                    <select class="form-select" name="category_id">
                                        <?php foreach ($categoryList as $c): ?>
                                            <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $row['category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
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
                    <?php endforeach; ?>
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

    <script>
        document.getElementById('showUserStatsBtn').addEventListener('click', function () {
            var summary = document.getElementById('userStatsSummary');
            if (summary.style.display === 'none') {
                summary.style.display = 'block';
                this.textContent = 'Hide User Stats';
            } else {
                summary.style.display = 'none';
                this.textContent = 'Show User Stats';
                document.getElementById('userDetails').style.display = 'none';
                document.getElementById('showMoreUsersBtn').textContent = 'Show More';
            }
        });

        document.getElementById('showMoreUsersBtn').addEventListener('click', function () {
            var details = document.getElementById('userDetails');
            if (details.style.display === 'none') {
                details.style.display = 'block';
                this.textContent = 'Show Less';
            } else {
                details.style.display = 'none';
                this.textContent = 'Show More';
            }
        });
    </script>
</body>

</html>