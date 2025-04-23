<?php
include 'config/Database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "email already taken.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (name, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email); 

    if ($stmt->execute()) {
        header("Location: product.html");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
