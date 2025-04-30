<?php
include_once 'config/Database.php';

class user_book
{
    private $db;
    private $user_id;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        $this->user_id = $_SESSION['user_id'];
    }

    public function getUserBooks()
    {
        $query = "
            SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name
            FROM bookmarks b
            JOIN articles a ON b.article_id = a.id
            LEFT JOIN category c ON a.category_id = c.category_id
            WHERE b.user_id = ?
            ORDER BY a.created_at DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $book_result = $stmt->get_result();

        if ($book_result->num_rows > 0) {
            return $book_result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function addBookmark($article_id)
    {
        $query = "INSERT INTO bookmarks (user_id, article_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $article_id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function removeBookmark($article_id)
    {
        $query = "DELETE FROM bookmarks WHERE user_id = ? AND article_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $article_id);
        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            $stmt->close();
            return $affected > 0;
        } else {
            $stmt->close();
            return false;
        }
    }
}
?>