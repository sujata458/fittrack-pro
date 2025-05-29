<?php
require_once __DIR__ . '/vendor/autoload.php'; // Stripe + mPDF
require_once __DIR__ . '/includes/db_config.php';

\Stripe\Stripe::setApiKey('sk_test_51ROSOABnT7Dq5pseCiwYyxtpCuQVId1jPVP6OzfQ6ZmaK9XEVDQT9fReYsvXzodwiBwR12ReqVWEyAqy95AdHJTY00zYBtMqAt'); // Replace with your actual secret key

if (!isset($_GET['session_id']) || !isset($_GET['mid'])) {
    die("Invalid access.");
}

$session_id = $_GET['session_id'];
$member_id = intval($_GET['mid']);

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    $amount = $session->amount_total / 100;
    $description = $session->metadata['description'] ?? $session->display_items[0]['custom']['name'] ?? 'Membership';
    $plan_parts = explode(' - ', $description);
    $plan = $plan_parts[0] ?? 'Basic Fit';
    $billing = str_replace(' Membership', '', $plan_parts[1] ?? 'Monthly');

    $interval = match ($billing) {
        'Monthly' => '+1 month',
        'Quarterly' => '+3 months',
        'Yearly' => '+12 months',
        default => '+1 month'
    };
    $next_billing_date = date('Y-m-d', strtotime($interval));

    // Insert subscription into DB
    $insert = mysqli_query($conn, "INSERT INTO subscriptions (member_id, plan_name, price, billing_cycle, next_billing_date, payment_method, status, created_at)
        VALUES ('$member_id', '$plan', '$amount', '$billing', '$next_billing_date', 'Credit Card', 'Active', NOW())");

    if (!$insert) {
        throw new Exception("DB insert failed: " . mysqli_error($conn));
    }

    // Generate invoice PDF
    $html = "
    <h2 style='color:#333;'>Gym Membership Invoice</h2>
    <p><strong>Member ID:</strong> $member_id</p>
    <p><strong>Plan:</strong> $plan</p>
    <p><strong>Billing Cycle:</strong> $billing</p>
    <p><strong>Amount Paid:</strong> \$$amount</p>
    <p><strong>Next Billing Date:</strong> $next_billing_date</p>
    <p><strong>Date:</strong> " . date('Y-m-d') . "</p>
    ";

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $invoice_name = "invoice_" . time() . ".pdf";
    $invoice_path = "invoices/" . $invoice_name;
    $mpdf->Output($invoice_path, \Mpdf\Output\Destination::FILE);

    echo "<div style='text-align:center; padding-top:50px;'>
        <h2>✅ Payment Successful!</h2>
        <p>Your subscription has been activated.</p>
        <a href='$invoice_path' target='_blank' class='btn btn-success'>📄 Download Invoice (PDF)</a><br><br>
        <a href='member_dashboard.php' class='btn btn-primary'>Go to Dashboard</a>
    </div>";

} catch (Exception $e) {
    echo "❌ Stripe or DB Error: " . $e->getMessage();
}
?>
