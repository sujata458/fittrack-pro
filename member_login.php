<?php
session_start();
include('includes/db_config.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $password_hash, $role);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                if (strtolower($role) === 'member') {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'member';
                    header("Location: member_dashboard.php");
                    exit();
                } else {
                    $error = "Only members can log in here.";
                }
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    } else {
        $error = "Please enter username/email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Login | FitTrack Pro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center text-blue-700">Member Login</h2>

    <?php if ($error): ?>
        <p class="text-red-600 mb-4 text-center"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Username or Email</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
            Login
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-4 text-center">
        Only approved members can access their dashboard.
    </p>
</div>

</body>
</html>
