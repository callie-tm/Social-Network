<?php
/**
 * Profile Page
 * Social Network Application
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

// Determine profile owner
$ownerUsername = $_GET['owner'] ?? getCurrentUsername();
$profileUser = getUserByUsername($conn, $ownerUsername);

if (!$profileUser) {
    $notFound = true;
} else {
    $notFound = false;
    $isOwnProfile = ($profileUser['id'] == getCurrentUserId());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $notFound ? 'User not found' : e($profileUser['fullname']) . ' profile on SocialNet' ?>">
    <title><?= $notFound ? 'User Not Found' : e($profileUser['fullname']) ?> - SocialNet</title>
    <link rel="stylesheet" href="/socialnet/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/menubar.php'; ?>

    <div class="page-wrapper">
        <div class="container">

            <?php if ($notFound): ?>
                <div class="card card-elevated" style="text-align:center;padding:60px 32px;">
                    <div style="font-size:3rem;margin-bottom:16px;">😕</div>
                    <h2 style="margin-bottom:8px;">User Not Found</h2>
                    <p style="color:var(--text-muted);margin-bottom:24px;">The user you're looking for doesn't exist.</p>
                    <a href="/socialnet/index.php" class="btn btn-primary">← Back to Home</a>
                </div>
            <?php else: ?>
                <div class="profile-hero">
                    <div class="profile-avatar" style="background: <?= stringToColor($profileUser['username']) ?>;">
                        <?= e(getInitials($profileUser['fullname'])) ?>
                    </div>
                    <h1 class="profile-name"><?= e($profileUser['fullname']) ?></h1>
                    <p class="profile-username">@<?= e($profileUser['username']) ?></p>
                    <p class="profile-joined">📅 Joined <?= formatDate($profileUser['created_at']) ?></p>

                    <?php if ($isOwnProfile): ?>
                        <div style="margin-top:20px;position:relative;z-index:1;">
                            <a href="/socialnet/setting.php" class="btn btn-secondary">⚙️ Edit Profile</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="profile-desc-card">
                    <h2>📝 About</h2>
                    <?php if (!empty($profileUser['description'])): ?>
                        <p><?= e($profileUser['description']) ?></p>
                    <?php else: ?>
                        <p style="font-style:italic;color:var(--text-muted);">
                            <?= $isOwnProfile ? 'You haven\'t added a description yet. Go to Settings to add one!' : 'This user hasn\'t added a description yet.' ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <footer class="footer">
        <p>© <?= date('Y') ?> SocialNet. All rights reserved.</p>
    </footer>

    <script src="/socialnet/assets/script.js"></script>
</body>
</html>
