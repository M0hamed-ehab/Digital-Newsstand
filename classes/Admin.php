<?php
class Admin
{
    private $conn;
    public $id, $title, $content, $author, $category_id, $image_path;


    public function __construct($db)
    {

        $this->conn = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function getCategories()
    {
        $query = "SELECT * FROM category ORDER BY category_name ASC";
        return $this->conn->query($query);
    }

    public function create()
    {
        if ($this->image_path) {
            $stmt = $this->conn->prepare("INSERT INTO articles (title, content, author, category_id, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $this->title, $this->content, $this->author, $this->category_id, $this->image_path);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO articles (title, content, author, category_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $this->title, $this->content, $this->author, $this->category_id);
        }
        return $stmt->execute();
    }

    public function update()
    {
        $stmt = $this->conn->prepare("UPDATE articles SET title=?, content=?, author=?, category_id=? WHERE id=?");
        $stmt->bind_param("sssii", $this->title, $this->content, $this->author, $this->category_id, $this->id);
        return $stmt->execute();
    }

    public function deleteById($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function readAll()
    {
        $sql = "SELECT a.*, c.category_name 
                FROM articles a 
                LEFT JOIN category c ON a.category_id = c.category_id
                ORDER BY a.created_at DESC";
        return $this->conn->query($sql);
    }

    public function createFromForm($post, $files)
    {
        $this->title = $post['title'];
        $this->content = $post['content'];
        $this->author = $post['author'];
        $this->category_id = $post['category_id'];

        if (isset($files['image']) && $files['image']['error'] === 0) {
            $imageName = basename($files['image']['name']);
            $targetDirectory = "images/";
            $targetFile = $targetDirectory . $imageName;

            if (move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                $this->image_path = $imageName;
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "No file uploaded or error occurred.";
        }


        return $this->create();
    }

    public function updateFromForm($post)
    {
        $this->id = $post['id'];
        $this->title = $post['title'];
        $this->content = $post['content'];
        $this->author = $post['author'];
        $this->category_id = $post['category_id'];
        return $this->update();
    }

    public function sendSummary($articleId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $subscribers = $this->conn->query("SELECT email FROM users WHERE role = 'subscriber'");
            if ($subscribers && $subscribers->num_rows > 0) {
                $count = $subscribers->num_rows;
                return "Summary sent to $count Premium+ subscribers.";
            } else {
                return "No subscribers found to send the summary.";
            }
        }
        return "Invalid article selected.";
    }

}