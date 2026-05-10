<?php
/**
 * Session Management Functions
 * Social Network Application
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Require login - redirect to signin if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /socialnet/signin.php');
        exit();
    }
}

/**
 * Create user session after successful login
 * @param int $userId
 * @param string $username
 * @param string $fullname
 */
function createUserSession($userId, $username, $fullname) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['fullname'] = $fullname;
}

/**
 * Destroy user session (logout)
 */
function destroyUserSession() {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * Get current logged-in user ID
 * @return int|null
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current logged-in username
 * @return string|null
 */
function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}

/**
 * Get current logged-in user fullname
 * @return string|null
 */
function getCurrentFullname() {
    return $_SESSION['fullname'] ?? null;
}
?>
