<?php
class Category {
    private $conn;
    private $table = "category";

    public $category_id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $sql = "INSERT INTO " . $this->table . " (category_name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->name);
        return $stmt->execute();
    }

    public function readAll() {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY category_id DESC";
        return $this->conn->query($sql);
    }

    public function delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->category_id);
        return $stmt->execute();
    }
}
?>
