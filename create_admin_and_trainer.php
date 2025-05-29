<?php
include('includes/db_config.php');

$users = [
    ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
    ['username' => 'trainer1', 'password' => 'trainer123', 'role' => 'trainer']
];

foreach ($users as $user) {
    $hash = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), role = VALUES(role)");
    $stmt->bind_param("sss", $user['username'], $hash, $user['role']);

    if ($stmt->execute()) {
        echo "✅ User '{$user['username']}' created or updated successfully.<br>";
    } else {
        echo "❌ Error for '{$user['username']}': " . $stmt->error . "<br>";
    }
}
?>
