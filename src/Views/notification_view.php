<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Notifications - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="icon" type="image/png" href="/images/bell.png" />
    <link rel="stylesheet" href="style/index.css" />

    <style>
        .container {
            margin: 5% 5% 10% 5%;
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
                    <li class="nav-item"></li>
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
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                <ul class="navbar-nav ms-auto">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li>
                            <a class="nav-link" href="random_article.php">Discover</a>
                        </li>
                    </ul>
                    <?php if ($userObj->isLoggedIn() || $userObj->isSignedUp()): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="noti.php" title="Notifications">
                                <i class="fas fa-bell fa-lg"></i>
                                <?php if ($notfications_count > 0): ?>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $notfications_count ?>
                                        <span class="visually-hidden">unread notifications</span>
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
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a>
                                </li>
                                <li><a class="dropdown-item" href="favorites.php"><i class="fas fa-heart me-2"></i>
                                        Favorites</a></li>
                                <li><a class="dropdown-item" href="bookmarks.php"><i class="fas fa-bookmark me-2"></i>
                                        Bookmarks</a></li>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin.php"><i class="fas fa-user-shield me-2"></i> Admin
                                            Panel</a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>
                                        Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="signup.html"><i class="fas fa-user-plus"></i> Signup</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Your Notifications</h2>

        <?php if (count($notfications) === 0): ?>
            <p>No notifications found.</p>
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
                <p>Are you sure you want to delete this notification?</p>
                <button id="confirm-delete" class="btn btn-danger me-2">Yes</button>
                <button id="cancel-delete" class="btn btn-secondary">No</button>
            </div>
            <form id="delete-form" method="POST" style="display:none;">
                <input type="hidden" name="delete_notfication_id" id="delete-notfication-id" value="" />
            </form>
        <?php endif; ?>
    </div>

    <?php if ($userObj->isLoggedIn() && count($notfications) > 0): ?>
        <div class="container mt-3">
            <form id="delete-all-form" method="POST" style="display:inline;">
                <input type="hidden" name="delete_all" value="1" />
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
        <p>&copy; <?= date("Y") ?> The Global Herald. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </p>
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