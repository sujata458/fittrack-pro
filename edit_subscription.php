<?php
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

$id = $_GET['id'] ?? 0;
$query = "SELECT * FROM subscriptions WHERE subscription_id = $id";
$result = mysqli_query($conn, $query);
$subscription = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_name = $_POST['plan_name'];
    $price = $_POST['price'];
    $billing_cycle = $_POST['billing_cycle'];
    $next_billing_date = $_POST['next_billing_date'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $update = "UPDATE subscriptions SET plan_name='$plan_name', price='$price', billing_cycle='$billing_cycle', 
               next_billing_date='$next_billing_date', payment_method='$payment_method', status='$status' 
               WHERE subscription_id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: admin_subscriptions.php?msg=updated");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-5">
    <h2>Edit Subscription</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Plan Name</label>
            <input type="text" name="plan_name" value="<?= $subscription['plan_name'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?= $subscription['price'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Billing Cycle</label>
            <select name="billing_cycle" class="form-control">
                <option <?= $subscription['billing_cycle'] == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                <option <?= $subscription['billing_cycle'] == 'Quarterly' ? 'selected' : '' ?>>Quarterly</option>
                <option <?= $subscription['billing_cycle'] == 'Yearly' ? 'selected' : '' ?>>Yearly</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Next Billing Date</label>
            <input type="date" name="next_billing_date" value="<?= $subscription['next_billing_date'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control">
                <option <?= $subscription['payment_method'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                <option <?= $subscription['payment_method'] == 'Direct Debit' ? 'selected' : '' ?>>Direct Debit</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option <?= $subscription['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option <?= $subscription['status'] == 'Paused' ? 'selected' : '' ?>>Paused</option>
                <option <?= $subscription['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
