<?php
include('includes/session.php');
include('includes/db_config.php');

if (strtolower($role) !== 'admin') {
    die("❌ Access denied. Only admins can add payments.");
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $payment_date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO payments (member_id, amount, payment_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $member_id, $amount, $payment_date, $status);

    if ($stmt->execute()) {
        $success = "✅ Payment added successfully.";
    } else {
        $error = "❌ Error: " . $stmt->error;
    }
}
$members = mysqli_query($conn, "SELECT member_id, full_name FROM members ORDER BY full_name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">💳 Add Payment</h2>

        <?php if ($success): ?><p class="text-green-600"><?= $success ?></p><?php endif; ?>
        <?php if ($error): ?><p class="text-red-600"><?= $error ?></p><?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label>Member</label>
                <select name="member_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Member</option>
                    <?php while ($row = mysqli_fetch_assoc($members)): ?>
                        <option value="<?= $row['member_id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label>Amount ($)</label>
                <input type="number" step="0.01" name="amount" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label>Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Payment</button>
        </form>
    </div>
</body>
</html>
