<?php
session_start();

// Restrict access to logged-in members only
if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$member_name = $_SESSION['member_name'];
