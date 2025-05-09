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
    <?php if ($dark_mode): ?>
        <link rel="stylesheet" href="style/profile-dark.css">
    <?php endif; ?>
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
            <form id="darkModeForm" method="post" style="display:inline;">
                <input type="hidden" name="toggle_dark_mode" value="1" />
                <button type="submit" id="darkModeToggle" class="btn btn-primary">
                    <?= $dark_mode ? 'Light Mode' : 'Dark Mode' ?>
                </button>
            </form>
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