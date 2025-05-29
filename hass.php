<?php
include('includes/db_config.php');

$username = 'trainer1';
$password = 'trainer123';
$role = 'trainer';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hash, $role);

if ($stmt->execute()) {
    echo "✅ Admin user created successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}
?>
