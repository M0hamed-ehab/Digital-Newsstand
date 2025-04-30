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
        $sub_stmt = $this->db->prepare("SELECT subscription_name FROM subscription WHERE user_id = ?");
        $sub_stmt->bind_param("i", $this->userId);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();
        if ($sub_result && $row = $sub_result->fetch_assoc()) {
            $this->subscriptionName = $row['subscription_name'];
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
        return !($this->subscriptionName === 'full');
    }

    public function getNotificationsCount()
    {
        return $this->notificationsCount;
    }
}
?>