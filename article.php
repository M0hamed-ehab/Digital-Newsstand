<?php
include_once 'config/Database.php';

$db = (new Database())->connect();
session_start();

$full = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $subscription_query = "SELECT subscription_name FROM subscription WHERE user_id = ?";
    $stmt = $db->prepare($subscription_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $subscription_result = $stmt->get_result();
    if ($subscription_result->num_rows > 0) {
        $subscription = $subscription_result->fetch_assoc();
        if ($subscription['subscription_name'] === 'full') {
            $full = true;
        }
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = $_GET['id'];

    $article_query = "
        SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name, a.image_path
        FROM articles a
        LEFT JOIN category c ON a.category_id = c.category_id
        WHERE a.id = ?
    ";

    $stmt = $db->prepare($article_query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $article_result = $stmt->get_result();

    if ($article_result->num_rows > 0) {
        $article = $article_result->fetch_assoc();
    } else {
        $error_message = "Article not found.";
    }

    $is_favorited = false;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $favorite_query = "
            SELECT 1 FROM favorites WHERE user_id = ? AND article_id = ?
        ";
        $stmt = $db->prepare($favorite_query);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        $favorite_result = $stmt->get_result();
        $is_favorited = $favorite_result->num_rows > 0;
    }

    $is_booked = false;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $bookmark_query = "
            SELECT 1 FROM bookmarks WHERE user_id = ? AND article_id = ?
        ";
        $stmt = $db->prepare($bookmark_query);
        $stmt->bind_param("ii", $user_id, $article_id);
        $stmt->execute();
        $book_result = $stmt->get_result();
        $is_booked = $book_result->num_rows > 0;
    }
} else {
    $error_message = "Invalid article ID.";
}

$article_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url('images/g5.jpg');
            background-size: auto;
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

        .container {
            max-width: 960px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
        }

        .article-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .article-header-left {
            flex-grow: 1;
        }

        .article-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .article-meta {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .article-meta a {
            color: #007bff;
            text-decoration: none;
        }

        .article-meta a:hover {
            text-decoration: underline;
        }

        .article-actions {
            display: flex;
            align-items: center;
        }

        .article-actions i {
            color: #6C757D;
            font-size: 1.2rem;
            cursor: pointer;
        }

        #translate-icon {
            margin-right: 0.5rem;
            /* Adjust spacing between icon and dropdown */
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            transition: color 0.2s ease-in-out;
        }

        #translate-icon:hover {
            color: #007bff;
        }

        .article-actions a {
            margin-left: 1rem;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .article-actions a:hover,
        .article-actions i:hover {
            color: #007bff;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #495057;
            margin-bottom: 2rem;
            display: grid;
        }

        .share-icons {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }

        .share-icons h5 {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 1rem;
        }

        .share-icons a {
            display: inline-block;
            margin: 0 0.75rem;
            font-size: 1.5rem;
            color: #007bff;
            transition: color 0.2s ease-in-out;
        }

        .share-icons a:hover {
            color: #0056b3;
        }

        .back-link {
            margin-top: 1.5rem;
            display: inline-block;
        }

        .favorited {
            color: #e52b6f !important;
        }

        .bookmarked {
            color: #007BFF !important;
        }

        .goog-te-gadget-simple {
            border: none !important;
        }

        .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
            display: none !important;
        }

        .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed span {
            display: none !important;
        }

        .goog-te-gadget img {
            left: 35%;
            position: relative;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php else: ?>
            <div class="article-header">
                <div class="article-header-left">
                    <h1><?= htmlspecialchars($article['title']) ?></h1>
                    <p class="article-meta">
                        By <a href="#"><?= htmlspecialchars($article['author']) ?></a> |
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
            </div>

            <div class="article-content">
                <?php
                if (!empty($article['image_path'])) {
                    echo '<img src="../images/' . htmlspecialchars($article['image_path']) . '" class="img-fluid mb-3">';
                }
                ?>
                <?= nl2br(htmlspecialchars($article['content'])) ?>
            </div>

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
                if (isset($_SESSION['user_id'])) {
                    $comment_desc = trim($_POST['comment_description']);
                    if (!empty($comment_desc)) {
                        $insert_comment_query = "INSERT INTO comment (description, user_id, article_id) VALUES (?, ?, ?)";
                        $stmt = $db->prepare($insert_comment_query);
                        $stmt->bind_param("sii", $comment_desc, $_SESSION['user_id'], $article_id);
                        $stmt->execute();
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
            $stmt = $db->prepare($comments_query);
            $stmt->bind_param("i", $article_id);
            $stmt->execute();
            $comments_result = $stmt->get_result();

            if ($comments_result->num_rows > 0) {
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
            const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>';

            if (userId === '0') {
                alert('Please log in to add/remove favorites.');
                return;
            }

            let action = iconElement.classList.contains('far') ? 'add' : 'remove';

            fetch('add_to_favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&article_id=${articleId}&action=${action}`,
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
            const userId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>';

            if (userId === '0') {
                alert('Please log in to add/remove bookmarks.');
                return;
            }

            let action = iconElement.classList.contains('far') ? 'add' : 'remove';

            fetch('add_to_bookmarks.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&article_id=${articleId}&action=${action}`,
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
            const { jsPDF } = window.jspdf;
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