<?php
session_start();
require 'vendor/autoload.php'; // Include Stripe & PayPal SDKs

// Stripe API Configuration
\Stripe\Stripe::setApiKey('your_stripe_secret_key');

// PayPal API Configuration
$paypal_client_id = 'your_paypal_client_id';
$paypal_secret = 'your_paypal_secret';
$paypal_url = "https://api-m.sandbox.paypal.com"; // Use live URL for production

include '../database/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'] * 100; // Convert to cents
    $payment_method = $_POST['payment_method'];
    
    if ($payment_method == 'stripe') {
        // Stripe Payment Processing
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'description' => 'Property Payment',
                'source' => $_POST['stripeToken'],
            ]);
            
            $transaction_id = $charge->id;
            $status = 'Success';
        } catch (Exception $e) {
            echo "<script>alert('Stripe Payment Failed: " . $e->getMessage() . "');</script>";
            exit;
        }
    } elseif ($payment_method == 'paypal') {
        // PayPal Payment Processing
        $paypal_data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $_POST['amount']
                ]
            ]]
        ];

        $ch = curl_init("$paypal_url/v2/checkout/orders");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode("$paypal_client_id:$paypal_secret")
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paypal_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = json_decode(curl_exec($ch));
        if (isset($response->id)) {
            header("Location: https://www.sandbox.paypal.com/checkoutnow?token={$response->id}");
            exit;
        } else {
            echo "<script>alert('PayPal Payment Failed');</script>";
            exit;
        }
    }
    
    // Store transaction details
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO transactions (user_id, amount, status, transaction_id, payment_method) VALUES ('$user_id', '$amount', '$status', '$transaction_id', '$payment_method')";
    mysqli_query($conn, $sql);
    
    echo "<script>alert('Payment Successful!'); window.location.href='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <h2>Make a Payment</h2>
    <form action="payment.php" method="POST">
        <input type="number" name="amount" placeholder="Enter Amount" required>
        <select name="payment_method" required>
            <option value="stripe">Credit/Debit Card (Stripe)</option>
            <option value="paypal">PayPal</option>
        </select>
        
        <!-- Stripe Payment Button -->
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="your_stripe_publishable_key"
            data-amount="5000"
            data-name="BidMyProperty"
            data-description="Property Payment"
            data-currency="usd">
        </script>
        
        <!-- PayPal Payment Button -->
        <button type="submit" name="paypal_pay">Pay with PayPal</button>
    </form>
</body>
</html>
