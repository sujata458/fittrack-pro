<?php
session_start();
if (strtolower($_SESSION['role']) !== 'member') {
    header("Location: login.php");
    exit();
}

include('includes/db_config.php');
include('includes/header.php');
include('includes/sidebar.php');

$username = $_SESSION['username'];
$member_result = mysqli_query($conn, "SELECT member_id FROM members WHERE email = '$username' OR full_name = '$username' LIMIT 1");
$member = mysqli_fetch_assoc($member_result);
$member_id = $member['member_id'] ?? null;

$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'], $_POST['billing'], $_POST['method']) && $_POST['method'] === 'Direct Debit') {
    $plan = $_POST['plan'];
    $billing = $_POST['billing'];
    $method = $_POST['method'];
    $start_date = date('Y-m-d');

    $interval = match ($billing) {
        'Monthly' => '+1 month',
        'Quarterly' => '+3 months',
        'Yearly' => '+12 months',
        default => '+1 month'
    };
    $end_date = date('Y-m-d', strtotime($interval));

    $prices = [
        'Basic Fit' => ['Monthly' => 29.99, 'Quarterly' => 79.99, 'Yearly' => 299.99],
        'Pro Gym Access' => ['Monthly' => 49.99, 'Quarterly' => 129.99, 'Yearly' => 449.99],
        'Elite Unlimited' => ['Monthly' => 69.99, 'Quarterly' => 179.99, 'Yearly' => 599.99],
    ];
    $price = $prices[$plan][$billing];

    $insert = mysqli_query($conn, "INSERT INTO subscriptions (member_id, plan_name, price, billing_cycle, next_billing_date, payment_method, status, created_at)
        VALUES ('$member_id', '$plan', '$price', '$billing', '$end_date', '$method', 'Active', NOW())");

    $alert = $insert
        ? "<div class='alert alert-success'>✅ Subscription started successfully via Direct Debit!</div>"
        : "<div class='alert alert-danger'>❌ Subscription failed: " . mysqli_error($conn) . "</div>";
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="bg-white p-5 rounded shadow-lg" style="max-width: 600px; margin: auto;">
    <h2 class="text-center text-primary mb-4">
      <i class="fas fa-box-open me-2"></i>Manage Your Subscription
    </h2>
    <?= $alert ?>
    <form method="POST" id="subscriptionForm">
      <input type="hidden" name="member_id" value="<?= $member_id ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Select Plan</label>
        <select name="plan" id="planSelect" class="form-select shadow-sm" required>
          <option value="Basic Fit">Basic Fit</option>
          <option value="Pro Gym Access">Pro Gym Access</option>
          <option value="Elite Unlimited">Elite Unlimited</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Billing Cycle</label>
        <select name="billing" id="billingSelect" class="form-select shadow-sm" required>
          <option value="Monthly">Monthly</option>
          <option value="Quarterly">Quarterly</option>
          <option value="Yearly">Yearly</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Payment Method</label>
        <select name="method" id="methodSelect" class="form-select shadow-sm" required>
          <option value="Direct Debit">Direct Debit</option>
          <option value="Credit Card">Credit Card</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Estimated Price</label>
        <input type="text" id="priceDisplay" class="form-control shadow-sm bg-light" readonly>
      </div>
      <button type="submit" class="btn btn-success w-100 fw-semibold">
        <i class="fas fa-check-circle me-1"></i>Proceed
      </button>
    </form>
  </div>
</div>

<script>
const prices = {
  "Basic Fit": { Monthly: 29.99, Quarterly: 79.99, Yearly: 299.99 },
  "Pro Gym Access": { Monthly: 49.99, Quarterly: 129.99, Yearly: 449.99 },
  "Elite Unlimited": { Monthly: 69.99, Quarterly: 179.99, Yearly: 599.99 }
};

function updatePrice() {
  const plan = document.getElementById('planSelect').value;
  const billing = document.getElementById('billingSelect').value;
  const price = prices[plan][billing];
  document.getElementById('priceDisplay').value = `$${price.toFixed(2)}`;
}

window.addEventListener('DOMContentLoaded', updatePrice);
document.getElementById('planSelect').addEventListener('change', updatePrice);
document.getElementById('billingSelect').addEventListener('change', updatePrice);

document.getElementById('subscriptionForm').addEventListener('submit', function (e) {
  const method = document.getElementById('methodSelect').value;
  if (method === "Credit Card") {
    e.preventDefault(); // Stop regular form submit

    const plan = document.getElementById('planSelect').value;
    const billing = document.getElementById('billingSelect').value;
    const memberId = document.querySelector('input[name="member_id"]').value;
    const price = prices[plan][billing];

    const form = document.createElement("form");
    form.action = "stripe_payment.php";
    form.method = "POST";

    const data = {
      plan: plan,
      billing: billing,
      member_id: memberId,
      price: price
    };

    for (let key in data) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = data[key];
      form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
  }
});
</script>
