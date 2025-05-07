<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/Database.php';

class LoginController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (empty($username) || empty($password)) {
                echo json_encode('All fields are required.');
                exit;
            }

            $stmt = $this->db->prepare("SELECT user_id, password, role FROM users WHERE name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id, $storedPassword, $role);
                $stmt->fetch();

                if ($password === $storedPassword) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['name'] = $username;
                    $_SESSION['role'] = $role;
                    echo 'success';
                    exit;
                } else {
                    echo json_encode('Invalid username or password.');
                }
            } else {
                echo json_encode('Invalid username or password.');
            }

            $stmt->close();
        }
        $this->db->close();
    }
}
?>