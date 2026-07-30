<?php
require_once 'config.php';
$user_id = require_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

// NOTE (unchanged from the original project): this endpoint was already a
// non-functional demo/stub before this reorganization — it references a
// vendor/autoload.php (Stripe/PayPal SDKs via Composer) and placeholder API
// keys ('your_stripe_secret_key', etc.) that were never actually set up.
// It's kept structurally the same here; to make real payments work you'd
// need to run `composer require stripe/stripe-php` in backend/ and put in
// real Stripe/PayPal credentials.
$vendor_autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($vendor_autoload)) {
    send_error('Payment SDKs are not installed. Run "composer require stripe/stripe-php" in backend/ and add real API keys to enable payments.', 501);
}
require_once $vendor_autoload;

\Stripe\Stripe::setApiKey('your_stripe_secret_key');

$paypal_client_id = 'your_paypal_client_id';
$paypal_secret = 'your_paypal_secret';
$paypal_url = 'https://api-m.sandbox.paypal.com';

$amount = (float) ($_POST['amount'] ?? 0) * 100;
$payment_method = $_POST['payment_method'] ?? '';
$transaction_id = '';
$status = '';

if ($payment_method === 'stripe') {
    try {
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Property Payment',
            'source' => $_POST['stripeToken'] ?? '',
        ]);
        $transaction_id = $charge->id;
        $status = 'Success';
    } catch (Exception $e) {
        send_error('Stripe Payment Failed: ' . $e->getMessage());
    }
} elseif ($payment_method === 'paypal') {
    $paypal_data = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'amount' => ['currency_code' => 'USD', 'value' => $_POST['amount'] ?? 0]
        ]]
    ];

    $ch = curl_init("$paypal_url/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode("$paypal_client_id:$paypal_secret")
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paypal_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    if (isset($response->id)) {
        send_success(['redirect_url' => "https://www.sandbox.paypal.com/checkoutnow?token={$response->id}"]);
    } else {
        send_error('PayPal Payment Failed');
    }
} else {
    send_error('Unknown payment method.');
}

mysqli_query($conn, "INSERT INTO transactions (user_id, amount, status, transaction_id, payment_method)
                      VALUES ('$user_id', '$amount', '$status', '$transaction_id', '$payment_method')");

send_success(['message' => 'Payment Successful!']);
