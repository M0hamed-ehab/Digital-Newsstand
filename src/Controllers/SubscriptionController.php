<?php
session_start();

include_once '../config/Database.php';
include_once '../src/Models/Article.php';
include_once '../src/Models/User.php';
include_once '../src/Models/Subscription.php';
include_once '../src/Models/Admin.php';
include_once '../src/Models/Plans.php';

class SubscriptionController
{
    private $db;
    private $articleObj;
    private $userObj;
    private $adminObj;
    private $plansObj;
    private $subscription;
    private $plans;
    private $categories_result;
    private $selected_category;
    private $search_term;
    private $user_id;
    private $message = '';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        $this->articleObj = new Article($this->db);
        $this->userObj = new User($this->db);
        $this->adminObj = new Admin($this->db);
        $this->plansObj = new Plans($this->db);
        $this->plans = $this->plansObj->getPlans();

        $this->categories_result = $this->adminObj->getCategories();

        $this->selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
        $this->search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.html");
            exit();
        }

        $this->user_id = $_SESSION['user_id'];
        $this->subscription = new Subscription($this->db, $this->user_id);

        $this->handleRequest();
    }

    private function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['plan'])) {
                $plan_name = $_POST['plan'];
                $new_plan_id = 1;
                foreach ($this->plans as $plan) {
                    if (strtolower(str_replace(' ', '_', $plan['plan_name'])) === $plan_name) {
                        $new_plan_id = $plan['plan_ID'];
                        break;
                    }
                }
                $this->message = $this->subscription->handlePlanChange($new_plan_id);
                header("Location: subscription.php?message=" . urlencode($this->message));
                exit();
            } elseif (isset($_POST['auto_renew'])) {
                $auto_renew = $_POST['auto_renew'];
                $this->message = $this->subscription->handleAutoRenewChange($auto_renew);
                header("Location: subscription.php?message=" . urlencode($this->message));
                exit();
            }
        }

        if (isset($_GET['message'])) {
            $this->message = htmlspecialchars($_GET['message']);
        }
    }

    public function load()
    {
        $highlight_plan = $this->subscription->getHighlightPlan();
        $auto_renew = $this->subscription->getAutoRenew();
        $articles = $this->subscription->getTodayArticles();
        $notifications_count = $this->subscription->getNotificationsCount();

        $articleObj = $this->articleObj;
        $userObj = $this->userObj;
        $categories_result = $this->categories_result;
        $selected_category = $this->selected_category;
        $plans = $this->plans;
        $message = $this->message;
        $highlight_plan = $highlight_plan;
        $auto_renew = $auto_renew;
        $articles = $articles;
        $notifications_count = $notifications_count;
        $subscription = $this->subscription;

        include '../src/Views/subscription_view.php';
    }
}
