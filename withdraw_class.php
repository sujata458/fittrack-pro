<?php
include('includes/member_session.php');
include('includes/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = (int) $_POST['class_id'];

    // Check if enrolled
    $check = $conn->prepare("SELECT * FROM enrollments WHERE member_id = ? AND class_id = ?");
    $check->bind_param("ii", $member_id, $class_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        header("Location: member_dashboard.php?error=not_enrolled");
        exit();
    }

    // Delete enrollment
    $delete = $conn->prepare("DELETE FROM enrollments WHERE member_id = ? AND class_id = ?");
    $delete->bind_param("ii", $member_id, $class_id);
    $delete->execute();

    // Decrease class count
    $conn->query("UPDATE classes SET enrolled = enrolled - 1 WHERE class_id = $class_id AND enrolled > 0");

    header("Location: member_dashboard.php?success=withdrawn");
    exit();
} else {
    header("Location: member_dashboard.php?error=invalid_request");
    exit();
}
