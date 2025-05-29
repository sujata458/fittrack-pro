<?php
include('includes/session.php');
include('includes/db_config.php');

// Restrict access to Admins only
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    die("Access denied – Admin only.");
}


$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $user_role = $_POST['role'];

    if ($username && $password && in_array($user_role, ['Admin', 'Trainer'])) {
        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $user_role);

        if ($stmt->execute()) {
            $success = "User added successfully.";
        } else {
            $error = "Username already exists or error inserting data.";
        }

        $stmt->close();
    } else {
        $error = "Please fill out all fields correctly.";
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Add New User</h2>

    <?php if ($success): ?>
        <p class="text-green-600 mb-4"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="text-red-600 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Username</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Role</label>
            <select name="role" class="w-full px-3 py-2 border rounded" required>
                <option value="Trainer">Trainer</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create User
        </button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
