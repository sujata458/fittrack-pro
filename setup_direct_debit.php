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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bank_name'], $_POST['bsb'], $_POST['account_number'], $_POST['authorization'])) {
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $bsb = mysqli_real_escape_string($conn, $_POST['bsb']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);

    $insert = mysqli_query($conn, "INSERT INTO direct_debit (member_id, bank_name, bsb, account_number, setup_date)
        VALUES ('$member_id', '$bank_name', '$bsb', '$account_number', NOW())");

    if ($insert) {
        $alert = "<div class='alert alert-success'>✅ Direct debit setup successfully!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>❌ Setup failed: " . mysqli_error($conn) . "</div>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="container mt-5">
  <div class="bg-white p-5 rounded shadow-lg" style="max-width: 600px; margin: auto;">
    <h2 class="text-center text-success mb-4">
      <i class="fas fa-university me-2"></i>Setup Direct Debit
    </h2>
    <?= $alert ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Bank Name</label>
        <input type="text" name="bank_name" class="form-control shadow-sm" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">BSB</label>
        <input type="text" name="bsb" class="form-control shadow-sm" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Account Number</label>
        <input type="text" name="account_number" class="form-control shadow-sm" required>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="authorization" id="authorization" required>
        <label class="form-check-label text-muted" for="authorization">
          I authorize FitTrack-Pro to debit this account for recurring subscription payments.
        </label>
      </div>
      <button type="submit" class="btn btn-success w-100 fw-semibold">
        <i class="fas fa-paper-plane me-1"></i>Submit Authorization
      </button>
    </form>
  </div>
</div>

<?php include('includes/footer.php'); ?>
