<?php
require 'vendor/autoload.php'; // Path to Stripe PHP library, adjust if needed

\Stripe\Stripe::setApiKey('sk_test_51ROSOABnT7Dq5pseCiwYyxtpCuQVId1jPVP6OzfQ6ZmaK9XEVDQT9fReYsvXzodwiBwR12ReqVWEyAqy95AdHJTY00zYBtMqAt'); // Replace with your actual secret key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan = $_POST['plan'] ?? 'Basic Fit';
    $billing = $_POST['billing'] ?? 'Monthly';
    $price = $_POST['price'] ?? 29.99;
    $member_id = $_POST['member_id'] ?? 0;

    // Convert price to cents for Stripe
    $amount = intval(floatval($price) * 100);

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => "$plan - $billing Membership",
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/fittrack-pro/payment_success.php?session_id={CHECKOUT_SESSION_ID}&mid=' . $member_id,
        'cancel_url' => 'http://localhost/fittrack-pro/manage_subscription.php',
    ]);

    header("Location: " . $session->url);
    exit();
} else {
    echo "Invalid access.";
}
?>
