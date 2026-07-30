<?php
require_once 'config.php';

// GET: list all available properties for bidding
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM properties WHERE status = 'available'");
    $properties = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $properties[] = $row;
    }
    send_json(['success' => true, 'properties' => $properties]);
}

// POST: place a bid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = require_user();
    $property_id = (int) ($_POST['property_id'] ?? 0);
    $bid_amount = (float) ($_POST['bid_amount'] ?? 0);

    $res = mysqli_query($conn, "SELECT highest_bid FROM properties WHERE id='$property_id'");
    $row = mysqli_fetch_assoc($res);
    if (!$row) send_error('Property not found.', 404);

    if ($bid_amount > $row['highest_bid']) {
        mysqli_query($conn, "UPDATE properties SET highest_bid='$bid_amount' WHERE id='$property_id'");
        mysqli_query($conn, "INSERT INTO bids (user_id, property_id, bid_amount) VALUES ('$user_id', '$property_id', '$bid_amount')");
        send_success(['message' => 'Bid placed successfully!']);
    } else {
        send_error('Your bid must be higher than the current highest bid.');
    }
}

send_error('Unsupported method.', 405);
