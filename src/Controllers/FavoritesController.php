<?php
require_once '../config/Database.php';
require_once '../src/Models/user_favs.php';
require_once '../src/Models/Article.php';
require_once '../src/Models/User.php';
require_once '../src/Models/Admin.php';

class FavoritesController
{
    private $db;
    private $userFavorites;
    private $articleObj;
    private $adminObj;
    private $userObj;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        $this->db = Database::getInstance()->getConnection();
        $this->userFavorites = new user_favs();
        $this->articleObj = new Article($this->db);
        $this->adminObj = new Admin($this->db);
        $this->userObj = new User($this->db);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['article_id'])) {
            $action = $_POST['action'];
            $article_id = intval($_POST['article_id']);
            if ($action === 'add') {
                if ($this->userFavorites->addFavorite($article_id)) {
                    echo 'added';
                } else {
                    echo 'error';
                }
            } elseif ($action === 'remove') {
                if ($this->userFavorites->removeFavorite($article_id)) {
                    echo 'removed';
                } else {
                    echo 'error';
                }
            } else {
                echo 'error';
            }
            exit();
        }

        $favorites = $this->userFavorites->getUserFavorites();
        $categories_result = $this->adminObj->getCategories();

        $selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

        $notifications_count = $this->userObj->getNotificationsCount();

        return [
            'favorites' => $favorites,
            'categories_result' => $categories_result,
            'selected_category' => $selected_category,
            'search_term' => $search_term,
            'notifications_count' => $notifications_count,
            'articleObj' => $this->articleObj,
            'userObj' => $this->userObj,
        ];
    }
}
?>