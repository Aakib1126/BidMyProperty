<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('POST required.', 405);
}

$property_id = (int) ($_POST['property_id'] ?? 0);
$bid_amount = (float) ($_POST['bid_amount'] ?? 0);

$result = mysqli_query($conn, "SELECT highest_bid FROM properties WHERE id='$property_id'");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    send_error('Property not found.', 404);
}

if ($bid_amount > $row['highest_bid']) {
    mysqli_query($conn, "UPDATE properties SET highest_bid='$bid_amount' WHERE id='$property_id'");
    send_success(['new_bid' => $bid_amount]);
} else {
    send_error('Bid must be higher than the current price!');
}
