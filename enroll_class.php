<?php
include('includes/member_session.php');
include('includes/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = (int) $_POST['class_id'];

    // Check if already enrolled
    $check = $conn->prepare("SELECT * FROM enrollments WHERE member_id = ? AND class_id = ?");
    $check->bind_param("ii", $member_id, $class_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: member_dashboard.php?error=already_enrolled");
        exit();
    }

    // Check if class is full
    $cap = $conn->prepare("SELECT capacity, enrolled FROM classes WHERE class_id = ?");
    $cap->bind_param("i", $class_id);
    $cap->execute();
    $cap_result = $cap->get_result()->fetch_assoc();

    if ($cap_result['enrolled'] >= $cap_result['capacity']) {
        header("Location: member_dashboard.php?error=full");
        exit();
    }

    // Enroll
    $insert = $conn->prepare("INSERT INTO enrollments (member_id, class_id) VALUES (?, ?)");
    $insert->bind_param("ii", $member_id, $class_id);
    $insert->execute();

    // Increment class enrollment
    $conn->query("UPDATE classes SET enrolled = enrolled + 1 WHERE class_id = $class_id");

    header("Location: member_dashboard.php?success=enrolled");
    exit();
} else {
    header("Location: member_dashboard.php?error=invalid_request");
    exit();
}
