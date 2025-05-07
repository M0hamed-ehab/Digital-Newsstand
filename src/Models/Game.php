<?php
class Game
{
    private $conn;
    public $GameID, $Title, $Description, $img, $page;

    public function __construct($db)
    {
        $this->conn = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getAllGames()
    {
        $query = "SELECT GameID, Title, Description, img, page FROM game";
        return $this->conn->query($query);
    }

    public function getGameById($id)
    {
        $stmt = $this->conn->prepare("SELECT GameID, Title, Description, img, page FROM game WHERE GameID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public static function play($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT page FROM game WHERE GameID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            header("Location: " . $row['page']);
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}
?>