<?php
include('includes/session.php');
include('includes/db_config.php');

// Restrict to Admins only
if ($role !== 'Admin') {
    die("Access denied. Admins only.");
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];

    if ($name && $email && $phone && in_array($status, ['Active', 'Pending', 'Expired'])) {
        $stmt = $conn->prepare("INSERT INTO members (full_name, email, phone, join_date, status) VALUES (?, ?, ?, CURDATE(), ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $status);

        if ($stmt->execute()) {
            $success = "Member added successfully!";
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Register New Member</h2>

    <?php if ($success): ?>
        <p class="text-green-600 mb-4"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="text-red-600 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Full Name</label>
            <input type="text" name="full_name" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Phone</label>
            <input type="text" name="phone" required class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded" required>
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
                <option value="Expired">Expired</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add Member
        </button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
