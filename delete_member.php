<?php
include('includes/session.php');
include('includes/db_config.php');

// Allow only Admins to delete members
if ($role !== 'Admin') {
    die("Access denied. Only admins can perform this action.");
}

// Validate ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $member_id = (int) $_GET['id'];

    // Prepare deletion
    $stmt = $conn->prepare("DELETE FROM members WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);

    if ($stmt->execute()) {
        header("Location: members.php?success=1");
    } else {
        echo "Error deleting member.";
    }
    $stmt->close();
} else {
    echo "Invalid member ID.";
}
?>
