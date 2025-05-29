<?php
include('includes/session.php');
include('includes/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id']) && $role === 'Admin') {
    $class_id = intval($_POST['class_id']);

    // Optional: Check if class has enrolled members first
    $check = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM enrollments WHERE class_id = $class_id");
    $count = mysqli_fetch_assoc($check)['cnt'];

    if ($count > 0) {
        // Optional: warn instead of delete
        header("Location: classes.php?error=class_has_members");
        exit;
    }

    mysqli_query($conn, "DELETE FROM classes WHERE class_id = $class_id");
}

header("Location: classes.php");
exit();
