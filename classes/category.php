<?php
class Category
{
    private $conn;
    private $table = "category";

    private $category_id;
    public $name;

    public function __construct($db)
    {
        $this->conn = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create($category_name)
    {
        $this->name = $category_name;
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (category_name) VALUES (?)");
        $stmt->bind_param("s", $this->name);
        return $stmt->execute();
    }

    public function readAll()
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY category_id DESC";
        return $this->conn->query($sql);
    }

    public function delete($category_id)
    {
        $this->category_id = $category_id;
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE category_id = ?");
        $stmt->bind_param("i", $this->category_id);
        return $stmt->execute();
    }
}
?>