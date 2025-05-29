<?php
session_start();
include('includes/session.php');
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if (strtolower($_SESSION['role']) !== 'admin') {
    echo "<div class='p-4 text-red-600'>Access denied.</div>";
    exit();
}

$result = mysqli_query($conn, "SELECT payment_date, SUM(amount) AS total_amount FROM payments WHERE status = 'Paid' GROUP BY payment_date ORDER BY payment_date DESC");

if (!$result) {
    echo "<div class='p-4 text-red-600'>Query failed: " . mysqli_error($conn) . "</div>";
    exit();
}
?>

<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">💰 Revenue Report</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border">Date</th>
                    <th class="py-2 px-4 border">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="text-center hover:bg-gray-50">
                        <td class="py-2 px-4 border"><?= $row['payment_date'] ?></td>
                        <td class="py-2 px-4 border">$<?= number_format($row['total_amount'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
