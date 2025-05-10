<?php

include_once '../config/Database.php';
include_once '../src/Models/Article.php';
include_once '../src/Models/User.php';
include_once '../src/Models/Admin.php';

class GameController
{
    private $db;
    private $articleObj;
    private $userObj;
    private $adminObj;
    private $categories_result;
    private $selected_category;
    private $search_term;
    private $notifications_count;
    private $show_ads;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->articleObj = new Article($this->db);
        $this->userObj = new User($this->db);
        $this->adminObj = new Admin($this->db);

        $this->categories_result = $this->adminObj->getCategories();
        $this->selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $this->search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

        $this->notifications_count = $this->userObj->getNotificationsCount();
        $this->show_ads = method_exists($this->userObj, 'shouldShowAds') ? $this->userObj->shouldShowAds() : false;
    }

    public function isUserLoggedIn()
    {
        return $this->userObj->isLoggedIn();
    }

    public function isSignedUp()
    {
        return $this->userObj->isSignedUp();
    }

    public function load($game)
    {
        $categories_result = $this->categories_result;
        $selected_category = $this->selected_category;
        $search_term = $this->search_term;
        $notifications_count = $this->notifications_count;
        $show_ads = $this->show_ads;
        $articleObj = $this->articleObj;
        $userObj = $this->userObj;
        $db = $this->db;
        $dark_mode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : false;
        switch (strtolower($game)) {
            case 'mine':
                include '../src/Views/mine_view.php';
                break;
            case 'sudoku':
                include '../src/Views/sudoku_view.php';
                break;
            case 'xo':
                include '../src/Views/xo_view.php';
                break;
            case 'wordle':
                include '../src/Views/wordle_view.php';
                break;
            default:
                echo "Game not found";
                break;
        }
    }
}
