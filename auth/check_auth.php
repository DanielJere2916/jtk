<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session before any output
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/logout.php');
    exit();
}

// Include database connection
include_once('connection.php');

// Check if session is valid
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: ../auth/logout.php');
    exit();
}

$_SESSION['last_activity'] = time();
?>