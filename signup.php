<?php
session_start();
include 'config/Database.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
        exit;
    }

    $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email already taken.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    $stmt = $db->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['just_signed_up'] = true;
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $db->error;
    }

    $stmt->close();
}

$db->close();
?>