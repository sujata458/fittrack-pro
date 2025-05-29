<?php
include('includes/db_config.php');

// Query counts
$active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM members WHERE status = 'Active'"))['count'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM members WHERE status = 'Pending'"))['count'];
$expired = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM members WHERE status = 'Expired'"))['count'];

echo json_encode([
    'labels' => ['Active', 'Pending', 'Expired'],
    'data' => [$active, $pending, $expired]
]);
?>
