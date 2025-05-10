<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Global Herald - Your Source for Trusted News</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="icon" type="image/png" href="/images/index.png">
        <link rel="stylesheet" href="../style/index.css">
        <?php if ($dark_mode): ?>
                <link rel="stylesheet" href="style/index-dark.css">
        <?php endif; ?>
</head>

<body>
        <?php include 'navbar.php'; ?>

        <?php
        $BNQ = $newsObj->getBNQ();
        $BNR = $db->query($BNQ);
        if ($BNR && $BNR->num_rows > 0):
                ?>
                <div class="breaking-news-strip bg-primary text-dark py-4 px-4 d-flex align-items-center justify-content-center"
                        style="overflow:hidden; white-space: nowrap; position: sticky;  top: 0;  width: 100%; z-index: 1050; font-size: 1.5rem; font-weight: 700; height: 60px;">
                        <div id="breakingNewsContent" style="white-space: nowrap; will-change: transform; color: #fff;">
                                <?php
                                echo "<strong>Breaking News:</strong> ";
                                $breaking_contents = '';
                                while ($row = $BNR->fetch_assoc()) {
                                        $breaking_contents = $row['content'];
                                }
                                echo $breaking_contents;
                                ?>
                        </div>
                </div>
        <?php endif; ?>

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
                                                                <li><a href="article.php?id=<?= $news['id'] ?>"><?= htmlspecialchars(substr($news['title'], 0, 50)) ?>...</a>
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
                                                                <li><a href="article.php?id=<?= $popular['id'] ?>"><?= htmlspecialchars(substr($popular['title'], 0, 50)) ?>...</a>
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
                                                        <li
                                                                class="<?= $selected_category == $category['category_id'] ? 'active' : '' ?>">
                                                                <a href="index.php?category_id=<?= $category['category_id'] ?>">
                                                                        <?= htmlspecialchars($category['category_name']) ?>
                                                                </a>
                                                        </li>
                                                <?php endwhile; ?>
                                        </ul>
                                </div>
                        </div>

                        <div class="col-md-9 main-content">
                                <h2><?= $heading_text ?></h2>
                                <?php if ($articles_result->num_rows > 0): ?>
                                        <?php while ($article = $articles_result->fetch_assoc()): ?>
                                                <div class="article-card">
                                                        <div class="article-header">
                                                                <h2><?= htmlspecialchars($article['title']) ?></h2>
                                                                <p class="article-meta">
                                                                        By <?= htmlspecialchars($article['author']) ?> |
                                                                        <i class="fas fa-folder"></i>
                                                                        <?= htmlspecialchars($article['category_name']) ?> |
                                                                        <i class="far fa-clock"></i>
                                                                        <?= date("F j, Y", strtotime($article['created_at'])) ?>
                                                                </p>
                                                        </div>
                                                        <div class="article-content">
                                                                <p><?= nl2br(htmlspecialchars($article['short_content'])) ?>...</p>
                                                                <a href="article.php?id=<?= $article['id'] ?>"
                                                                        class="read-more-btn">Read More <i
                                                                                class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                </div>
                                        <?php endwhile; ?>

                                        <?php
                                        $total_pages = ceil($total_articles / $articles_per_page);
                                        if ($total_pages > 1):
                                                ?>
                                                <nav aria-label="Page navigation">
                                                        <ul class="pagination justify-content-center">
                                                                <?php if ($page > 1): ?>
                                                                        <li class="page-item">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $page - 1 ?>"
                                                                                        aria-label="Previous">
                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                </a>
                                                                        </li>
                                                                <?php endif; ?>

                                                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $i ?>"><?= $i ?></a>
                                                                        </li>
                                                                <?php endfor; ?>

                                                                <?php if ($page < $total_pages): ?>
                                                                        <li class="page-item">
                                                                                <a class="page-link"
                                                                                        href="?category_id=<?= $selected_category ?>&search=<?= urlencode($search_term) ?>&page=<?= $page + 1 ?>"
                                                                                        aria-label="Next">
                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                </a>
                                                                        </li>
                                                                <?php endif; ?>
                                                        </ul>
                                                </nav>
                                        <?php endif; ?>

                                <?php else: ?>
                                        <p class="lead">No articles available in this category.</p>
                                <?php endif; ?>
                        </div>
                </div>
        </div>

        <!-- Latest -->
        <?php if ($show_ads): ?>
                <section class="ads-section">
                        <div class="container">
                                <h3 style="text-align: center; margin-bottom: 1rem;">Sponsored Ads</h3>
                                <div style="display: flex; justify-content: center; gap: 1rem;">
                                        <a href="subscription.php" target="_blank">

                                                <div
                                                        style="width: 100%; height: 20rem;  border-radius: 0.25rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                                        <img src="/images/g6.png" alt="Ad1"
                                                                style="width: 100%; height: 100%; object-fit: contain;">
                                                </div>
                                        </a>

                                </div>
                        </div>
                </section>
        <?php endif; ?>

        <section class="container-games" id="games">
                <div class="container">
                        <div class="row ify-content-center">
                                <div class="col-md-10 col-lg-8 p-b-20">
                                        <div class="how2 how2-cl4 flex-s-c m-r-10 m-r-0-sr991">
                                                <h2 class="section-title"><i class="fas fa-gamepad"></i> Explore
                                                        Exciting Games</h2>
                                        </div>

                                        <div class="row p-t-35">
                                                <?php if ($games_result && $games_result->num_rows > 0): ?>
                                                        <?php while ($game = $games_result->fetch_assoc()): ?>
                                                                <div class="col-sm-6 p-r-25 p-r-15-sr991">
                                                                        <div class="m-b-45">
                                                                                <a href="index.php?play=<?= htmlspecialchars($game['GameID']) ?>"
                                                                                        class="wrap-pic-w hov1 trans-03">
                                                                                        <img src="<?= htmlspecialchars($game['img']) ?>"
                                                                                                alt="<?= htmlspecialchars($game['Title']) ?>"
                                                                                                class="game-img">
                                                                                </a>
                                                                                <div class="p-t-16">
                                                                                        <h5 class="p-b-5">
                                                                                                <a href="index.php?play=<?= htmlspecialchars($game['GameID']) ?>"
                                                                                                        class="f1-m-3 cl2 hov-cl10 trans-03">
                                                                                                        <?= htmlspecialchars($game['Title']) ?>
                                                                                                </a>
                                                                                        </h5>
                                                                                        <span class="cl8">
                                                                                                <span class="f1-s-3">
                                                                                                        <?= nl2br(htmlspecialchars($game['Description'])) ?>
                                                                                                </span>
                                                                                        </span>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        <?php endwhile; ?>
                                                <?php else: ?>
                                                        <p class="lead">No games available at the moment.</p>
                                                <?php endif; ?>
                                        </div>
                                </div>
                        </div>
                </div>
        </section>

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

                }
                );
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
                integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
                crossorigin="anonymous" referrerpolicy="no-referrer" />


</body>

</html>