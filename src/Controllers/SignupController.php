<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/Database.php';
include_once '../src/Models/User.php';

class SignupController
{
    private $db;
    private $user;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->user = new User($this->db);
    }

    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $email = trim($_POST['email']);

            if (empty($username) || empty($password) || empty($email)) {
                echo json_encode('All fields are required.');
                exit;
            }

            $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo json_encode('Email already taken.');
                $stmt->close();
                exit;
            }
            $stmt->close();

            $stmt = $this->db->prepare("SELECT user_id FROM users WHERE name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo json_encode('Username already taken.');
                $stmt->close();
                exit;
            }
            $stmt->close();

            $stmt = $this->db->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['just_signed_up'] = true;
                echo ('Signup successful');
                exit;
            } else {
                echo json_encode('Error: ' . $this->db->error);
            }

            $stmt->close();
        }
        $this->db->close();
    }
}
?>