<?php
/**
 * Home Page - List All Users
 * Social Network Application
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$currentUser = getUserById($conn, getCurrentUserId());
$allUsers = getAllUsers($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SocialNet Home - Browse members">
    <title>Home - SocialNet</title>
    <link rel="stylesheet" href="/socialnet/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/menubar.php'; ?>

    <div class="page-wrapper">
        <div class="container-wide">

            <div class="welcome-banner">
                <h2>👋 Welcome, <?= e(getCurrentFullname()) ?>!</h2>
                <p>Explore profiles and connect with other members of the community.</p>
            </div>

            <div class="section-header">
                <h2>All Members</h2>
                <span class="badge"><?= count($allUsers) ?> member<?= count($allUsers) !== 1 ? 's' : '' ?></span>
            </div>

            <div class="user-grid">
                <?php foreach ($allUsers as $user): ?>
                    <a href="/socialnet/profile.php?owner=<?= urlencode($user['username']) ?>" class="user-card" id="user-card-<?= e($user['username']) ?>">
                        <div class="user-card-inner">
                            <div class="user-card-header">
                                <div class="user-avatar" style="background: <?= stringToColor($user['username']) ?>;">
                                    <?= e(getInitials($user['fullname'])) ?>
                                </div>
                                <div class="user-info">
                                    <h3><?= e($user['fullname']) ?></h3>
                                    <span class="username">@<?= e($user['username']) ?></span>
                                </div>
                            </div>
                            <?php if (!empty($user['description'])): ?>
                                <p class="user-card-desc"><?= e($user['description']) ?></p>
                            <?php else: ?>
                                <p class="user-card-desc" style="font-style:italic;color:var(--text-muted);">No description yet.</p>
                            <?php endif; ?>
                            <div class="user-card-meta">
                                <span>📅</span>
                                <span>Joined <?= formatDate($user['created_at']) ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <footer class="footer">
        <p>© <?= date('Y') ?> SocialNet. All rights reserved.</p>
    </footer>

    <script src="/socialnet/assets/script.js"></script>
</body>
</html>
