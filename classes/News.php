<?php
class News
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createBreakingNews($content, $duration)
    {
        if ($duration < 1 || $duration > 60) {
            return "Duration must be between 1 and 60 minutes.";
        }
        if (empty(trim($content))) {
            return "Content cannot be empty.";
        }

        $stmt = $this->conn->prepare("INSERT INTO breaking_news (content, duration) VALUES (?, ?)");
        $stmt->bind_param("si", $content, $duration);
        if ($stmt->execute()) {
            $stmt->close();
            return "Breaking news created successfully.";
        } else {
            $stmt->close();
            return "Failed to create breaking news.";
        }
    }
}
?>