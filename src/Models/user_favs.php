<?php
include_once '../config/Database.php';

class user_favs
{
    private $db;
    private $user_id;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        if (isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
        } else {
            $this->user_id = null;
        }
    }

    // Fetch user favorites
    public function getUserFavorites()
    {
        $query = "
            SELECT a.id, a.title, a.author, a.content, a.created_at, c.category_name
            FROM favorites f
            JOIN articles a ON f.article_id = a.id
            LEFT JOIN category c ON a.category_id = c.category_id
            WHERE f.user_id = ?
            ORDER BY a.created_at DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $favorites_result = $stmt->get_result();

        // Check if favorites exist
        if ($favorites_result->num_rows > 0) {
            return $favorites_result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function addFavorite($article_id)
    {
        $query = "INSERT INTO favorites (user_id, article_id) VALUES (?, ?)";
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

    public function removeFavorite($article_id)
    {
        $query = "DELETE FROM favorites WHERE user_id = ? AND article_id = ?";
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