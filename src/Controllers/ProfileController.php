<?php
session_start();
include_once '../config/Database.php';
include_once '../src/Models/profile.php';

class ProfileController
{
    private $conn;
    private $profile;
    public $user;
    public $errors = [];
    public $success_message = '';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->profile = new profile($this->conn);

        try {
            $this->user = $this->profile->getUserInfo();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $response = $this->profile->updateProfile($_POST['username'], $_POST['email']);

                if ($response['success']) {
                    $this->success_message = $response['message'];
                    $this->user['name'] = $_POST['username'];
                    $this->user['email'] = $_POST['email'];
                } else {
                    $this->errors = $response['errors'];
                }
            } elseif (isset($_POST['change_password'])) {
                $response = $this->profile->changePassword($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);

                if ($response['success']) {
                    $this->success_message = $response['message'];
                } else {
                    $this->errors = $response['errors'];
                }
            }
        }
        $user = $this->user;
        $success_message = $this->success_message;
        $error_message = isset($this->errors) && !empty($this->errors) ? implode(', ', $this->errors) : null;
        $errors = $this->errors;
        include '../src/views/profile_view.php';
    }
}
?>