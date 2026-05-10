<?php
/**
 * Helper Functions
 * Social Network Application
 */

/**
 * Sanitize user input
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Display escaped output
 * @param string $str
 * @return string
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Validate username format
 * @param string $username
 * @return bool
 */
function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username);
}

/**
 * Check if username exists in database
 * @param mysqli $conn
 * @param string $username
 * @return bool
 */
function usernameExists($conn, $username) {
    $stmt = $conn->prepare("SELECT id FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Get user by username
 * @param mysqli $conn
 * @param string $username
 * @return array|null
 */
function getUserByUsername($conn, $username) {
    $stmt = $conn->prepare("SELECT id, username, fullname, password, description, created_at FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

/**
 * Get user by ID
 * @param mysqli $conn
 * @param int $userId
 * @return array|null
 */
function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT id, username, fullname, description, created_at FROM account WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

/**
 * Get all users
 * @param mysqli $conn
 * @return array
 */
function getAllUsers($conn) {
    $result = $conn->query("SELECT id, username, fullname, description, created_at FROM account ORDER BY created_at DESC");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

/**
 * Create new user
 * @param mysqli $conn
 * @param string $username
 * @param string $fullname
 * @param string $password (plain text - will be hashed)
 * @return bool
 */
function createUser($conn, $username, $fullname, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO account (username, fullname, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $fullname, $hashedPassword);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

/**
 * Update user description
 * @param mysqli $conn
 * @param int $userId
 * @param string $description
 * @return bool
 */
function updateUserDescription($conn, $userId, $description) {
    $stmt = $conn->prepare("UPDATE account SET description = ? WHERE id = ?");
    $stmt->bind_param("si", $description, $userId);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

/**
 * Format date to readable string
 * @param string $datetime
 * @return string
 */
function formatDate($datetime) {
    return date('F j, Y \a\t g:i A', strtotime($datetime));
}

/**
 * Generate avatar initials from fullname
 * @param string $fullname
 * @return string
 */
function getInitials($fullname) {
    $words = explode(' ', trim($fullname));
    $initials = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }
    }
    return mb_substr($initials, 0, 2);
}

/**
 * Generate a consistent color from a string (for avatars)
 * @param string $str
 * @return string HSL color
 */
function stringToColor($str) {
    $hash = crc32($str);
    $hue = abs($hash) % 360;
    return "hsl($hue, 65%, 55%)";
}
?>
