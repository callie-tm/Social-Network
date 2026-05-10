<?php
/**
 * Admin - Create New User
 * Social Network Application
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$errors = [];
$success = '';
$username = '';
$fullname = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!isValidUsername($username)) {
        $errors[] = 'Username must be 3-50 characters (letters, numbers, underscore only).';
    } elseif (usernameExists($conn, $username)) {
        $errors[] = 'Username already exists. Please choose another.';
    }

    if (empty($fullname)) {
        $errors[] = 'Full name is required.';
    } elseif (strlen($fullname) > 100) {
        $errors[] = 'Full name must be 100 characters or less.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    // Create user if no errors
    if (empty($errors)) {
        if (createUser($conn, $username, $fullname, $password)) {
            $success = "User \"$fullname\" (@$username) created successfully!";
            $username = '';
            $fullname = '';
        } else {
            $errors[] = 'Failed to create user. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin - Create new user for SocialNet">
    <title>Create New User - SocialNet Admin</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">🛡️</div>
                <h1>Admin Panel</h1>
                <p>Create a new user account</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?= e($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-error">⚠️ <?= e($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="POST" action="" id="newUserForm">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input"
                           placeholder="e.g. johndoe" value="<?= e($username) ?>" required
                           pattern="[a-zA-Z0-9_]{3,50}" title="3-50 characters: letters, numbers, underscore">
                </div>

                <div class="form-group">
                    <label class="form-label" for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="form-input"
                           placeholder="e.g. John Doe" value="<?= e($fullname) ?>" required maxlength="100">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                           placeholder="Minimum 6 characters" required minlength="6">
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                           placeholder="Re-enter password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create User</button>
            </form>

            <div style="text-align:center;margin-top:20px;">
                <a href="/socialnet/signin.php" style="color:var(--text-muted);font-size:0.85rem;">← Back to Sign In</a>
            </div>
        </div>
    </div>
    <script src="/assets/script.js"></script>
</body>
</html>
