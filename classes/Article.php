<?php
class Article {
    private $conn;
    public $id, $title, $content, $author, $category_id, $image_path;

    public function __construct($db) {
        $this->conn = $db;
    }

}
?>
