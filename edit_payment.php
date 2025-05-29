<?php
session_start();
include('includes/db_config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied.");
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";

// Handle form submission BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $status = $_POST['status'];

    $update = "UPDATE payments SET amount = ?, status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("dsi", $amount, $status, $payment_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: payments.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
        $stmt->close();
    }
}

// Now continue output safely
include('includes/header.php');
include('includes/sidebar.php');

// Fetch payment details
$query = "SELECT p.*, m.full_name FROM payments p JOIN members m ON p.member_id = m.member_id WHERE p.payment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<div class='p-6 text-red-600 font-semibold'>Invalid payment ID.</div>";
    include('includes/footer.php');
    exit();
}

$payment = $result->fetch_assoc();
$stmt->close();
?>

<div class="max-w-xl mx-auto bg-white rounded shadow p-6 mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Payment for <?= htmlspecialchars($payment['full_name']) ?></h1>

    <?php if (!empty($error)): ?>
        <div class="mb-4 text-red-600"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Amount</label>
            <input type="number" step="0.01" name="amount" value="<?= $payment['amount'] ?>" class="w-full px-3 py-2 border rounded" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full px-3 py-2 border rounded" required>
                <option value="Paid" <?= $payment['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                <option value="Pending" <?= $payment['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Failed" <?= $payment['status'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Payment</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
