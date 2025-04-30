<?php
session_start();
include_once 'config/Database.php';

class ArticleHandler {
    private $db;
    private $user_id;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    public function getArticle($article_id) {
        $query = "SELECT * FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function isFavorited($article_id) {
        if (!$this->user_id) return false;

        $query = "SELECT 1 FROM favorites WHERE user_id = ? AND article_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $article_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function isBookmarked($article_id) {
        if (!$this->user_id) return false;

        $query = "SELECT 1 FROM bookmarks WHERE user_id = ? AND article_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $article_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function getCurrentArticleUrl() {
        return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}
?>
