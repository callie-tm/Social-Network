<?php
/**
 * Database Configuration & Connection
 * Social Network Application
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'socialnet');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die('<div style="text-align:center;margin-top:50px;font-family:sans-serif;">
        <h2>Database Connection Error</h2>
        <p>Could not connect to the database. Please check your configuration.</p>
        <p style="color:#888;">' . $conn->connect_error . '</p>
    </div>');
}

// Set charset to utf8mb4
$conn->set_charset('utf8mb4');
?>
