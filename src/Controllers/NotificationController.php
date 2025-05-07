<?php
require_once '../config/Database.php';
require_once '../src/Models/Article.php';
require_once '../src/Models/User.php';
require_once '../src/Models/Notification.php';
require_once '../src/Models/Admin.php';

class NotificationController
{
    private $db;
    private $articleObj;
    private $userObj;
    private $notificationObj;
    private $adminObj;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        $this->db = Database::getInstance()->getConnection();
        $this->articleObj = new Article($this->db);
        $this->userObj = new User($this->db);
        $this->notificationObj = new Notification($this->db);
        $this->adminObj = new Admin($this->db);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['delete_notfication_id']) || isset($_POST['delete_all']))) {
            $user_id = $_SESSION['user_id'] ?? 0;

            if ($user_id > 0) {
                if (isset($_POST['delete_notfication_id'])) {
                    $notfication_id = intval($_POST['delete_notfication_id']);
                    $this->notificationObj->deleteNotification($notfication_id, $user_id);
                } elseif (isset($_POST['delete_all'])) {
                    $this->notificationObj->deleteAllNotifications($user_id);
                }
            }

            header("Location: noti.php");
            exit();
        }

        $categories_result = $this->adminObj->getCategories();

        $selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

        $notifications_count = $this->userObj->getNotificationsCount();

        $notfications = [];
        if ($this->userObj->isLoggedIn()) {
            $user_id = $_SESSION['user_id'];
            $notfications = $this->notificationObj->getNotifications($user_id);
        }

        return [
            'categories_result' => $categories_result,
            'selected_category' => $selected_category,
            'search_term' => $search_term,
            'notifications_count' => $notifications_count,
            'notfications' => $notfications,
            'articleObj' => $this->articleObj,
            'userObj' => $this->userObj,
        ];
    }
}
?>