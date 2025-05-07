<?php
session_start();

include_once '../config/Database.php';
include_once '../src/Models/Article.php';
include_once '../src/Models/User.php';
include_once '../src/Models/user_favs.php';
include_once '../src/Models/user_book.php';

class ArticleController
{
    private $db;
    private $user;
    private $articleObj;
    private $userFavorites;
    private $userBookmarks;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user = new User($this->db);
        $this->articleObj = new Article($this->db);
        $this->userFavorites = new user_favs();
        $this->userBookmarks = new user_book();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['article_id'])) {
            $this->handleAjaxActions();
            exit();
        }

        $data = $this->prepareArticleData();
        return $data;
    }

    private function handleAjaxActions()
    {
        $action = $_POST['action'];
        $article_id = intval($_POST['article_id']);
        if ($action === 'add_favorite') {
            echo $this->userFavorites->addFavorite($article_id) ? 'added' : 'error';
        } elseif ($action === 'remove_favorite') {
            echo $this->userFavorites->removeFavorite($article_id) ? 'removed' : 'error';
        } elseif ($action === 'add_bookmark') {
            echo $this->userBookmarks->addBookmark($article_id) ? 'added' : 'error';
        } elseif ($action === 'remove_bookmark') {
            echo $this->userBookmarks->removeBookmark($article_id) ? 'removed' : 'error';
        } else {
            echo 'error';
        }
    }

    private function prepareArticleData()
    {
        $premium = false;
        $is_favorited = false;
        $is_booked = false;
        $article = null;
        $error_message = null;

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $article_id = (int) $_GET['id'];

            $this->articleObj->incrementViews($article_id);

            $article = $this->articleObj->getArticleById($article_id);
            if (!$article) {
                $error_message = "Article not found.";
            }

            if ($this->user->isLoggedIn()) {
                $user_id = $this->user->getUserId();
                $is_favorited = $this->articleObj->isFavorited($user_id, $article_id);
                $is_booked = $this->articleObj->isBookmarked($user_id, $article_id);
                $premium = ($this->user->getSubscriptionName() === 3);
            }
        } else {
            $error_message = "Invalid article ID.";
        }

        $article_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $show_ads = $this->user->shouldShowAds();

        $comments = null;
        if ($article && isset($article['id'])) {
            $comments = $this->articleObj->getComments($article['id']);
        }

        return [
            'premium' => $premium,
            'is_favorited' => $is_favorited,
            'is_booked' => $is_booked,
            'article' => $article,
            'comments' => $comments,
            'user' => $this->user,
            'error_message' => $error_message,
            'article_url' => $article_url,
            'show_ads' => $show_ads,
        ];
    }
}
?>