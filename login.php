<?php
session_start();
include('includes/db_config.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $password_hash, $role);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = strtolower($role);

            // Role-based redirect
            switch (strtolower($role)) {
                case 'admin': header("Location: admin_dashboard.php"); break;
                case 'trainer': header("Location: trainer_dashboard.php"); break;
                case 'member': header("Location: member_dashboard.php"); break;
                default: $error = "Unknown role."; break;
            }
            exit();
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ User not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | FitTrack Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-6">FitTrack Pro Login</h2>

        <?php if ($error): ?>
            <p class="text-red-600 mb-4 text-sm text-center"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm">Username or Email</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
    </div>
</body>
</html>
