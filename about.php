<?php
/**
 * About Page
 * Social Network Application
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About SocialNet - Student project information">
    <title>About - SocialNet</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/menubar.php'; ?>

    <div class="page-wrapper">
        <div class="container">

            <div class="page-header">
                <h1>About This Project</h1>
                <p>Student information &amp; project details</p>
            </div>

            <div class="card card-elevated about-card">
                <div class="about-emoji">🎓</div>
                <h2>Student Name</h2>
                <p class="student-id">Student ID: XXXXXXXX</p>
                <div class="about-info">
                    <p>This is a Social Network web application built as a course project.</p>
                    <p>The application demonstrates core web development concepts including user authentication, session management, CRUD operations, and responsive design.</p>
                </div>
                <div class="tech-stack">
                    <span class="tech-tag">PHP</span>
                    <span class="tech-tag">MySQL</span>
                    <span class="tech-tag">Nginx</span>
                    <span class="tech-tag">Linux</span>
                    <span class="tech-tag">HTML5</span>
                    <span class="tech-tag">CSS3</span>
                    <span class="tech-tag">JavaScript</span>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer">
        <p>© <?= date('Y') ?> SocialNet. All rights reserved.</p>
    </footer>

    <script src="/assets/script.js"></script>
</body>
</html>
