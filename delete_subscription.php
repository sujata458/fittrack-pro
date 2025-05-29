<?php
include('includes/db_config.php');

$id = $_GET['id'] ?? 0;

$query = "DELETE FROM subscriptions WHERE subscription_id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: admin_subscriptions.php?msg=deleted");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
