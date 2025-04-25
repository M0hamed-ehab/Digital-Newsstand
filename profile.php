<?php
session_start();
include_once 'config/Database.php';
$database = new Database();
$conn = $database->connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    echo "Error: Could not retrieve user information.";
    exit();
}

$errors = [];
if (isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    if (empty($new_username)) {
        $errors[] = "Username is required.";
    }

    if (empty($new_email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $update_query = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $new_username, $new_email, $user_id);

        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $user['username'] = $new_username;
            $user['email'] = $new_email;
        } else {
            $error_message = "Error updating profile.";
        }

        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - The Global Herald</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.7;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
        }

        h2 {
            color: #212529;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 0.3rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.3rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .logout-link {
            margin-top: 0.3rem;
            text-align: center;
            color: #dc3545;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-left: 10px;
            transition: color 0.2s ease-in-out;
        }

        .logout-link:hover {
            color: #bd2130;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-user-cog"></i> Edit Profile</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= htmlspecialchars($user['name']) ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?= htmlspecialchars($user['email']) ?>">
            </div>

            <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
            <button type="button" class="btn btn-primary" name="Subscription"
                onclick="window.location.href='subscription.php'">Subscription</button>
        </form>

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>