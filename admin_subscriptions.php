<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

// SQL query with JOIN and error handling
$query = "SELECT s.*, m.full_name FROM subscriptions s
          JOIN members m ON s.member_id = m.member_id
          ORDER BY s.subscription_id DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("<div class='alert alert-danger m-4'>❌ SQL Error: " . mysqli_error($conn) . "</div>");
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="fas fa-box-open me-2"></i>All Subscriptions</h2>
        <a href="add_subscription.php" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i>Add Subscription
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Plan</th>
                            <th>Billing</th>
                            <th>Price</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>Next Billing</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="text-center">
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td><?= htmlspecialchars($row['plan_name']) ?></td>
                                    <td><?= htmlspecialchars($row['billing_cycle']) ?></td>
                                    <td>$<?= number_format($row['price'], 2) ?></td>
                                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $row['status'] == 'Active' ? 'bg-success' : 
                                                ($row['status'] == 'Paused' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= isset($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : 'N/A' ?></td>
                                    <td><?= htmlspecialchars($row['next_billing_date']) ?></td>
                                    <td>
                                        <a href="edit_subscription.php?id=<?= $row['subscription_id'] ?>" class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_subscription.php?id=<?= $row['subscription_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>No subscriptions found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
