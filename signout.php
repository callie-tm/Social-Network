<?php
/**
 * Sign Out
 * Social Network Application
 */
require_once __DIR__ . '/includes/session.php';

destroyUserSession();
header('Location: /socialnet/signin.php');
exit();
?>
