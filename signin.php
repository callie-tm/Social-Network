<?php
/**
 * Sign In Page
 * Social Network Application
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: /socialnet/index.php');
    exit();
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $user = getUserByUsername($conn, $username);
        if ($user && password_verify($password, $user['password'])) {
            createUserSession($user['id'], $user['username'], $user['fullname']);
            header('Location: /socialnet/index.php');
            exit();
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sign in to SocialNet">
    <title>Sign In - SocialNet</title>
    <link rel="stylesheet" href="/socialnet/assets/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">🌐</div>
                <h1>Welcome Back</h1>
                <p>Sign in to your SocialNet account</p>
            </div>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-error">⚠️ <?= e($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form method="POST" action="" id="signinForm">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input"
                           placeholder="Enter your username" value="<?= e($username) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                           placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <div style="text-align:center;margin-top:20px;">
                <a href="/socialnet/admin/newuser.php" style="color:var(--text-muted);font-size:0.85rem;">Admin: Create New User →</a>
            </div>
        </div>
    </div>
    <script src="/socialnet/assets/script.js"></script>
</body>
</html>
