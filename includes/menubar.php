<?php
/**
 * Reusable Navigation Bar
 * Social Network Application
 */

// Determine active page
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar" id="mainNavbar">
    <div class="nav-container">
        <a href="/socialnet/index.php" class="nav-brand">
            <span class="brand-icon">🌐</span>
            <span class="brand-text">SocialNet</span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span class="hamburger"></span>
        </button>

        <ul class="nav-links" id="navLinks">
            <li>
                <a href="/socialnet/index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                    <span class="nav-icon">🏠</span>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="/socialnet/setting.php" class="nav-link <?= $currentPage === 'setting.php' ? 'active' : '' ?>">
                    <span class="nav-icon">⚙️</span>
                    <span>Setting</span>
                </a>
            </li>
            <li>
                <a href="/socialnet/profile.php" class="nav-link <?= $currentPage === 'profile.php' ? 'active' : '' ?>">
                    <span class="nav-icon">👤</span>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="/socialnet/about.php" class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>">
                    <span class="nav-icon">ℹ️</span>
                    <span>About</span>
                </a>
            </li>
            <li>
                <a href="/socialnet/signout.php" class="nav-link nav-signout">
                    <span class="nav-icon">🚪</span>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>

        <div class="nav-user">
            <div class="nav-avatar" style="background: <?= stringToColor(getCurrentUsername() ?? '') ?>;">
                <?= e(getInitials(getCurrentFullname() ?? '')) ?>
            </div>
            <span class="nav-username"><?= e(getCurrentFullname()) ?></span>
        </div>
    </div>
</nav>
