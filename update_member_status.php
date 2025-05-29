<?php
include('includes/session.php');
include('includes/db_config.php');

// Ensure only Admins can perform this action
if ($role !== 'Admin') {
    die("Access denied.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['member_id'];
    $status = $_POST['status'];

    // Validate status value
    if (in_array($status, ['Pending', 'Active', 'Inactive'])) {
        $stmt = $conn->prepare("UPDATE members SET status = ? WHERE member_id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}

// Redirect back to the members approval page
header("Location: members.php");
exit();
