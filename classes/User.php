<?php
class User
{
    private $db;
    private $userId;
    private $subscriptionName;
    private $notificationsCount;

    public function __construct($db)
    {
        $this->db = $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->subscriptionName = null;
        $this->notificationsCount = 0;

        if ($this->userId !== null) {
            $this->loadSubscription();
            $this->loadNotificationsCount();
        }
    }

    private function loadSubscription()
    {
        $sub_stmt = $this->db->prepare("SELECT plan_ID FROM subscription WHERE user_id = ?");
        $sub_stmt->bind_param("i", $this->userId);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();
        if ($sub_result && $row = $sub_result->fetch_assoc()) {
            $this->subscriptionName = (int) $row['plan_ID'];
        }
        $sub_stmt->close();
    }

    private function loadNotificationsCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM notfications WHERE user_id = ?");
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $this->notificationsCount = $row['count'];
        }
        $stmt->close();
    }

    public function isLoggedIn()
    {
        return $this->userId !== null;
    }

    public function isSignedUp()
    {
        return isset($_SESSION['just_signed_up']) && $_SESSION['just_signed_up'] === true;
    }

    public function getSubscriptionName()
    {
        return $this->subscriptionName;
    }

    public function shouldShowAds()
    {
        // Show ads if plan_ID is not 3 (premium_plus/full)
        return !($this->subscriptionName === 3);
    }

    public function getNotificationsCount()
    {
        return $this->notificationsCount;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function addComment($article_id, $user_id, $comment)
    {
        $stmt = $this->db->prepare("INSERT INTO comment (description, user_id, article_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $comment, $user_id, $article_id);
        return $stmt->execute();
    }

    public function submitComment($article_id, $user_id, $comment_desc)
    {
        $comment_desc = trim($comment_desc);
        if (empty($comment_desc)) {
            return false;
        }
        return $this->addComment($article_id, $user_id, $comment_desc);
    }
}
?>