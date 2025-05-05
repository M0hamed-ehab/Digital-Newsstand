<?php
class Notification
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getNotifications($user_id)
    {
        $notifications = [];
        $stmt = $this->conn->prepare("SELECT notfication_id, article_id, notfication_description FROM notfications WHERE user_id = ? ORDER BY notfication_id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();
        return $notifications;
    }

    public function deleteNotification($notification_id, $user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM notfications WHERE notfication_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $notification_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteAllNotifications($user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM notfications WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>