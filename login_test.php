<?php
session_start();
include('includes/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash, $role);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            echo "✅ Login successful as $role";
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ Username not found.";
    }
}
?>

<form method="POST">
    <input name="username" placeholder="Username"><br>
    <input name="password" placeholder="Password" type="password"><br>
    <button type="submit">Login</button>
</form>
