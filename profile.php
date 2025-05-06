<?php
session_start();
include_once 'config/Database.php';
include_once 'classes/profile.php';

$conn = Database::getInstance()->getConnection();
$profile = new profile($conn);

try {
    $user = $profile->getUserInfo();
} catch (Exception $e) {
    echo $e->getMessage();

    exit();
}

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $response = $profile->updateProfile($_POST['username'], $_POST['email']);

        if ($response['success']) {
            $success_message = $response['message'];
            $user['name'] = $_POST['username'];
            $user['email'] = $_POST['email'];
        } else {
            $errors = $response['errors'];
        }
    } elseif (isset($_POST['change_password'])) {
        $response = $profile->changePassword($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);

        if ($response['success']) {
            $success_message = $response['message'];
        } else {
            $errors = $response['errors'];
        }
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
    <link rel="icon" type="image/png" href="/images/user.png">
    <link rel="stylesheet" href="style/profile.css">
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-user-cog"></i> Edit Profile</h2>

        <?php if (!empty($success_message)): ?>
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
            <div class="mt-3">
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </form>

        <hr>

        <h3>Change Password</h3>
        <form method="post">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
        </form>

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>