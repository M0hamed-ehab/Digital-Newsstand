<?php
session_start();
include_once 'config/Database.php';
include_once 'classes/Article.php';
include_once 'classes/User.php';
include_once 'classes/user_favs.php';
include_once 'classes/user_book.php';

$db = Database::getInstance()->getConnection();

$user = new User($db);
$articleObj = new Article($db);
$userFavorites = new user_favs();
$userBookmarks = new user_book();

// Handle AJAX add/remove favorite and bookmark requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['article_id'])) {
    $action = $_POST['action'];
    $article_id = intval($_POST['article_id']);
    if ($action === 'add_favorite') {
        if ($userFavorites->addFavorite($article_id)) {
            echo 'added';
        } else {
            echo 'error';
        }
    } elseif ($action === 'remove_favorite') {
        if ($userFavorites->removeFavorite($article_id)) {
            echo 'removed';
        } else {
            echo 'error';
        }
    } elseif ($action === 'add_bookmark') {
        if ($userBookmarks->addBookmark($article_id)) {
            echo 'added';
        } else {
            echo 'error';
        }
    } elseif ($action === 'remove_bookmark') {
        if ($userBookmarks->removeBookmark($article_id)) {
            echo 'removed';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
    exit();
}

$full = false;
$is_favorited = false;
$is_booked = false;
$article = null;
$error_message = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = (int) $_GET['id'];

    $articleObj->incrementViews($article_id);

    $article = $articleObj->getArticleById($article_id);
    if (!$article) {
        $error_message = "Article not found.";
    }

    if ($user->isLoggedIn()) {
        $user_id = $user->getUserId();
        $is_favorited = $articleObj->isFavorited($user_id, $article_id);
        $is_booked = $articleObj->isBookmarked($user_id, $article_id);
        $full = ($user->getSubscriptionName() === 'full');
    }
} else {
    $error_message = "Invalid article ID.";
}

$article_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$show_ads = $user->shouldShowAds();





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($article['title']) ? htmlspecialchars($article['title']) : 'Article Detail' ?> - The Global Herald
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/images/article.png">
    <link rel="stylesheet" href="./article.css">
</head>

<body>

    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php else: ?>
            <?php
            $view_mode = 'web';
            if (isset($_GET['view']) && in_array($_GET['view'], ['web', 'print'])) {
                $view_mode = $_GET['view'];
            }
            ?>

            <div class="article-header">
                <div class="article-header-left">
                    <h1><?= htmlspecialchars($article['title']) ?></h1>
                    <p class="article-meta">
                        By <a
                            href="index.php?search=<?= urlencode($article['author']) ?>"><?= htmlspecialchars($article['author']) ?></a>
                        |
                        Category: <?= htmlspecialchars($article['category_name']) ?> |
                        <i class="far fa-clock"></i> <?= date("F j, Y", strtotime($article['created_at'])) ?>
                    </p>
                </div>
                <div class="article-actions">
                    <?php if ($full): ?>
                        <i id="download-icon" class="fa-solid fa-download" style="margin-right: 0.66rem;"
                            title="Download Article"></i>
                    <?php endif; ?>
                    <i id="read-aloud-icon" class="fas fa-volume-up" title="Read Aloud"></i>
                    <div id="google_translate_element" title="Translate Article"></div>
                    <a href="#" title="Add to Favorites" class="<?= $is_favorited ? 'fas' : 'far' ?> fa-heart"
                        onclick="toggleFavorite(<?= $article['id'] ?>, this); return false;"></a>
                    <a href="#" title="Add to Bookmark" class="<?= $is_booked ? 'fas' : 'far' ?> fa-bookmark"
                        onclick="toggleBookmark(<?= $article['id'] ?>, this); return false;"></a>
                </div>
                <div class="view-toggle" style="margin-left: 1rem;">
                    <?php if ($view_mode === 'web'): ?>
                        <a href="?id=<?= $article['id'] ?>&view=print" class="btn btn-outline-secondary btn-sm"
                            title="Switch to Print View">Print View</a>
                    <?php else: ?>
                        <a href="?id=<?= $article['id'] ?>&view=web" class="btn btn-outline-secondary btn-sm"
                            title="Switch to Web View">Web View</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($view_mode === 'print'): ?>
                <style>
                    body {
                        font-family: 'Times New Roman', Times, serif;
                        background: #fff !important;
                        color: #000 !important;
                        margin: 2rem;
                    }

                    .container {
                        max-width: 100% !important;
                        box-shadow: none !important;
                        background: #fff !important;
                        padding: 0 !important;
                        margin: 0 !important;
                    }

                    .article-header,
                    .article-actions,
                    .share-icons,
                    .back-link,
                    #comment-section,
                    .ads-section {
                        display: none !important;
                    }

                    .article-content img {
                        max-width: 100% !important;
                        height: auto !important;
                        margin-bottom: 1rem;
                    }

                    .article-content {
                        font-size: 14pt !important;
                        line-height: 1.6 !important;
                        color: #000 !important;
                    }


                    .view-toggle {
                        margin-bottom: 1rem;
                    }
                </style>
                <div class="container">
                    <div class="view-toggle">
                        <a href="?id=<?= $article['id'] ?>&view=web" class="btn btn-outline-secondary btn-sm"
                            title="Return to Web View">Return to Web View</a>
                    </div>
                    <h1><?= htmlspecialchars($article['title']) ?></h1>
                    <p><strong>By:</strong> <?= htmlspecialchars($article['author']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($article['category_name']) ?></p>
                    <p><strong>Date:</strong> <?= date("F j, Y", strtotime($article['created_at'])) ?></p>
                    <div class="article-content">
                        <?php
                        if (!empty($article['image_path'])) {
                            echo '<img src="../images/' . htmlspecialchars($article['image_path']) . '" class="img-fluid mb-3">';
                        }
                        ?>
                        <?= nl2br(htmlspecialchars($article['content'])) ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="article-content">
                    <?php
                    if (!empty($article['image_path'])) {
                        echo '<img src="../images/' . htmlspecialchars($article['image_path']) . '" class="img-fluid mb-3">';
                    }
                    ?>
                    <?= nl2br(htmlspecialchars($article['content'])) ?>
                </div>
            <?php endif; ?>
            <?php if ($show_ads): ?>
                <section class="ads-section"
                    style="background-color: #f8f9fa; justify-self: center; padding: 1rem; margin: 2rem 2rem; border: 1px solid #ddd; border-radius: 0.5rem; width: fit-content; align-self: center;">
                    <div class="container">
                        <h3 style="text-align: center; margin-bottom: 1rem;">Sponsored Ads</h3>
                        <div style="display: flex; justify-content: center; gap: 1rem;">
                            <a href="subscription.php" target="_blank">

                                <div
                                    style="width: 100%; height: 20rem;  border-radius: 0.25rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="images/g6.png" alt="Ad1" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            </a>

                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <div class="share-icons">
                <h5>Share this article:</h5>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($article_url) ?>" target="_blank"
                    rel="noopener noreferrer" class="fab fa-facebook"></a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($article_url) ?>&text=<?= urlencode($article['title']) ?>"
                    target="_blank" rel="noopener noreferrer" class="fab fa-twitter"></a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($article_url) ?>&title=<?= urlencode($article['title']) ?>"
                    target="_blank" rel="noopener noreferrer" class="fab fa-linkedin"></a>
                <a href="whatsapp://send?text=<?= urlencode($article_url) ?>" target="_blank" class="fab fa-whatsapp"></a>
                <a href="mailto:?subject=<?= urlencode('Check out this article: ' . $article['title']) ?>&body=<?= urlencode('I thought you might find this interesting: ' . $article_url) ?>"
                    class="fas fa-envelope"></a>
            </div>
        <?php endif; ?>

        <div class="back-link" style="display: flex; gap: 10px; align-items: center;">
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Headlines</a>
            <button id="comment-toggle-btn" class="btn btn-outline-primary" title="Toggle Comments"
                style="display: flex; align-items: center;">
                <i class="fas fa-comments"></i>
            </button>
        </div>

        <div id="comment-section" style="margin-top: 20px; display: none;">
            <h3>Comments</h3>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_description'])) {
                if ($user->isLoggedIn()) {
                    $comment_desc = trim($_POST['comment_description']);
                    if (!empty($comment_desc)) {
                        $articleObj->addComment($article_id, $user->getUserId(), $comment_desc);
                    }
                } else {
                    echo '<div class="alert alert-warning">You must be logged in to post a comment.</div>';
                }
            }

            $comments_query = "
                SELECT c.description, u.name
                FROM comment c
                JOIN users u ON c.user_id = u.user_id
                WHERE c.article_id = ?
                ORDER BY c.comment_id DESC
            ";
            $comments_result = $articleObj->getComments($article_id);

            if ($comments_result && $comments_result->num_rows > 0) {
                echo '<ul class="list-group mb-3">';
                while ($comment = $comments_result->fetch_assoc()) {
                    echo '<li class="list-group-item"><strong>' . htmlspecialchars($comment['name']) . ':</strong> <br>' . htmlspecialchars($comment['description']) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No comments yet.</p>';
            }

            if (isset($_SESSION['user_id'])) {
                ?>
                <form method="POST" id="comment-form">
                    <div class="mb-3">
                        <label for="comment_description" class="form-label">Leave a Comment</label>
                        <textarea class="form-control" id="comment_description" name="comment_description" rows="3"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit Comment</button>
                </form>
                <?php
            } else {
                echo '<p>Please <a href="login.html">log in</a> to leave a comment.</p>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFavorite(articleId, iconElement) {
            let action = iconElement.classList.contains('far') ? 'add_favorite' : 'remove_favorite';
            fetch('article.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&article_id=${articleId}`,
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'added') {
                        iconElement.classList.remove('far', 'fa-heart');
                        iconElement.classList.add('fas', 'fa-heart', 'favorited');
                    } else if (data === 'removed') {
                        iconElement.classList.remove('fas', 'fa-heart', 'favorited');
                        iconElement.classList.add('far', 'fa-heart');
                    } else if (data === 'error') {
                        alert('Failed to update favorites. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred while updating favorites.');
                });
        }

        function toggleBookmark(articleId, iconElement) {
            let action = iconElement.classList.contains('far') ? 'add_bookmark' : 'remove_bookmark';

            fetch('article.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&article_id=${articleId}`,
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'added') {
                        iconElement.classList.remove('far', 'fa-bookmark');
                        iconElement.classList.add('fas', 'fa-bookmark', 'bookmarked');
                    } else if (data === 'removed') {
                        iconElement.classList.remove('fas', 'fa-bookmark', 'bookmarked');
                        iconElement.classList.add('far', 'fa-bookmark');
                    } else if (data === 'error') {
                        alert('Failed to update bookmarks. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred while updating bookmarks.');
                });
        }

        const btn = document.getElementById("read-aloud-icon");
        const synth = window.speechSynthesis;

        let selectedVoice = null;
        let utterance = null;
        let isPaused = false;

        function loadVoice() {
            const voices = synth.getVoices();
            selectedVoice = voices.find(voice => voice.lang === "en-US") || voices[0];
        }

        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = loadVoice;
        } else {
            loadVoice();
        }

        function startSpeaking(text) {
            if (synth.speaking || synth.paused) {
                synth.cancel();
            }

            utterance = new SpeechSynthesisUtterance(text);
            utterance.voice = selectedVoice;
            utterance.rate = 1;
            utterance.pitch = 1;

            synth.speak(utterance);
        }

        btn.addEventListener("click", e => {
            e.preventDefault();

            const article = document.querySelector(".article-content");
            if (!article) return;

            const text = article.textContent.trim();

            if (!utterance || (!synth.speaking && !synth.paused)) {
                startSpeaking(text);
                btn.classList.remove("fa-play", "fa-volume-up");
                btn.classList.add("fa-pause");
                btn.title = "Pause Reading";
            } else if (synth.speaking && !synth.paused) {
                synth.pause();
                isPaused = true;
                btn.classList.remove("fa-pause");
                btn.classList.add("fa-play");
                btn.title = "Resume Reading";
            } else if (synth.paused) {
                synth.resume();
                isPaused = false;
                btn.classList.remove("fa-play");
                btn.classList.add("fa-pause");
                btn.title = "Pause Reading";
            }
        });

        synth.addEventListener("end", resetIcon);
        synth.addEventListener("cancel", resetIcon);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const commentToggleBtn = document.getElementById("comment-toggle-btn");
            if (commentToggleBtn) {
                commentToggleBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    const commentSection = document.getElementById("comment-section");
                    if (commentSection.style.display === "none" || commentSection.style.display === "") {
                        commentSection.style.display = "block";
                    } else {
                        commentSection.style.display = "none";
                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>

    <script type="text/javascript"
        src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
        </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById("download-icon").addEventListener("click", async function () {
            const {
                jsPDF
            } = window.jspdf;
            const articleElement = document.querySelector(".article-content");

            if (!articleElement) {
                console.error("Could not find the .article-content element to download.");
                return;
            }

            try {
                const canvas = await html2canvas(articleElement, {
                    logging: false
                });
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF();
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('article.pdf');

            } catch (error) {
                console.error("Error during PDF generation:", error);
                alert("An error occurred while generating the PDF.");
            }
        });
    </script>
</body>

</html>