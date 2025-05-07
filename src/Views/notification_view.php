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
    <?php include 'navbar.php'; ?>

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