<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assign session values
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '';
$role = strtolower($_SESSION['role'] ?? '');

// Optional: redirect if not logged in
if (!$user_id) {
    header("Location: login.php");
    exit();
}
?>
