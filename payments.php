<?php
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

// Fetch payment records
$query = "
    SELECT p.payment_id, p.amount, p.payment_date, p.status, m.full_name 
    FROM payments p 
    JOIN members m ON p.member_id = m.member_id 
    ORDER BY p.payment_date DESC
";
$payments = mysqli_query($conn, $query);
?>

<!-- Title & Add Payment Button -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Payment History</h1>
        <p class="text-gray-500 text-sm">Overview of all member transactions</p>
    </div>
    <?php if ($role === 'Admin'): ?>
    <button onclick="document.getElementById('addPaymentModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Payment
    </button>
    <?php endif; ?>
</div>

<!-- Payment Table -->
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm text-left text-gray-600">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="p-4">Member</th>
                <th class="p-4">Amount</th>
                <th class="p-4">Date</th>
                <th class="p-4">Status</th>
                <?php if ($role === 'Admin'): ?>
                    <th class="p-4">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($payments)): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4"><?= htmlspecialchars($row['full_name']) ?></td>
                    <td class="p-4">$<?= number_format($row['amount'], 2) ?></td>
                    <td class="p-4"><?= $row['payment_date'] ?></td>
                    <td class="p-4">
                        <span class="text-sm px-2 py-1 rounded-full 
                            <?= $row['status'] === 'Paid' ? 'bg-green-100 text-green-700' : 
                                 ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <?php if ($role === 'Admin'): ?>
                        <td class="p-4">
                            <a href="edit_payment.php?id=<?= $row['payment_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Payment Modal (Admin Only) -->
<?php if ($role === 'Admin'): ?>
<div id="addPaymentModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md relative">
        <h2 class="text-xl font-bold mb-4">Add New Payment</h2>
        <form method="POST" action="add_payment.php" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Member</label>
                <select name="member_id" class="w-full px-3 py-2 border rounded" required>
                    <?php
                    $members = mysqli_query($conn, "SELECT member_id, full_name FROM members WHERE status='Active'");
                    while ($m = mysqli_fetch_assoc($members)) {
                        echo "<option value='{$m['member_id']}'>" . htmlspecialchars($m['full_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Amount</label>
                <input type="number" name="amount" step="0.01" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded">
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addPaymentModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
        <button onclick="document.getElementById('addPaymentModal').classList.add('hidden')" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
<?php endif; ?>

<?php include('includes/footer.php'); ?>
