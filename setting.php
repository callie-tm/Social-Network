<?php
/**
 * Settings - Edit Profile
 * Social Network Application
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$currentUser = getUserById($conn, getCurrentUserId());
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');

    if (strlen($description) > 5000) {
        $error = 'Description must be 5000 characters or less.';
    } else {
        if (updateUserDescription($conn, getCurrentUserId(), $description)) {
            $success = 'Your profile has been updated successfully!';
            $currentUser['description'] = $description;
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit your SocialNet profile settings">
    <title>Settings - SocialNet</title>
    <link rel="stylesheet" href="/socialnet/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/menubar.php'; ?>

    <div class="page-wrapper">
        <div class="container">

            <div class="page-header">
                <h1>⚙️ Profile Settings</h1>
                <p>Update your profile description</p>
            </div>

            <div class="card card-elevated">
                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?= e($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error">⚠️ <?= e($error) ?></div>
                <?php endif; ?>

                <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid var(--border);">
                    <div class="user-avatar user-avatar-lg" style="background: <?= stringToColor(getCurrentUsername()) ?>;">
                        <?= e(getInitials(getCurrentFullname())) ?>
                    </div>
                    <div>
                        <h2 style="font-size:1.2rem;font-weight:700;"><?= e(getCurrentFullname()) ?></h2>
                        <p style="color:var(--text-muted);font-size:0.9rem;">@<?= e(getCurrentUsername()) ?></p>
                    </div>
                </div>

                <form method="POST" action="" id="settingsForm">
                    <div class="form-group">
                        <label class="form-label" for="description">About Me</label>
                        <textarea id="description" name="description" class="form-textarea"
                                  placeholder="Tell others about yourself..." rows="6"
                                  maxlength="5000"><?= e($currentUser['description'] ?? '') ?></textarea>
                        <div style="text-align:right;margin-top:6px;font-size:0.75rem;color:var(--text-muted);">
                            <span id="charCount">0</span> / 5000
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;justify-content:flex-end;">
                        <a href="/socialnet/profile.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <footer class="footer">
        <p>© <?= date('Y') ?> SocialNet. All rights reserved.</p>
    </footer>

    <script src="/socialnet/assets/script.js"></script>
    <script>
        const textarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        function updateCount() { charCount.textContent = textarea.value.length; }
        textarea.addEventListener('input', updateCount);
        updateCount();
    </script>
</body>
</html>
