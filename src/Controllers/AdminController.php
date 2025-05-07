<?php
require_once '../config/Database.php';
require_once '../src/Models/Category.php';
require_once '../src/Models/Admin.php';
require_once '../src/Models/Article.php';

class AdminController
{
    private $db;
    private $admin;
    private $article;
    private $category;
    public $message;
    public $categories;
    public $articles;
    public $categoryList;
    public $breakingNews;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.html");
            exit();
        }

        $this->db = Database::getInstance()->getConnection();
        $this->admin = new Admin($this->db);
        $this->article = new Article($this->db);
        $this->category = new Category($this->db);

        $this->handleRequest();

        $this->categories = $this->category->readAll();
        $this->articles = $this->admin->readAll();
        $result = $this->category->readAll();
        $this->categoryList = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->categoryList[] = $row;
            }
        }

        $bnq = $this->admin->getBNQ();
        $result = $this->db->query($bnq);
        $this->breakingNews = $result;
    }

    private function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create_category'])) {
                $this->message = $this->category->create($_POST['category_name'])
                    ? "Category added successfully."
                    : "Failed to add category.";
            } elseif (isset($_POST['delete_category'])) {
                $this->message = $this->category->delete($_POST['category_id'])
                    ? "Category deleted."
                    : "Failed to delete category.";
            } elseif (isset($_POST['create'])) {
                $this->message = $this->admin->createFromForm($_POST, $_FILES)
                    ? "Article published."
                    : "Failed to publish article.";
            } elseif (isset($_POST['update'])) {
                $this->message = $this->admin->updateFromForm($_POST)
                    ? "Article updated."
                    : "Failed to update article.";
            } elseif (isset($_POST['delete'])) {
                $this->message = $this->admin->deleteById($_POST['id'])
                    ? "Article deleted."
                    : "Failed to delete article.";
            } elseif (isset($_POST['send'])) {
                $this->message = $this->article->sendSummary($_POST['id']);
            } elseif (isset($_POST['create_breaking_news'])) {
                $content = trim($_POST['breaking_content']);
                $duration = intval($_POST['breaking_duration']);
                $this->message = $this->admin->createBreakingNews($content, $duration);
            }
        }
    }
}
?>