<?php
include('includes/db_config.php');

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name && $email && $phone) {
        // Check if already registered
        $stmt = $conn->prepare("SELECT member_id FROM members WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "A member with this email or phone already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO members (full_name, email, phone, join_date, status) VALUES (?, ?, ?, CURDATE(), 'Pending')");
            $stmt->bind_param("sss", $name, $email, $phone);

            if ($stmt->execute()) {
                $success = "Registration submitted. Awaiting admin approval.";
            } else {
                $error = "Error saving data. Please try again.";
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | FitTrack Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center text-blue-700">Member Registration</h2>

    <?php if ($success): ?>
        <p class="text-green-600 mb-4"><?= $success ?></p>
    <?php elseif ($error): ?>
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

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">
            Submit Registration
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-4 text-center">
        You will receive access once an admin approves your account.
    </p>
</div>

</body>
</html>
