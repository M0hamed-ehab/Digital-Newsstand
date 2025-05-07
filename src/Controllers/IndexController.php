<?php
include_once '../config/Database.php';
include_once '../src/Models/Article.php';
include_once '../src/Models/User.php';
include_once '../src/Models/Game.php';
include_once '../src/Models/Admin.php';

class IndexController
{
    private $db;
    private $adminObj;
    private $articleObj;
    private $userObj;
    private $gameObj;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->adminObj = new Admin($this->db);
        $this->articleObj = new Article($this->db);
        $this->userObj = new User($this->db);
        $this->gameObj = new Game($this->db);
    }

    public function playGame($gameId)
    {
        Game::play(intval($gameId));
        exit();
    }

    public function index()
    {
        if (isset($_GET['play']) && is_numeric($_GET['play'])) {
            $this->playGame($_GET['play']);
        }

        $categories_result = $this->adminObj->getCategories();

        $selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;

        $selected_category_name = '';
        if ($selected_category !== 0) {
            $categories_result->data_seek(0);
            while ($category = $categories_result->fetch_assoc()) {
                if ($category['category_id'] == $selected_category) {
                    $selected_category_name = $category['category_name'];
                    break;
                }
            }
        }

        if ($search_term !== '') {
            $heading_text = "Results for: \"" . htmlspecialchars($search_term) . "\"";
        } elseif ($selected_category !== 0 && $selected_category_name !== '') {
            $heading_text = "Category: " . htmlspecialchars($selected_category_name);
        } else {
            $heading_text = "Latest Headlines";
        }

        $articles_per_page = 5;

        $total_articles = $this->articleObj->getTotalArticles($search_term, $selected_category);
        $articles_result = $this->articleObj->getArticles($search_term, $selected_category, $page, $articles_per_page);

        $games_result = $this->gameObj->getAllGames();

        $newsObj = new Admin($this->db);

        $BNQ = $newsObj->getBNQ();
        $BNR = $this->db->query($BNQ);

        $popular_articles_query = "SELECT id, title FROM articles ORDER BY id DESC";
        $popular_articles_result = $this->db->query($popular_articles_query);

        $show_ads = $this->userObj->shouldShowAds();

        $notifications_count = $this->userObj->getNotificationsCount();

        $articleObj = $this->articleObj;
        $userObj = $this->userObj;
        $db = $this->db;

        include '../src/Views/index_view.php';
    }
}
?>