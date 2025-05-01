<?php
class Subscription
{
    private $db;
    private $user_id;
    private $role;
    private $subscription_name;
    private $auto_renew;
    private $highlight_plan;
    private $articles = [];
    private $notifications_count = 0;

    public function __construct($db, $user_id)
    {
        $this->db = $db;
        $this->user_id = $user_id;
        $this->role = $this->getUserRole();
        $this->loadSubscriptionDetails();
        $this->highlight_plan = $this->determineHighlightPlan();
        $this->articles = $this->getTodayArticles();
        $this->notifications_count = $this->getNotificationsCount();
    }

    private function getUserRole()
    {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['role'];
        }
        return null;
    }

    private function loadSubscriptionDetails()
    {
        if ($this->role === 'subscriber' || $this->role === 'admin') {
            $stmt_sub = $this->db->prepare("SELECT subscription_name, auto_renew FROM subscription WHERE user_id = ?");
            $stmt_sub->bind_param("i", $this->user_id);
            $stmt_sub->execute();
            $result_sub = $stmt_sub->get_result();
            if ($result_sub->num_rows > 0) {
                $sub_row = $result_sub->fetch_assoc();
                $this->subscription_name = $sub_row['subscription_name'];
                $this->auto_renew = $sub_row['auto_renew'];
            } else {
                $this->subscription_name = 'none';
                $this->auto_renew = 0;
            }
        } else {
            $this->subscription_name = 'none';
            $this->auto_renew = 0;
        }
    }

    private function determineHighlightPlan()
    {
        if ($this->role === 'member') {
            return 'free';
        } elseif ($this->role === 'subscriber' || $this->role === 'admin') {
            if ($this->subscription_name === 'none') {
                return 'free';
            } elseif ($this->subscription_name === 'semi') {
                return 'premium';
            } elseif ($this->subscription_name === 'full') {
                return 'premium_plus';
            }
        }
        return 'free';
    }

    public function getHighlightPlan()
    {
        return $this->highlight_plan;
    }

    public function getAutoRenew()
    {
        return $this->auto_renew;
    }

    public function getTodayArticles()
    {
        $articles = [];
        $today = date('Y-m-d');
        $stmt_articles = $this->db->prepare("SELECT title, content FROM articles WHERE DATE(created_at) = ?");
        $stmt_articles->bind_param("s", $today);
        $stmt_articles->execute();
        $result_articles = $stmt_articles->get_result();
        while ($row_article = $result_articles->fetch_assoc()) {
            $articles[] = $row_article;
        }
        return $articles;
    }

    public function planClass($plan)
    {
        return $plan === $this->highlight_plan ? 'plan highlight' : 'plan';
    }

    public function buttonLabel($plan)
    {
        return $plan === $this->highlight_plan ? 'Your Plan' : 'Choose Plan';
    }

    public function getNotificationsCount()
    {
        $count = 0;
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM notfications WHERE user_id = ?");
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $count = $row['count'];
        }
        $stmt->close();
        return $count;
    }

    public function handlePlanChange($new_plan)
    {
        if ($this->role === 'admin') {
            if ($new_plan === 'free') {
                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'none' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium') {
                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'semi' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium_plus') {
                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'full' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            }
        } else {
            if ($new_plan === 'free') {
                $update_role = $this->db->prepare("UPDATE users SET role = 'member' WHERE user_id = ?");
                $update_role->bind_param("i", $this->user_id);
                $update_role->execute();
                $_SESSION['role'] = 'member';

                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'none' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium') {
                $update_role = $this->db->prepare("UPDATE users SET role = 'subscriber' WHERE user_id = ?");
                $update_role->bind_param("i", $this->user_id);
                $update_role->execute();
                $_SESSION['role'] = 'subscriber';

                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'semi' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            } elseif ($new_plan === 'premium_plus') {
                $update_role = $this->db->prepare("UPDATE users SET role = 'subscriber' WHERE user_id = ?");
                $update_role->bind_param("i", $this->user_id);
                $update_role->execute();
                $_SESSION['role'] = 'subscriber';

                $update_sub = $this->db->prepare("UPDATE subscription SET subscription_name = 'full' WHERE user_id = ?");
                $update_sub->bind_param("i", $this->user_id);
                $update_sub->execute();
            }
        }
        return "Your subscription has been changed to " . ucfirst(str_replace('_', ' ', $new_plan)) . ".";
    }

    public function handleAutoRenewChange($auto_renew)
    {
        $auto_renew_val = $auto_renew === '1' ? 1 : 0;
        $stmt = $this->db->prepare("UPDATE subscription SET auto_renew = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $auto_renew_val, $this->user_id);
        $stmt->execute();
        return "Auto-renewal has been " . ($auto_renew_val ? "enabled" : "disabled") . ".";
    }
}
