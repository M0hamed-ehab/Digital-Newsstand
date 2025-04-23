<?php
class Article {
    private $conn;
    public $id, $title, $content, $author, $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO articles (title, content, author, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $this->title, $this->content, $this->author, $this->category_id);
        return $stmt->execute();
    }

    public function update() {
        $stmt = $this->conn->prepare("UPDATE articles SET title=?, content=?, author=?, category_id=? WHERE id=?");
        $stmt->bind_param("sssii", $this->title, $this->content, $this->author, $this->category_id, $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id=?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function readAll() {
        $sql = "SELECT a.*, c.category_name FROM articles a
                LEFT JOIN category c ON a.category_id = c.category_id";
        return $this->conn->query($sql);
    }

    public function getCategories() {
        return $this->conn->query("SELECT * FROM category");
    }
}
