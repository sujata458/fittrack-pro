<?php
include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $plan_name = $_POST['plan_name'];
    $price = $_POST['price'];
    $billing_cycle = $_POST['billing_cycle'];
    $next_billing_date = $_POST['next_billing_date'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $query = "INSERT INTO subscriptions (member_id, plan_name, price, billing_cycle, next_billing_date, payment_method, status)
              VALUES ('$member_id', '$plan_name', '$price', '$billing_cycle', '$next_billing_date', '$payment_method', '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_subscriptions.php?msg=added");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-5">
    <h2>Add Subscription</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Member ID</label>
            <input type="number" name="member_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Plan Name</label>
            <input type="text" name="plan_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Billing Cycle</label>
            <select name="billing_cycle" class="form-control">
                <option value="Monthly">Monthly</option>
                <option value="Quarterly">Quarterly</option>
                <option value="Yearly">Yearly</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Next Billing Date</label>
            <input type="date" name="next_billing_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Payment Method</label>
            <select name="payment_method" class="form-control">
                <option value="Credit Card">Credit Card</option>
                <option value="Direct Debit">Direct Debit</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Paused">Paused</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Subscription</button>
    </form>
</div>
