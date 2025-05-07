<?php
class Plans
{
    private $db;
    public $plans = [];

    public function __construct($db)
    {
        $this->db = $db;
        $this->loadPlans();
    }

    private function loadPlans()
    {
        $this->plans = [];
        $query = "SELECT plan_ID, plan_name, price, popular, features, description FROM plans";
        $result = $this->db->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $features = explode('**', $row['features']);
                $this->plans[] = [
                    'plan_ID' => (int) $row['plan_ID'],
                    'plan_name' => $row['plan_name'],
                    'price' => $row['price'],
                    'popular' => (bool) $row['popular'],
                    'features' => $features,
                    'description' => $row['description']
                ];
            }
        }
    }

    public function getPlans()
    {
        return $this->plans;
    }
}
?>